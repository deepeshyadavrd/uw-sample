<?php
// Load OpenCart framework
require_once('../../index.php');

// Get the registry instance
$registry = $this;
$db = $registry->get('db');

// Fetch products marked as New Arrivals
$query = $db->query("SELECT product_id, image, pd.name 
                     FROM " . DB_PREFIX . "product p 
                     LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
                     WHERE p.status = 1 
                     AND p.date_available <= NOW()
                     ORDER BY p.date_added DESC 
                     LIMIT 10");

$products = [];

foreach ($query->rows as $row) {
    $products[] = [
        'title' => $row['name'],
        'link' => '/index.php?route=product/product&product_id=' . $row['product_id'],
        'image' => 'image/' . $row['image']
    ];
}

// Save to JSON
file_put_contents(__DIR__ . '/new-arrivals.json', json_encode($products));

echo "New Arrivals JSON cached successfully!";
?>
