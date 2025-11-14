<?php
class ControllerAccountForgotten extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', true));
		}

		$this->load->language('account/forgotten');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_account_customer->editCode($this->request->post['email'], token(40));
			$this->session->data['success'] = $this->language->get('text_success');
			$this->session->data['customer_email'] = $this->request->post['email'];
			$this->response->redirect("./");
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_forgotten'),
			'href' => $this->url->link('account/forgotten', '', true)
		);
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = "?route=account/forgotten";//$this->url->link('account/forgotten', '', true);
		$data['back'] = $this->url->link('account/login', '', true);
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');

		$this->response->setOutput($this->load->view('account/forgotten', $data));
	}

	protected function validate() {
		if (!isset($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		}
		
		// Check if customer has been approved.
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

		if ($customer_info && !$customer_info['status']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		return !$this->error;
	}
}
