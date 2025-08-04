<?php

class ControllerCommonFranchise extends Controller {

	public function index() {

		// $this->document->setTitle($this->config->get('config_meta_title'));

		// $this->document->setDescription($this->config->get('config_meta_description'));

		// $this->document->setKeywords($this->config->get('config_meta_keyword'));

		// if (isset($this->request->get['route'])) {

		// 	$this->document->addLink($this->config->get('config_url'), 'canonical');

		// }

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['menu'] = $this->load->controller('common/menu');
		// $data['register'] = $this->url->link('account/register');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->load->model('tool/image');

		$this->response->setOutput($this->load->view('common/franchise', $data));

	}
	

}

