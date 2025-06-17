<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		// $this->load->model('setting/extension');

		// $data['analytics'] = array();

		// $analytics = $this->model_setting_extension->getExtensions('analytics');
		
		// foreach ($analytics as $analytic) {
		// 	if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
		// 		$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
		// 	}
		// }

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		// $data['keywords'] = $this->document->getKeywords();
		// $data['links'] = $this->document->getLinks();
		// $data['robots'] = $this->document->getRobots();
		// $data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');
		// $data['cart_items'] = sprintf($this->cart->countProducts());
	
		
		// $data['customer_name'] = $this->customer->getFirstName();

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		// $this->load->language('common/header');

		// $this->document->setTitle($this->language->get('title'));
	

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');
			$data['wishlist_items'] = sprintf($this->model_account_wishlist->getTotalWishlist());
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		
		//$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		// $data['account'] = $this->url->link('account/account', '', true);
		// $data['register'] = $this->url->link('account/register', '', true);
		// $data['login'] = $this->url->link('account/login', '', true);
		// $data['order'] = $this->url->link('account/order', '', true);
		// $data['transaction'] = $this->url->link('account/transaction', '', true);
		// $data['download'] = $this->url->link('account/download', '', true);
		// $data['logout'] = $this->url->link('account/logout', '', true);
		// $data['shopping_cart'] = $this->url->link('checkout/cart');
		// $data['checkout'] = $this->url->link('checkout/checkout', '', true);
		// $data['contact'] = $this->url->link('information/contact');
		// $data['register'] = $this->url->link('account/register');
		// $data['telephone'] = $this->config->get('config_telephone');

		
		$req_uri = end(explode('/',$_SERVER[REQUEST_URI]));
			$req_uri = strtok($req_uri, '?');
			$req_uri = strtok($req_uri, '&');
// print_r($req_uri);
$user_agent = $_SERVER['HTTP_USER_AGENT'];

$is_mobile = preg_match('/Mobile|Android|iPhone|iPad/i', $user_agent);

if ($is_mobile) {
    $data['preload_hero'] = 'image/catalog/monsoon-offer/summer sale banner 2 (1).jpg';
} else {
    $data['preload_hero'] = 'image/catalog/monsoon-offer/summer sale banner 1 uw.jpg';
}
		$data['canonical'] = '';
		if($data['title']!='The page you requested cannot be found!'){
			$data['canonical'] = $server.strtolower($req_uri);
		}

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		// $data['cart'] = $this->load->controller('common/cart');
		
		$this->load->model('extension/total/coupon');

		$coupon_info = $this->model_extension_total_coupon->getAllCoupon();
			
		$data['coupon_code'] = $coupon_info[0]['code'];
		// print_r($coupon_info);
		// print_r($data);

		return $this->load->view('common/header', $data);
	}
}
