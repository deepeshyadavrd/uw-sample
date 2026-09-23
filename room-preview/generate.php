<?php
/**
 * generate-mask.php
 * -----------------------------------------------------------------
 * Hybrid approach: pastes the product's REAL photo onto the room
 * (pixel-perfect, guaranteed accurate), then uses a masked inpaint
 * call to Cloudflare Workers AI so the model only blends the edges
 * and adds a contact shadow -- it never touches the product itself.
 *
 * This solves the "my product isn't actually in the image" problem
 * from the plain img2img version, while staying fully free (no
 * card) via Cloudflare's free daily allocation.
 *
 * LIMITATIONS (be aware of these):
 *   - Placement is rule-based (by product_category), not true scene
 *     understanding -- no perspective correction. Works best on
 *     reasonably front-on room photos.
 *   - Background removal on the product photo is a simple near-white
 *     color-key. Works well for catalog photos on a plain white/light
 *     background; poorly on photos with busy/dark backgrounds.
 *   - The masked inpaint step is a light touch-up (shadow + edge
 *     blend) -- it does not fix bad placement or wrong scale.
 *
 * SETUP
 *   Same as generate-cf.php: CLOUDFLARE_ACCOUNT_ID and
 *   CLOUDFLARE_API_TOKEN as server environment variables. No card
 *   needed for the free daily allocation.
 *
 * REQUEST (multipart/form-data, POST)
 *   room_image         - file, the customer's uploaded room photo
 *   product_image       - file, the product's real photo (ideally on
 *                        a plain white/light background)
 *   product_image_url    - string, alternative to product_image --
 *                        provide exactly one of the two
 *   product_name          - string
 *   product_category      - optional string, e.g. "Sofa", "Wall Art",
 *                        "Rug", "Lamp" -- drives placement rules
 *
 * RESPONSE (JSON)
 *   { "success": true, "image_base64": "...", "mime_type": "image/png" }
 *   { "success": false, "error": "message" }
 * -----------------------------------------------------------------
 */

header('Content-Type: application/json');

// ---- Config ---------------------------------------------------------
const MODEL_ID = '@cf/stabilityai/stable-diffusion-xl-base-1.0'; // only this one in the family is confirmed to support image_b64 + mask
const MAX_UPLOAD_BYTES = 8 * 1024 * 1024;
const WHITE_THRESHOLD = 245;   // pixels this bright or brighter become transparent
const MASK_BAND_PX = 28;       // width of the blend/shadow band around the product
const MASK_BLUR_PASSES = 3;    // feathering strength
const MAX_ROOM_DIMENSION = 1024; // Cloudflare's free allocation caps out here; larger images cost much more per call

const ALLOWED_IMAGE_HOSTS = [
    'localhost',
    '127.0.0.1',
];

function fail(string $message, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fail('Only POST is supported.', 405);
}

$accountId = getenv('CLOUDFLARE_ACCOUNT_ID');
$apiToken  = getenv('CLOUDFLARE_API_TOKEN');
if (!$accountId || !$apiToken) {
    fail('Server is missing CLOUDFLARE_ACCOUNT_ID or CLOUDFLARE_API_TOKEN.', 500);
}

if (empty($_FILES['room_image']) || $_FILES['room_image']['error'] !== UPLOAD_ERR_OK) {
    fail('room_image is required.');
}
if ($_FILES['room_image']['size'] > MAX_UPLOAD_BYTES) {
    fail('Room image exceeds the size limit.');
}

