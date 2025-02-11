<?php
class ControllerCatalogSitemap extends Controller {
    public function index() {
        // $this->load->language('module/sitemap_generator');
        
        $this->document->setTitle('sitemap');

        // Load the model for sitemap generation
        $this->load->model('catalog/sitemap');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            // Generate product, category, and main sitemaps
            $this->model_catalog_sitemap->generateProductSitemap();
            $this->model_catalog_sitemap->generateCategorySitemap();
            $this->model_catalog_sitemap->generateMainSitemap();

            // Set a success message
            $this->session->data['success'] = 'success';
        }

        // Prepare the data for the view template
        $data['action'] = $this->url->link('catalog/sitemap', 'user_token=' . $this->session->data['user_token'], true);
        $data['heading_title'] = 'Sitemap';
        $data['text_success'] = 'success';
        
        // Load the view template
        $this->response->setOutput($this->load->view('catalog/sitemap', $data));
    }
}
