<?php
// Load OpenCart config
require_once('config.php');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry setup
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Language
$language = new Language('en-gb');
$language->load('default');
$registry->set('language', $language);

// Load most recent products (customize as needed)
$query = $db->query("SELECT p.product_id, p.image, pd.name, p.price
                     FROM " . DB_PREFIX . "product p 
                     LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
                     ORDER BY p.viewed DESC 
                     LIMIT 10");

$data = [];

foreach ($query->rows as $row) {
    $data[] = [
        'id'    => $row['product_id'],
        'name'  => $row['name'],
        'image' => $row['image'],
        'price' => $row['price'],
    ];
}

// Save to JSON file
file_put_contents('most-popular.json', json_encode($data, JSON_PRETTY_PRINT));

echo "✅ most-popular.json generated!";