$productImageUrl = isset($_POST['product_image_url']) ? trim((string) $_POST['product_image_url']) : '';
$productName      = isset($_POST['product_name']) ? trim((string) $_POST['product_name']) : '';
$productCategory  = isset($_POST['product_category']) ? trim((string) $_POST['product_category']) : '';
$hasProductUpload = !empty($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK;

if (!$hasProductUpload && $productImageUrl === '') {
    fail('Provide either product_image (file) or product_image_url.');
}
if ($hasProductUpload && $productImageUrl !== '') {
    fail('Provide only one of product_image or product_image_url, not both.');
}
if ($productName === '') {
    fail('product_name is required.');
}
if ($productImageUrl !== '') {
    $host = parse_url($productImageUrl, PHP_URL_HOST);
    if (!$host || !in_array($host, ALLOWED_IMAGE_HOSTS, true)) {
        fail('product_image_url host is not allowed: ' . ($host ?: '(unparseable)'));
    }
}

// ---- Load images into GD --------------------------------------------
function loadGdImage(string $bytes) {
    $img = @imagecreatefromstring($bytes);
    if (!$img) {
        fail('Could not decode an image (unsupported format or corrupt file).', 400);
    }
    return $img;
}

$roomBytes = file_get_contents($_FILES['room_image']['tmp_name']);
$roomImgOriginal = loadGdImage($roomBytes);
$origW = imagesx($roomImgOriginal);
$origH = imagesy($roomImgOriginal);

// Resize down if needed -- keeps requests inside Cloudflare's free
// allocation bracket (<=1024x1024) and makes every step of this
// script faster.
if (max($origW, $origH) > MAX_ROOM_DIMENSION) {
    $scale = MAX_ROOM_DIMENSION / max($origW, $origH);
    $roomW = (int) round($origW * $scale);
    $roomH = (int) round($origH * $scale);
    $roomImg = imagecreatetruecolor($roomW, $roomH);
    imagecopyresampled($roomImg, $roomImgOriginal, 0, 0, 0, 0, $roomW, $roomH, $origW, $origH);
} else {
    $roomImg = $roomImgOriginal;
    $roomW = $origW;
    $roomH = $origH;
}

if ($hasProductUpload) {
    if ($_FILES['product_image']['size'] > MAX_UPLOAD_BYTES) {
        fail('Product image exceeds the size limit.');
    }
    $productBytes = file_get_contents($_FILES['product_image']['tmp_name']);
} else {
    $ch = curl_init($productImageUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_FOLLOWLOCATION => false,
    ]);
    $productBytes = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($productBytes === false || $httpCode >= 400) {
        fail('Could not fetch product image (HTTP ' . $httpCode . ').', 502);
    }
}
$productImgRaw = loadGdImage($productBytes);

// ---- Remove near-white background from the product photo -------------
function removeWhiteBackground($src, int $threshold) {
    $w = imagesx($src);
    $h = imagesy($src);
    $out = imagecreatetruecolor($w, $h);
    imagesavealpha($out, true);
    imagealphablending($out, false);
    $transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
    imagefill($out, 0, 0, $transparent);

    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $rgb = imagecolorat($src, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            if ($r >= $threshold && $g >= $threshold && $b >= $threshold) {
                continue; // leave transparent
            }
            $color = imagecolorallocatealpha($out, $r, $g, $b, 0);
            imagesetpixel($out, $x, $y, $color);
        }
    }
    return $out;
}

$productCutoutRaw = removeWhiteBackground($productImgRaw, WHITE_THRESHOLD);

// ---- Zoom in: crop the cutout to its actual opaque content -----------
function getOpaqueBoundingBox($img): ?array {
    $w = imagesx($img);
    $h = imagesy($img);
    $minX = $w; $minY = $h; $maxX = -1; $maxY = -1;

    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $rgba = imagecolorat($img, $x, $y);
            $alpha = ($rgba >> 24) & 0x7F;
            if ($alpha < 120) {
                if ($x < $minX) $minX = $x;
                if ($x > $maxX) $maxX = $x;
                if ($y < $minY) $minY = $y;
                if ($y > $maxY) $maxY = $y;
            }
        }
    }

    if ($maxX < $minX || $maxY < $minY) {
        return null;
    }
    return [$minX, $minY, $maxX, $maxY];
}

