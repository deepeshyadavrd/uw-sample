<?php
class ControllerExtensionFeedSitemaps extends Controller {
    public function index() {
        if ($this->config->get('feed_google_sitemap_status')) {
            $output = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $output .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            
            $baseUrl = $this->config->get('config_url');

            // Product Sitemap
            $output .= "    <sitemap>\n";
            $output .= "        <loc>{$baseUrl}sitemap-products.xml</loc>\n";
            $output .= "        <lastmod>" . date('Y-m-d') . "</lastmod>\n";
            $output .= "    </sitemap>\n";

            // Category Sitemap
            $output .= "    <sitemap>\n";
            $output .= "        <loc>{$baseUrl}sitemap-categories.xml</loc>\n";
            $output .= "        <lastmod>" . date('Y-m-d') . "</lastmod>\n";
            $output .= "    </sitemap>\n";

            // Information Pages Sitemap
            $output .= "    <sitemap>\n";
            $output .= "        <loc>{$baseUrl}sitemap-info.xml</loc>\n";
            $output .= "        <lastmod>" . date('Y-m-d') . "</lastmod>\n";
            $output .= "    </sitemap>\n";

            $output .= '</sitemapindex>';

            $this->response->addHeader('Content-Type: application/xml');
            $this->response->setOutput($output);
        }
    }
    public function generateSitemapIndex() {

        $base = HTTPS_SERVER;
    
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
        $xml .= '<sitemap>';
        $xml .= '<loc>' . $base . 'sitemap-products.xml</loc>';
        $xml .= '</sitemap>';
    
        $xml .= '<sitemap>';
        $xml .= '<loc>' . $base . 'sitemap-categories.xml</loc>';
        $xml .= '</sitemap>';
    
        $xml .= '<sitemap>';
        $xml .= '<loc>' . $base . 'sitemap-pages.xml</loc>';
        $xml .= '</sitemap>';
    
        $xml .= '</sitemapindex>';
    
        file_put_contents(DIR_APPLICATION . '../sitemap-base.xml', $xml);
    }

    public function generate() {
        $this->load->model('extension/feed/sitemaps');

        // Generate different sitemap sections
        $this->model_extension_feed_sitemaps->generateProductsSitemap();
        $this->model_extension_feed_sitemaps->generateCategoriesSitemap();
        $this->model_extension_feed_sitemaps->generateInfoPagesSitemap();

        echo "✅ Sitemaps Generated Successfully!";
    }
}
