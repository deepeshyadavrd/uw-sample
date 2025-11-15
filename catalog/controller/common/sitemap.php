<?php
class ControllerCommonSitemap extends Controller {
	public function index() {
		$this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $categories = $this->model_catalog_category->getCategories(0);
        $data['categories'] = array();
        //print_r($categories);
		$this->document->setTitle('Sitemap | Urbanwood');
		$this->document->setDescription('Explore our sitemap for easy navigation to all pages, including furniture collections, home decor ideas, and exclusive Urbanwood designs. Find what you need fast!');
        foreach ($categories as $category) {
			//if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' .  $child['category_id'])
					);
				}
//print_r($children);
				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			//}
		}
		$this->load->model('catalog/information');
		$informations = $this->model_catalog_information->getInformations();
		//print_r($informations);
		foreach ($informations as $result) {
			
				$data['categories'][] = array(
					'name' => $result['title'],
					'children' => '',
					'column'   => $result['column'] ? $result['column'] : 1,
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			
		}
	
		function sort_by_children_length($a, $b) {
			$a_count = isset($a['children']) && is_array($a['children']) ? count($a['children']) : 0;
			$b_count = isset($b['children']) && is_array($b['children']) ? count($b['children']) : 0;

			return $a_count - $b_count;
		}
		
		usort($data['categories'], 'sort_by_children_length');
        //print_r($data);

        $data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
        
        $this->response->setOutput($this->load->view('common/sitemap', $data));
	}
}