function cropToBoundingBox($img, array $bbox, int $marginPx = 4) {
    [$minX, $minY, $maxX, $maxY] = $bbox;
    $w = imagesx($img);
    $h = imagesy($img);

    $x1 = max(0, $minX - $marginPx);
    $y1 = max(0, $minY - $marginPx);
    $x2 = min($w - 1, $maxX + $marginPx);
    $y2 = min($h - 1, $maxY + $marginPx);

    $cropW = $x2 - $x1 + 1;
    $cropH = $y2 - $y1 + 1;

    $out = imagecreatetruecolor($cropW, $cropH);
    imagesavealpha($out, true);
    imagealphablending($out, false);
    $transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
    imagefill($out, 0, 0, $transparent);
    imagecopy($out, $img, 0, 0, $x1, $y1, $cropW, $cropH);

    return $out;
}

$bbox = getOpaqueBoundingBox($productCutoutRaw);
$productCutout = $bbox ? cropToBoundingBox($productCutoutRaw, $bbox) : $productCutoutRaw;
$cutW = imagesx($productCutout);
$cutH = imagesy($productCutout);

// ---- Decide placement + scale from category (simple rules) ------------
function decidePlacement(string $category, int $roomW, int $roomH, int $cutW, int $cutH): array {
    $cat = strtolower($category);
    $aspect = $cutH / max(1, $cutW);

    if (str_contains($cat, 'wall') || str_contains($cat, 'art') || str_contains($cat, 'mirror') || str_contains($cat, 'frame') || str_contains($cat, 'clock')) {
        $targetW = (int) round($roomW * 0.25);
        $targetH = (int) round($targetW * $aspect);
        $x = (int) round(($roomW - $targetW) / 2);
        $y = (int) round($roomH * 0.22);
    } elseif (str_contains($cat, 'rug') || str_contains($cat, 'carpet')) {
        $targetW = (int) round($roomW * 0.6);
        $targetH = (int) round($targetW * $aspect);
        $x = (int) round(($roomW - $targetW) / 2);
        $y = (int) round($roomH * 0.72);
    } elseif (str_contains($cat, 'lamp') || str_contains($cat, 'vase') || str_contains($cat, 'decor') || str_contains($cat, 'plant')) {
        $targetW = (int) round($roomW * 0.15);
        $targetH = (int) round($targetW * $aspect);
        $x = (int) round($roomW * 0.7);
        $y = (int) round($roomH * 0.55);
    } else {
        // default: floor-standing furniture (sofa, table, chair, etc.)
        $targetW = (int) round($roomW * 0.35);
        $targetH = (int) round($targetW * $aspect);
        $x = (int) round(($roomW - $targetW) / 2);
        $y = (int) round($roomH * 0.55);
    }

    // clamp inside room bounds
    $x = max(0, min($x, $roomW - $targetW));
    $y = max(0, min($y, $roomH - $targetH));

    return [$x, $y, $targetW, $targetH];
}

[$px, $py, $pw, $ph] = decidePlacement($productCategory, $roomW, $roomH, $cutW, $cutH);

// ---- Composite the real product photo onto the room -------------------
$composited = imagecreatetruecolor($roomW, $roomH);
imagecopy($composited, $roomImg, 0, 0, 0, 0, $roomW, $roomH);

$resizedCutout = imagecreatetruecolor($pw, $ph);
imagesavealpha($resizedCutout, true);
imagealphablending($resizedCutout, false);
$transparent = imagecolorallocatealpha($resizedCutout, 0, 0, 0, 127);
imagefill($resizedCutout, 0, 0, $transparent);
imagecopyresampled($resizedCutout, $productCutout, 0, 0, 0, 0, $pw, $ph, $cutW, $cutH);

imagealphablending($composited, true);
imagecopy($composited, $resizedCutout, $px, $py, 0, 0, $pw, $ph);

