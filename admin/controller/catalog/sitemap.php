<?php
class ControllerModuleSitemapGenerator extends Controller {
    public function index() {
        // $this->load->language('module/sitemap_generator');
        
        $this->document->setTitle('sitemap');

        // Load the model for sitemap generation
        $this->load->model('module/sitemap_generator');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            // Generate product, category, and main sitemaps
            $this->model_module_sitemap_generator->generateProductSitemap();
            $this->model_module_sitemap_generator->generateCategorySitemap();
            $this->model_module_sitemap_generator->generateMainSitemap();

            // Set a success message
            $this->session->data['success'] = 'success';
        }

        // Prepare the data for the view template
        $data['action'] = $this->url->link('module/sitemap_generator', 'user_token=' . $this->session->data['user_token'], true);
        $data['heading_title'] = 'Sitemap';
        $data['text_success'] = 'success';
        
        // Load the view template
        $this->response->setOutput($this->load->view('module/sitemap_generator', $data));
    }
}
