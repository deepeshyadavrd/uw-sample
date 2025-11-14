<?php

class ControllerCommonFooter extends Controller {

	public function index() {

		$this->load->language('common/footer');

		$data['customerstories'] = $this->load->controller('home/customerstories');
		if (isset($this->request->get['route'])){
			$data['homepage'] = 'Yes';
		}
		if($this->request->get['route'] == "custom/customfurniture"){
			$data['custom_page'] = 'Yes';
		}
		return $this->load->view('common/footer', $data);

	}

}