// ---- Build the mask: white band around the product, black elsewhere ---
$mask = imagecreatetruecolor($roomW, $roomH);
$black = imagecolorallocate($mask, 0, 0, 0);
$white = imagecolorallocate($mask, 255, 255, 255);
imagefill($mask, 0, 0, $black);

// outer band (white) -- expanded bbox, plus extra room below for a shadow
$outerX1 = max(0, $px - MASK_BAND_PX);
$outerY1 = max(0, $py - MASK_BAND_PX);
$outerX2 = min($roomW - 1, $px + $pw + MASK_BAND_PX);
$outerY2 = min($roomH - 1, $py + $ph + (int) round(MASK_BAND_PX * 1.8)); // extra below for shadow
imagefilledrectangle($mask, $outerX1, $outerY1, $outerX2, $outerY2, $white);

// inner area (black again) -- the product's own footprint stays untouched
imagefilledrectangle($mask, $px, $py, $px + $pw, $py + $ph, $black);

for ($i = 0; $i < MASK_BLUR_PASSES; $i++) {
    imagefilter($mask, IMG_FILTER_GAUSSIAN_BLUR);
}

// ---- Encode both images as base64 for the API -------------------------
function gdToBase64Png($img): string {
    ob_start();
    imagepng($img);
    $bytes = ob_get_clean();
    return base64_encode($bytes);
}

// Cloudflare's `mask` parameter requires a raw byte array, not a base64
// string (unlike `image_b64`, which does accept a string) -- confirmed
// by the API's own "Type mismatch of '/mask', 'array' not in 'string'" error.
function gdToByteArray($img): array {
    ob_start();
    imagepng($img);
    $bytes = ob_get_clean();
    return array_values(unpack('C*', $bytes));
}

$compositedBytes = gdToByteArray($composited);
$maskBytes = gdToByteArray($mask);

// ---- Build prompt (blend/shadow only, not a redesign) ------------------
$prompt = "Blend the edges of the {$productName} naturally into this room and add a soft, realistic "
        . "contact shadow beneath it, matching the room's existing lighting. Do not change the "
        . "product's shape, color, or design -- only blend its edges and shadow into the scene.";
$negativePrompt = "different product, redesigned object, distorted shape, extra items, blurry";

// ---- Call Cloudflare Workers AI (masked inpaint) -----------------------
$endpoint = "https://api.cloudflare.com/client/v4/accounts/{$accountId}/ai/run/" . MODEL_ID;
$payload = [
    'prompt' => $prompt,
    'negative_prompt' => $negativePrompt,
    'image' => $compositedBytes,
    'mask' => $maskBytes,
    'strength' => 0.3, // light touch -- blend only, don't repaint the product
    'num_steps' => 10, // lower steps = lower cost per call; raise back toward 20 if blend quality looks rough
];

$ch = curl_init($endpoint);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $apiToken,
        'Content-Type: application/json',
    ],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => 60,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$curlErr = curl_error($ch);
curl_close($ch);

if ($response === false) {
    fail('Request to Cloudflare Workers AI failed: ' . $curlErr, 502);
}
if ($httpCode >= 400) {
    fail('Cloudflare Workers AI returned an error (HTTP ' . $httpCode . '): ' . substr($response, 0, 500), 502);
}

if ($contentType && str_starts_with($contentType, 'image/')) {
    echo json_encode([
        'success' => true,
        'image_base64' => base64_encode($response),
        'mime_type' => $contentType,
    ]);
    exit;
}

$decoded = json_decode($response, true);
if (!$decoded) {
    fail('Could not parse Cloudflare response: ' . substr($response, 0, 500), 502);
}
$imageBase64 = $decoded['result']['image'] ?? $decoded['image'] ?? null;
if (!$imageBase64) {
    fail('No image found in Cloudflare response: ' . substr($response, 0, 500), 502);
}

echo json_encode([
    'success' => true,
    'image_base64' => $imageBase64,
    'mime_type' => 'image/png',
]);