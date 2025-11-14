<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}
      	if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$userAgent = $_SERVER['HTTP_USER_AGENT']; 
		$is_mobile = false; // default

		$is_tablet = preg_match('/iPad|Tablet|Nexus 7|Nexus 10/i', $userAgent);

		if (!$is_tablet && preg_match('/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Opera Mini/i', $userAgent)) {
    		$is_mobile = true;
		}

		$data['is_mobile'] = $is_mobile; // pass to Twig
		$this->response->setOutput($this->load->view('common/home', $data));

	}

	public function social(){
				echo '<div class="container container-custom"><h2 class="my-lg-3 pg-heading text-center">Follow us on Instagram</h2></div><div class="row row-1" style="margin:0"><div class=column-2 style="padding:0"><div class=img-box><a href=https://www.instagram.com/p/DIlHqbhvQhv id=zoomIn target=_blank><img alt="urbanwood trendy shoe rack"src=catalog/view/javascript/assets/image/instapost/instapost1.webp></a></div><div class=img-box><a href=https://www.instagram.com/p/DIx0nq0vp0m id=zoomIn target=_blank><img alt="urbanwood sofa sets"src=catalog/view/javascript/assets/image/instapost/instapost2.webp></a></div><div class=img-box><a href=https://www.instagram.com/p/DH5XuASvBkM id=zoomIn target=_blank><img alt="urbanwood wardrobes"src=catalog/view/javascript/assets/image/instapost/instapost3.webp></a></div><div class=img-box><a href=https://www.instagram.com/p/DFZkcyAPmMM id=zoomIn target=_blank><img alt="urbanwood shoe racks"src=catalog/view/javascript/assets/image/instapost/instapost4.webp></a></div><div class=img-box><a href=https://www.instagram.com/p/DFe3bAtvKqM id=zoomIn target=_blank><img alt="urbanwood sofa cum beds"src=catalog/view/javascript/assets/image/instapost/instapost5.webp></a></div><div class=img-box><a href=https://www.instagram.com/p/DGX5vA4P0Yx id=zoomIn target=_blank><img alt="urbanwood platform beds"src=catalog/view/javascript/assets/image/instapost/instapost6.webp></a></div><div class=img-box><a href=https://www.instagram.com/p/DHnedOmpN8I id=zoomIn target=_blank><img alt="urbanwood beds"src=catalog/view/javascript/assets/image/instapost/instapost7.webp></a></div></div></div>';
	}
}

