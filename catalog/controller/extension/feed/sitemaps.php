<?php
class ControllerExtensionFeedSitemaps extends Controller {
    public function index() {

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
    
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
        $xml .= '<sitemap>';
        $xml .= '<loc>' . HTTPS_SERVER . 'sitemap-products.xml</loc>';
        $xml .= '</sitemap>';
    
        $xml .= '<sitemap>';
        $xml .= '<loc>' . HTTPS_SERVER . 'sitemap-categories.xml</loc>';
        $xml .= '</sitemap>';
    
        $xml .= '<sitemap>';
        $xml .= '<loc>' . HTTPS_SERVER . 'sitemap-pages.xml</loc>';
        $xml .= '</sitemap>';
    
        $xml .= '</sitemapindex>';
    
        file_put_contents(DIR_APPLICATION . '../sitemap-base.xml', $xml);
    
        $this->response->addHeader('Content-Type: application/xml');
        $this->response->setOutput($xml);
    }
    public function products() {
        $this->load->model('extension/feed/sitemaps');
        $this->load->model('tool/image');

        $products = $this->model_extension_feed_sitemaps->getProducts();

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $xml .= 'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

        foreach ($products as $product) {

            $xml .= '<url>';
            $xml .= '<loc>' .
                htmlspecialchars(
                    $this->url->link(
                        'product/product',
                        'product_id=' . $product['product_id'],
                        true
                    )
                ) .
                '</loc>';
                    
            $xml .= '<changefreq>daily</changefreq>';
                    
            if (!empty($product['date_modified'])) {
            
                $xml .= '<lastmod>' .
                    date('Y-m-d', strtotime($product['date_modified'])) .
                    '</lastmod>';
            }
        
            $xml .= '<priority>0.8</priority>';
        
            if (!empty($product['image'])) {
            
                $image = $this->model_tool_image->resize(
                    $product['image'],
                    $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'),
                    $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')
                );
            
                $xml .= '<image:image>';
            
                $xml .= '<image:loc>' .
                    htmlspecialchars($image) .
                    '</image:loc>';
            
                $xml .= '<image:caption><![CDATA[' .
                    $product['name'] .
                    ']]></image:caption>';
            
                $xml .= '<image:title><![CDATA[' .
                    $product['name'] .
                    ']]></image:title>';
            
                $xml .= '</image:image>';
            }
        
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        file_put_contents( DIR_APPLICATION . '../sitemap-products.xml', $xml );

        echo 'Product sitemap generated';
    }

    public function categories() {
        $this->load->model('extension/feed/sitemaps');
        $categories = $this->model_extension_feed_sitemaps->getCategories();
    
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
        foreach ($categories as $category) {
    
            $xml .= '<url>';
    
            $xml .= '<loc>' .
                $this->url->link(
                    'product/category',
                    'path=' . $category['category_id'],
                    true
                ) .
                '</loc>';
    
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.7</priority>';
    
            $xml .= '</url>';
        }
    
        $xml .= '</urlset>';
    
        file_put_contents(DIR_APPLICATION . '../sitemap-categories.xml', $xml);
    
        $this->response->addHeader('Content-Type: application/xml');
        $this->response->setOutput($xml);
    }

    public function pages() {
        $this->load->model('extension/feed/sitemaps');
        $urls = [];

        // Homepage
        // $urls[] = HTTPS_SERVER;

        // Static pages
        // $urls[] = HTTPS_SERVER . 'contact-us';
        // $urls[] = HTTPS_SERVER . 'postal-address';
        $informations = $this->model_extension_feed_sitemaps->getInformations();
        // foreach ($informations as $info) {

        //     $urls[] = $this->url->link(
        //         'information/information',
        //         'information_id=' . $info['information_id'],
        //         true
        //     );
        // }
    
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
        $xml .= '<url>';
        $xml .= '<loc>' . HTTPS_SERVER . '</loc>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';
    
        foreach ($informations as $info) {
    
            $xml .= '<url>';
    
            $xml .= '<loc>' .
                $this->url->link(
                    'information/information',
                    'information_id=' . $info['information_id'],
                    true
                ) .
                '</loc>';
    
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.6</priority>';
    
            $xml .= '</url>';
        }
    
        $xml .= '</urlset>';
    
        file_put_contents(DIR_APPLICATION . '../sitemap-pages.xml', $xml);
    
        $this->response->addHeader('Content-Type: application/xml');
        $this->response->setOutput($xml);
    }

    public function generate() {

        $this->products();
        $this->categories();
        $this->pages();
        $this->index();
    
        echo 'Sitemaps Generated Successfully';
    }
}
