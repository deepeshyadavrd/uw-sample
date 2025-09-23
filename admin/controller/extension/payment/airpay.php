<?php 
class ControllerExtensionPaymentairpay extends Controller {
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('extension/payment/airpay');


	//$this->data['heading_title'] = $this->language->get('heading_title');
	$this->document->setTitle($this->language->get('heading_title'));
	$this->load->model('setting/setting');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
	
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('airpay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
	// print_R($this->session->data['user_token']); exit;
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['merchantIdentifier'] = $this->language->get('merchantIdentifier');
		$data['username'] = $this->language->get('username');
		$data['password'] = $this->language->get('password');
		$data['secret_key'] = $this->language->get('secret_key');
		$data['mode'] = $this->language->get('mode');
		$data['payment_mode'] = $this->language->get('payment_mode');
		$data['log'] = $this->language->get('log');

		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['text_all_zones'] = $this->language->get('text_all_zones');


        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
		if (isset($this->request->post['merchantIdentifier'])) {
			$data['airpay_merchantIdentifier'] = $this->request->post['airpay_merchantIdentifier'];
		} else {
			$data['airpay_merchantIdentifier'] = $this->config->get('airpay_merchantIdentifier');
		}
		if (isset($this->request->post['username'])) {
			$data['airpay_username'] = $this->request->post['airpay_username'];
		} else {
			$data['airpay_username'] = $this->config->get('airpay_username');
		}
		if (isset($this->request->post['password'])) {
			$data['airpay_password'] = $this->request->post['airpay_password'];
		} else {
			$data['airpay_password'] = $this->config->get('airpay_password');
		}
		if (isset($this->request->post['airpay_secret_key'])) {
			$data['airpay_secret_key'] = $this->request->post['airpay_secret_key'];
		} else {
			$data['airpay_secret_key'] = $this->config->get('airpay_secret_key');
		}
		
		if (isset($this->request->post['airpay_test'])) {
			$data['airpay_test'] = $this->request->post['airpay_test'];
		} else {
			$data['airpay_test'] = $this->config->get('airpay_test');
		}
		if (isset($this->request->post['airpay_log'])) {
			$data['airpay_log'] = $this->request->post['airpay_log'];
		} else {
			$data['airpay_log'] = $this->config->get('airpay_log');
		}
		if (isset($this->request->post['chmod'])) {
			
			$data['chmod'] = $this->request->post['chmod'];
		} else {
			$data['chmod'] = $this->config->get('chmod');
		}
		if (isset($this->request->post['airpay_status'])) {
			$data['airpay_status'] = $this->request->post['airpay_status'];
		} else {
			$data['airpay_status'] = $this->config->get('airpay_status');
		}
// newly added code for zone status for guest checkout
		if (isset($this->request->post['airpay_geo_zone_id'])) {
			$data['airpay_geo_zone_id'] = $this->request->post['airpay_geo_zone_id'];
		} else {
			$data['airpay_geo_zone_id'] = $this->config->get('airpay_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
//end

		
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

	//	$this->data['error_warning'] = @$this->error['warning'];
        if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

   		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => 'https://payments.airpay.co.in/pay/index.php'
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/airpay', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/airpay', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		
	
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/airpay', $data));
	}
	
	private function validate() {
		// if (!$this->user->hasPermission('modify', 'payment/airpay')){
		// 	$this->error['warning'] = $this->language->get('error_permission');
		// }
		// if (!$this->error) {
		// 	return TRUE;
		// } else {
		// 	return FALSE;
		// }	
		return true;
	}
}
?>
