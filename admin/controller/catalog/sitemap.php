<?php
class ControllerCatalogSitemap extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('catalog/sitemap');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/sitemap');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_catalog_sitemap->saveMainSitemap($this->request->post['main_sitemap']);
            $this->session->data['success'] = 'Main sitemap updated successfully!';
            $this->response->redirect($this->url->link('catalog/sitemap', 'user_token=' . $this->session->data['user_token'], true));
        }

        // $data['breadcrumbs'] = array(
        //     array(
        //         'text' => 'Home',
        //         'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        //     ),
        //     array(
        //         'text' => 'Extensions',
        //         'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true)
        //     ),
        //     array(
        //         'text' => 'Sitemap Generator',
        //         'href' => $this->url->link('catalog/sitemap', 'user_token=' . $this->session->data['user_token'], true)
        //     )
        // );

        $data['generate_product_sitemap'] = $this->url->link('catalog/sitemap/generateProductSitemap', 'user_token=' . $this->session->data['user_token']);
        $data['generate_category_sitemap'] = $this->url->link('catalog/sitemap/generateCategorySitemap', 'user_token=' . $this->session->data['user_token']);
        $data['generate_info_sitemap'] = $this->url->link('catalog/sitemap/generateInfoSitemap', 'user_token=' . $this->session->data['user_token']);
        $data['save_main_sitemap'] = $this->url->link('catalog/sitemap', 'user_token=' . $this->session->data['user_token']);

        $data['main_sitemap'] = file_get_contents( DIR_ROOT . 'sitemap.xml');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/sitemap', $data));
    }

    public function generateProductSitemap() {
        $this->load->model('catalog/sitemap');
        $this->model_catalog_sitemap->generateProductSitemap();
        $this->session->data['success'] = 'Product Sitemap generated successfully!';
        $this->response->redirect($this->url->link('catalog/sitemap', 'user_token=' . $this->session->data['user_token']));
    }

    public function generateCategorySitemap() {
        $this->load->model('catalog/sitemap');
        $this->model_catalog_sitemap->generateCategorySitemap();
        $this->session->data['success'] = 'Category Sitemap generated successfully!';
        $this->response->redirect($this->url->link('catalog/sitemap', 'user_token=' . $this->session->data['user_token']));
    }

    public function generateInfoSitemap() {
        $this->load->model('catalog/sitemap');
        $this->model_catalog_sitemap->generateInfoSitemap();
        $this->session->data['success'] = 'Information Sitemap generated successfully!';
        $this->response->redirect($this->url->link('catalog/sitemap', 'user_token=' . $this->session->data['user_token']));
    }
    private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/sitemap')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
    
        return !$this->error;
    }
}
