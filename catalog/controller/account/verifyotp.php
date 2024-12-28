<?php
class ControllerAccountVerifyotp extends Controller {
    public function index() {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', true));
		}

        $data['email'] = $this->session->data['customer_email'];

        $data['action'] = $this->url->link('account/reset', '', true);

        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/verifyotp', $data));
    }
}