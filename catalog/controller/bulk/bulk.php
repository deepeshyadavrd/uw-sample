<?php
class ControllerBulkBulk extends Controller {
	public function index() {
        $data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');

        $this->response->setOutput($this->load->view('bulk/bulk', $data));
    }
}