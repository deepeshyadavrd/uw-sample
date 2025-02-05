<?php
class ModelExtensionFeedSitemaps extends Model {
    private function generateSitemapFile($filename, $urls) {
        $sitemapContent = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemapContent .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $sitemapContent .= "    <url>\n";
            $sitemapContent .= "        <loc>" . $this->url->link('product/product', 'product_id=' . $url) . "</loc>\n";
            // $sitemapContent .= "        <lastmod>" . date('Y-m-d') . "</lastmod>\n";
            $sitemapContent .= "        <changefreq>weekly</changefreq>\n";
            $sitemapContent .= "        <priority>0.8</priority>\n";
            $sitemapContent .= "    </url>\n";
        }

        $sitemapContent .= '</urlset>';
        // print_r($sitemapContent);
        file_put_contents(DIR_APPLICATION . '../' . $filename, $sitemapContent);
    }

    public function generateProductsSitemap() {
        $baseUrl = $this->config->get('config_url');
        $query = $this->db->query("SELECT  p.product_id AS url FROM " . DB_PREFIX . "product p WHERE p.status = 1");

        $urls = [];
        foreach ($query->rows as $row) {
            $urls[] = $row['url'];
        }

        $this->generateSitemapFile('sitemap-products.xml', $urls);
    }

    public function generateCategoriesSitemap() {
        $baseUrl = $this->config->get('config_url');
        $query = $this->db->query("SELECT CONCAT('$baseUrl', 'index.php?route=product/category&category_id=', c.category_id) AS url FROM " . DB_PREFIX . "category c WHERE c.status = 1");

        $urls = [];
        foreach ($query->rows as $row) {
            $urls[] = $row['url'];
        }

        $this->generateSitemapFile('sitemap-categories.xml', $urls);
    }

    public function generateInfoPagesSitemap() {
        $baseUrl = $this->config->get('config_url');
        $query = $this->db->query("SELECT CONCAT('$baseUrl', 'index.php?route=information/information&information_id=', i.information_id) AS url FROM " . DB_PREFIX . "information i WHERE i.status = 1");

        $urls = [];
        foreach ($query->rows as $row) {
            $urls[] = $row['url'];
        }

        $this->generateSitemapFile('sitemap-info.xml', $urls);
    }
}
?>
