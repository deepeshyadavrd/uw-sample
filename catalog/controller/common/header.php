<?php

class ControllerCommonHeader extends Controller {

	public function index() {

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}
		$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);
		$data['robots'] = false;
		if ($category_id == 154){
			header("X-Robots-Tag: noindex, follow", true);
			$data['robots'] = '<meta name="robots" content="noindex, follow">';
		}

		$data['title'] = $this->document->getTitle();
		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();

		$data['keywords'] = $this->document->getKeywords();
		// $data['links'] = $this->document->getLinks();

		// $data['styles'] = $this->document->getStyles();

		// $data['scripts'] = $this->document->getScripts('header');

		// $data['lang'] = $this->language->get('code');

		// $data['direction'] = $this->language->get('direction');

        	//print_r($_SERVER);
			$req_uri = end(explode('/',$_SERVER['REQUEST_URI']));
			$req_uri = strtok($req_uri, '?');
			$req_uri = strtok($req_uri, '&');
		$data['canonical'] = '';
		if($data['title']!='The page you requested cannot be found!'){
			$data['canonical'] = $server.strtolower($req_uri);
		}
print_r($req_uri);
		// $data['name'] = $this->config->get('config_name');

		// $data['cart_items'] = sprintf($this->cart->countProducts());
		
		// $data['customer_name'] = $this->customer->getFirstName();

		// $this->load->language('common/header');

		// $this->document->setTitle($this->language->get('title'));
	
		// Wishlist
		// if ($this->customer->isLogged()) {
			
		// 	$this->load->model('account/wishlist');
		// 	$data['wishlist_items'] = sprintf($this->model_account_wishlist->getTotalWishlist());
		// 	$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());

		// } else {

		// 	$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));

		// }

		// $data['trackorder'] = $this->url->link('account/trackorder', '', true);

		// $data['logged'] = $this->customer->isLogged();

		// $data['logout'] = $this->url->link('account/logout', '', true);

		// $data['language'] = $this->load->controller('common/language');

		return $this->load->view('common/header', $data);

	}

}

