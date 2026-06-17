<?php
// Load OpenCart

if (file_exists('config.php')) {
    require_once('config.php');
} else {
    exit('config.php not found');
}

require_once(DIR_SYSTEM . 'library/db.php');
require_once(DIR_SYSTEM . 'library/db/mysqli.php');

$db = new DB('mysqli', 'localhost', 'root', '', 'beta_uw', 3306 );

echo '<h2>Starting Deep HTML Cleanup...</h2>';

// Tables / Columns
$targets = [
    'oc_blog_description' => [
        'column' => 'description',
        'id'     => 'information_id'
    ]
];
$blog_id = isset($_GET['blog_id']) ? (int)$_GET['blog_id'] : 0;

if (!$blog_id) {
    die('Please provide blog_id');
}
// Process
foreach ($targets as $table => $info) {
    $column    = $info['column'];
    $id_column = $info['id'];
    $query = $db->query("SELECT * FROM `" . $table . "` WHERE information_id = '" . $blog_id . "'");
    $cleaned = 0;

    foreach ($query->rows as $row) {
        $raw_html = $row[$column];

        if (!$raw_html) {
            continue;
        }

        // Decode multiple times
        $html = $raw_html;

        for ($i = 0; $i < 5; $i++) {
            $decoded = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            if ($decoded === $html) {
                break;
            }
            $html = $decoded;
        }

        // Remove span tags only
        // $html = preg_replace('#</?span[^>]*>#i','',$html);
        // Convert bold spans to <b>
$html = preg_replace_callback(
    '#<span([^>]*)>(.*?)</span>#is',
    function ($matches) {

        $attrs   = $matches[1];
        $content = $matches[2];

        if (
            preg_match('/style\s*=\s*([\'"])(.*?)\1/is', $attrs, $style)
        ) {
            $css = strtolower($style[2]);

            if (
                preg_match('/font-weight\s*:\s*(700|800|900|bold)\b/i', $css)
            ) {
                return '<b>' . $content . '</b>';
            }
        }

        return $content; // remove non-bold span
    },
    $html
);

        // Remove ALL attributes from tags
        // Keep only tag names
        $allowed_tags = ['p','ul','ol','li','h1','h2','h3','h4','h5','h6','strong','b','em','i','br','a','img'];

        $html = preg_replace_callback(
            '/<([a-z0-9]+)(?:\s+[^>]*)?>/i',
            function ($matches) use ($allowed_tags) {
                $tag = strtolower($matches[1]);
                if (!in_array($tag, $allowed_tags)) {
                    return $matches[0];
                }
                if ($tag == 'a') {
                    return $matches[0];
                }
                if ($tag == 'img') {

                    preg_match('/src\s*=\s*([\'"])(.*?)\1/is', $matches[0], $src);
                    preg_match('/alt\s*=\s*([\'"])(.*?)\1/is', $matches[0], $alt);
                
                    return '<img src="' .
                        htmlspecialchars($src[2] ?? '', ENT_QUOTES, 'UTF-8') .
                        '" alt="' .
                        htmlspecialchars($alt[2] ?? '', ENT_QUOTES, 'UTF-8') .
                        '">';
                }
                return '<' . $tag . '>';
            },
            $html
        );

        // Clean A tags but keep href
        // $html = preg_replace_callback(
        //     '/<a\b([^>]*)>/i',
        //     function ($matches) {
        //         preg_match('/href\s*=\s*([\'"])(.*?)\1/i', $matches[1], $href);

        //         if (!empty($href[2])) {
        //             return '<a href="' .
        //                 htmlspecialchars($href[2], ENT_QUOTES, 'UTF-8') .
        //                 '">';
        //         }
        //         return '<a>';
        //     },
        //     $html
        // );

        // Remove empty tags
        do {

            $old_html = $html;

            $html = preg_replace('#<p>\s*(?:&nbsp;|\s)*</p>#i', '', $html);

            $html = preg_replace('#<(h[1-6])>\s*(?:&nbsp;|\s)*</\1>#i', '', $html);

            $html = preg_replace('#<li>\s*(?:&nbsp;|\s)*</li>#i', '', $html);
            $html = preg_replace(
                '#<p>\s*(?:&nbsp;|\s|<br\s*/?>)*\s*</p>#is',
                '',
                $html
            );
        
            $html = preg_replace(
                '#<li>\s*(?:&nbsp;|\s|<br\s*/?>)*\s*</li>#is',
                '',
                $html
            );
        
            $html = preg_replace(
                '#<(h[1-6])>\s*(?:&nbsp;|\s|<br\s*/?>)*\s*</\1>#is',
                '',
                $html
            );

        } while ($old_html !== $html);
        
        // Normalize whitespace
        $html = preg_replace('/[\r\n\t]+/', ' ', $html);
        $html = preg_replace('/\s{2,}/', ' ', $html);

        // Put tags on separate lines
        $tags = ['h1','h2','h3','h4','h5','h6','p','ul','ol','li'];

        foreach ($tags as $tag) {
            $html = preg_replace('#<' . $tag . '>#i', "\n<{$tag}>", $html );
            $html = preg_replace('#</' . $tag . '>#i', "</{$tag}>\n", $html);
        }

        $html = preg_replace("/\n{2,}/", "\n", $html);
        $html = trim($html);
        // file_put_contents('debug.html', $html);
        // exit;
        // Encode back
        $final = htmlentities($html, ENT_QUOTES, 'UTF-8');
        // file_put_contents('final.txt', $final);
        // exit;
        if ($final !== $raw_html) {

            $db->query("UPDATE `" . $table . "` SET `" . $column . "` = '" . $db->escape($final) . "' WHERE `" . $id_column . "` = '" . (int)$row[$id_column] . "' AND language_id = '" . (int)$row['language_id'] . "' ");

            $cleaned++;
        }
    }

    echo "<strong>{$table}</strong> : {$cleaned} records cleaned.<br>";
}

echo '<h3>Cleanup Complete.</h3>';