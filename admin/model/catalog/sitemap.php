<?php
class ModelModuleSitemapGenerator extends Model {
    public function generateProductSitemap() {
        $file = DIR_ROOT . 'product_sitemap.xml';
        $xml = new SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        // Query to get all products
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product");

        foreach ($query->rows as $row) {
            $url = $xml->addChild('url');
            $url->addChild('loc', $this->url->link('product/product', 'product_id=' . $row['product_id']));
            $url->addChild('priority', '0.8');
            $url->addChild('changefreq', 'daily');
        }

        // Save the product sitemap
        $xml->asXML($file);
    }

    public function generateCategorySitemap() {
        $file = DIR_ROOT . 'category_sitemap.xml';
        $xml = new SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        // Query to get all categories
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category");

        foreach ($query->rows as $row) {
            $url = $xml->addChild('url');
            $url->addChild('loc', $this->url->link('product/category', 'path=' . $row['category_id']));
            $url->addChild('priority', '0.7');
            $url->addChild('changefreq', 'weekly');
        }

        // Save the category sitemap
        $xml->asXML($file);
    }

    public function generateMainSitemap() {
        $file = DIR_ROOT . 'sitemap.xml';
        $xml = new SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        // Links to other sitemaps
        $xml->addChild('url')->addChild('loc', HTTP_SERVER . 'product_sitemap.xml');
        $xml->addChild('url')->addChild('loc', HTTP_SERVER . 'category_sitemap.xml');

        // Save the main sitemap
        $xml->asXML($file);
    }
}
