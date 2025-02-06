<?php
class ModelExtensionModuleSitemap extends Model {
    public function generateProductSitemap() {
        $products = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE status = 1");
        
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($products->rows as $product) {
            $xml .= "<url><loc>" . HTTPS_CATALOG . "index.php?route=product/product&product_id=" . $product['product_id'] . "</loc></url>\n";
        }

        $xml .= "</urlset>";

        file_put_contents(DIR_CATALOG . '../product_sitemap.xml.gz', gzencode($xml));
    }

    public function generateCategorySitemap() {
        $categories = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE status = 1");

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($categories->rows as $category) {
            $xml .= "<url><loc>" . HTTPS_CATALOG . "index.php?route=product/category&category_id=" . $category['category_id'] . "</loc></url>\n";
        }

        $xml .= "</urlset>";

        file_put_contents(DIR_CATALOG . '../category_sitemap.xml', $xml);
    }

    public function generateInfoSitemap() {
        $infoPages = $this->db->query("SELECT information_id FROM " . DB_PREFIX . "information WHERE status = 1");

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($infoPages->rows as $info) {
            $xml .= "<url><loc>" . HTTPS_CATALOG . "index.php?route=information/information&information_id=" . $info['information_id'] . "</loc></url>\n";
        }

        $xml .= "</urlset>";

        file_put_contents(DIR_CATALOG . '../info_sitemap.xml', $xml);
    }

    public function saveMainSitemap($content) {
        file_put_contents(DIR_CATALOG . '../main_sitemap.xml', $content);
    }
}
