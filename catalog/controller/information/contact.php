<?php
class ControllerInformationContact extends Controller {
	public function index() {
		$this->load->language('information/contact');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->setDescription("Get in touch with us! Reach out for inquiries, support, or collaborations. We're here to help, contact us today via phone, email, or our online form - Urbanwood");

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/contact')
		);

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');

		$this->response->setOutput($this->load->view('information/contact', $data));

	}
}

