<?php
// NVIDIA-hosted FLUX.1 Kontext [dev]. Receives the room photo + product PNG + wall/floor hint,
// stitches them into one image (Kontext accepts only one), asks the AI to place the product in the room,
// then crops the result back to the room part.
// Needs PHP cURL + GD (both are standard on XAMPP/most hosts).
$NVIDIA_KEY = getenv('NVIDIA_API_KEY') ?: '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';
$MAX_PER_HOUR = 10;

header('Content-Type: application/json');
@set_time_limit(180);
function fail($m, $c = 500) { http_response_code($c); echo json_encode(['error' => $m]); exit; }
function toImg($d) { return (is_string($d) && preg_match('#^data:image/(jpeg|png|webp);base64,(.+)$#', $d, $m)) ? @imagecreatefromstring(base64_decode($m[2])) : null; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') fail('POST only', 405);
if (strpos($NVIDIA_KEY, 'PASTE_YOUR') !== false) fail('NVIDIA key not set in this file');
if (!function_exists('curl_init')) fail('PHP cURL extension is not enabled');
if (!function_exists('imagecreatetruecolor')) fail('PHP GD extension is not enabled');

// per-IP rate limit
$f = sys_get_temp_dir() . '/room_rl_' . md5($_SERVER['REMOTE_ADDR'] ?? 'x');
$hits = is_file($f) ? array_filter(array_map('intval', explode(',', file_get_contents($f))), function ($t) { return $t > time() - 3600; }) : [];
if (count($hits) >= $MAX_PER_HOUR) fail('Too many requests, try again later.', 429);
$hits[] = time(); file_put_contents($f, implode(',', $hits));

$in = json_decode(file_get_contents('php://input'), true) ?: [];
$room = toImg($in['room'] ?? ''); $prod = toImg($in['product'] ?? '');
if (!$room || !$prod) fail('Room or product image missing/invalid', 400);
$placement = (($in['placement'] ?? 'wall') === 'floor') ? 'floor' : 'wall';
$size = max(0.05, min(1.0, (float)($in['size'] ?? 0.35)));

// ---- 1. Build the stitched image: [ room | divider | product on white ] ----
$rw = imagesx($room); $rh = imagesy($room);
$s = min(1, 900 / $rw, 768 / $rh);
$RW = (int)round($rw * $s); $H = (int)round($rh * $s);
$PW = (int)round($RW * 0.6); $DIV = 6; $W = $RW + $DIV + $PW;

$canvas = imagecreatetruecolor($W, $H);
imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
imagecopyresampled($canvas, $room, 0, 0, 0, 0, $RW, $H, $rw, $rh);
imagefilledrectangle($canvas, $RW, 0, $RW + $DIV - 1, $H, imagecolorallocate($canvas, 40, 40, 40));

$pw = imagesx($prod); $ph = imagesy($prod);
$ps = min(($PW - 40) / $pw, ($H - 40) / $ph);
$tw = max(1, (int)round($pw * $ps)); $th = max(1, (int)round($ph * $ps));
$tmp = imagecreatetruecolor($tw, $th);                    // keep PNG transparency while resizing
imagealphablending($tmp, false); imagesavealpha($tmp, true);
imagefill($tmp, 0, 0, imagecolorallocatealpha($tmp, 255, 255, 255, 127));
imagecopyresampled($tmp, $prod, 0, 0, 0, 0, $tw, $th, $pw, $ph);
imagealphablending($canvas, true);
imagecopy($canvas, $tmp, $RW + $DIV + (int)(($PW - $tw) / 2), (int)(($H - $th) / 2), 0, 0, $tw, $th);

ob_start(); imagejpeg($canvas, null, 88); $stitched = 'data:image/jpeg;base64,' . base64_encode(ob_get_clean());

// ---- 2. Ask the AI ----
$where = $placement === 'floor'
  ? "standing on the floor (resting naturally on it with a realistic contact shadow, near a wall if suitable)"
  : "hanging or mounted on a wall at a natural height";
$prompt = "This image has two panels separated by a dark vertical line. The LEFT panel is a photo of a room. The RIGHT panel shows a product on a white background. "
  . "Edit the image: place the product from the right panel into the room in the left panel, $where, "
  . "at a realistic scale (roughly " . round($size * 100) . "% of the room photo's width), matching the room's lighting, color temperature and perspective, with natural shadows. "
  . "Keep the product's exact shape, colors and details. Do not change anything else in the room. Fill the right panel with plain white.";

$ch = curl_init('https://ai.api.nvidia.com/v1/genai/black-forest-labs/flux.1-kontext-dev');
curl_setopt_array($ch, [
  CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 170,
  // LOCAL XAMPP TESTING ONLY: uncomment if you get an SSL certificate error. Remove before going live.
  // CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $NVIDIA_KEY, 'Accept: application/json', 'Content-Type: application/json'],
  CURLOPT_POSTFIELDS => json_encode(['prompt' => $prompt, 'image' => $stitched, 'aspect_ratio' => 'match_input_image', 'steps' => 30, 'cfg_scale' => 3.5]),
]);
$body = curl_exec($ch); $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($body === false) fail('Connection error: ' . curl_error($ch));
curl_close($ch);
$j = json_decode($body, true);
if ($status >= 400) fail('NVIDIA error (' . $status . '): ' . substr($j['detail'] ?? $j['error'] ?? $j['message'] ?? $body, 0, 300));

$b64 = $j['image'] ?? ($j['artifacts'][0]['base64'] ?? ($j['images'][0] ?? null));
if (is_array($b64)) $b64 = $b64['b64_json'] ?? $b64['base64'] ?? null;
if (!$b64) fail('Unexpected response: ' . substr($body, 0, 300));
$raw = base64_decode(preg_replace('#^data:image/\w+;base64,#', '', $b64));

// ---- 3. Crop back to the room panel ----
$out = @imagecreatefromstring($raw);
if (!$out) fail('Could not read the AI result image');
$ow = imagesx($out); $oh = imagesy($out);
$cw = max(1, (int)round($ow * $RW / $W));
$crop = imagecrop($out, ['x' => 0, 'y' => 0, 'width' => $cw, 'height' => $oh]);
if (!$crop) fail('Could not crop the result');
ob_start(); imagejpeg($crop, null, 92);
echo json_encode(['image' => 'data:image/jpeg;base64,' . base64_encode(ob_get_clean())]);