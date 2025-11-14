<?php

class ControllerCommonMenu extends Controller {

	public function index() {

		$data['logged'] = $this->customer->isLogged();

		
		$data['trackorder'] = $this->url->link('account/trackorder', '', true);


		return $this->load->view('common/menu', $data);

	}

}

