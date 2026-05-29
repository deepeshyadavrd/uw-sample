<?php
// 1. Load OpenCart Configurations & DB Framework
if (file_exists('config.php')) {
    require_once('config.php');
} else {
    exit('Error: config.php missing.');
}

require_once(DIR_SYSTEM . 'library/db.php');
require_once(DIR_SYSTEM . 'library/db/mysqli.php');

$db = new DB('mysqli', 'localhost', 'root', '', 'beta_uw', 3306);

echo "<h3>Starting OpenCart Database Deep Clean...</h3>";

// 2. Define target tables and columns to scrub
$targets = [
    'oc_blog_description'    => 'description'
];

foreach ($targets as $table => $column) {
    // Fetch records that contain suspected HTML styling clutter
    $query = $db->query("SELECT * FROM `" . $table . "` WHERE `" . $column . "` LIKE '%style=%' OR `" . $column . "` LIKE '%<p>%';");
    
    $cleaned_count = 0;
    
    if ($query->num_rows) {
        foreach ($query->rows as $row) {
            $id_column = isset($row['information_id']) ? 'information_id' : 'product_id';
            $lang_id   = $row['language_id'];
            $raw_data  = $row[$column];
            
            if (empty(trim($raw_data))) continue;

            // STEP 1: DECODE UNTIL IT IS PURE RAW HTML
            // We loop 5 times to peel back deep double-encodings (like &amp;amp;lt;)
            $html = $raw_data;
            for ($i = 0; $i < 5; $i++) {
                $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
            }

            // STEP 2: STRIP ALL SPANS COMPLETELY
            $html = preg_replace('/<\s*\/?span\s*.*?>/is', '', $html);

            // STEP 3: STRIP EVERY ATTRIBUTE FROM LI AND P TAGS
            // Wipes out aria-level, role, or anything inside <li ...> and <p ...>
            $html = preg_replace('/<(li|p)\b[^>]*>/is', '<$1>', $html);

            // STEP 4: STRIP GENERAL STYLES AND DIRECTIONS
            $html = preg_replace('/style=".*?"/is', '', $html);
            $html = preg_replace('/dir=".*?"/is', '', $html);

            // STEP 5: REMOVE EMPTY PARAGRAPHS
            $html = preg_replace('/<p>\s*(&nbsp;|\s)*\s*<\/p>/is', '', $html);

            // STEP 6: FLATTEN ALL NEWLINES TO REBUILD CLEAN FORMATTING
            $html = preg_replace('/(?:\r\n|\r|\n)+/s', ' ', $html);

            // STEP 7: APPLY EXACT NEWLINE STRUCTURAL FORMATTING RULES
            // Force opening tags onto fresh lines (\n)
            $html = preg_replace('/(<p>|<ul>|<ol>|<li>)/i', "\n$1", $html);
            // Force closing tags down—EXCLUDING THE CLOSING </li> TAGS
            $html = preg_replace('/(<\/p>|<\/ul>|<\/ol>)/i', "$1\n", $html);

            // Condense multiple running newlines down into single spacing steps
            $html = preg_replace("/\n+/", "\n", $html);
            $html = trim($html);

            // STEP 8: RE-ENCODE BACK TO THE EXACT OPENCART DATABASE STANDARD
            $final_db_string = htmlentities($html, ENT_QUOTES, 'UTF-8', false);
            $final_db_string = str_replace(
                array('&amp;lt;', '&amp;gt;', '&amp;quot;', '&amp;amp;'), 
                array('&lt;', '&gt;', '&quot;', '&amp;'), 
                $final_db_string
            );

            // STEP 9: EXECUTE SAVE IF CHANGES ARE DETECTED
            if (trim($raw_data) !== trim($final_db_string)) {
                $db->query("UPDATE `" . $table . "` SET `" . $column . "` = '" . $db->escape($final_db_string) . "' WHERE `" . $id_column . "` = '" . (int)$row[$id_column] . "' AND `language_id` = '" . (int)$lang_id . "'");
                $cleaned_count++;
            }
        }
    }
    echo "Table <strong>{$table}</strong> complete. Cleaned and formatted {$cleaned_count} records.<br>";
}

echo "<h4>Database code structural styling complete! Refresh your caches and remove db_cleaner.php.</h4>";
?>