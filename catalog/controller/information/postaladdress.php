<?php
class ControllerInformationPostaladdress extends Controller {
	public function index() {
		$this->load->language('information/contact');
		$this->document->setTitle('Postal Address : Urbanwood');

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')

		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/postaladdress')
		);

		$data['locations'] = array();
		$this->load->model('localisation/location');

		foreach((array)$this->config->get('config_location') as $location_id) {
			$location_info = $this->model_localisation_location->getLocation($location_id);

			if ($location_info) {
				$data['locations'][] = array(
					'name'        => $location_info['name'],
					'address'     => nl2br($location_info['address'])
				);
			}
		}

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$this->response->setOutput($this->load->view('information/postaladdress', $data));
	}
}