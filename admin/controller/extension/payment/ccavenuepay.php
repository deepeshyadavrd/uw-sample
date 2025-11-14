<?php
/*Payment Name    : CCAvenue MCPG
Description	  : Payment with CCAvenue MCPG.
Module Version    : 3.0.2
Author		  : CCAvenue
*/
if (defined('DOM_CC_PATH_PG_MAIN_201') == false)
{
	define("DOM_CC_PATH_PG_MAIN_201", dirname(DIR_APPLICATION) . "/admin/controller/extension/payment/");
}
$file_CC = DOM_CC_PATH_PG_MAIN_201 . "ccavenue_main.php";
if (file_exists($file_CC))
{
	include_once ($file_CC);

}
class ControllerExtensionPaymentCcavenuepay extends Controller{
	private $error = array();
	private $_errors = array();
	public function getErrors(){
		return $this->_errors;
	}
	public function index(){
		$ccavenue = new Ccavenue_main();
		$this->document->addStyle('view/stylesheet/style.css');
		$this->document->addStyle('view/stylesheet/bright.css');
		$this->load->language('extension/payment/ccavenuepay');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		$sucesstxt = $this->language->get('text_success');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate()))
		{
			$this->model_setting_setting->editSetting('payment_ccavenuepay', $this->request->post);
			$this->session->data['success'] = $sucesstxt;
			$errortxt = '';
			$success = '';
			$settings = $this->model_setting_setting->getSetting('payment_ccavenuepay');
			$this->model_setting_setting->editSetting('payment_ccavenuepay', $settings);
			if (isset($_POST['ajax']) && $_POST['ajax'] == 'true')
			{
				echo json_encode(array(
					'error' => $errortxt,
					'success' => $success
				));
				exit;
			}
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . "&type=payment", 'SSL'));
		}
		if (empty($errortxt))
		{
			$this->session->data['success'] = 'Cancel: No any changes in configration.';
		}
		else
		{
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', 'SSL'));
		}
		$data['entry_payment_test_mode'] = $this->language->get('entry_payment_test_mode');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_test'] = $this->language->get('text_test');
		$data['entry_payment_currency_option'] = $this->language->get('entry_payment_currency_option');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		if (isset($this->error['warning']))
		{
			$data['error_warning'] = $this->error['warning'];
		}
		else
		{
			$data['error_warning'] = '';
		}
		if (isset($this->error['merchant_id']))
		{
			$data['error_merchant_id'] = $this->error['merchant_id'];
		}
		else
		{
			$data['error_merchant_id'] = '';
		}
		if (isset($this->error['access_code']))
		{
			$data['error_access_code'] = $this->error['access_code'];
		}
		else
		{
			$data['error_access_code'] = '';
		}
		if (isset($this->error['working_key']))
		{
			$data['error_working_key'] = $this->error['working_key'];
		}
		else
		{
			$data['error_working_key'] = '';
		}
		if (isset($this->error['currency_option']))
		{
			$data['error_currency_option'] = $this->error['currency_option'];
		}
		else
		{
			$data['error_currency_option'] = '';
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home') ,
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL') ,
			'separator' => false
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension') ,
			'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', 'SSL') ,
			'separator' => ' :: '
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title') ,
			'href' => $this->url->link('extension/payment/ccavenuepay', 'user_token=' . $this->session->data['user_token'], 'SSL') ,
			'separator' => ' :: '
		);
		$data['action'] = $this->url->link('extension/payment/ccavenuepay', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', 'SSL');
		if (isset($this->request->post['payment_ccavenuepay_status']))
		{
			$data['payment_ccavenuepay_status'] = $this->request->post['payment_ccavenuepay_status'];
		}
		else
		{
			$data['payment_ccavenuepay_status'] = $this->config->get('payment_ccavenuepay_status');
		}
		if (isset($this->request->post['payment_ccavenuepay_merchant_id']))
		{
			$data['payment_ccavenuepay_merchant_id'] = $this->request->post['payment_ccavenuepay_merchant_id'];
		}
		else
		{
			$data['payment_ccavenuepay_merchant_id'] = $this->config->get('payment_ccavenuepay_merchant_id');
		}
		if (isset($this->request->post['payment_ccavenuepay_access_code']))
		{
			$data['payment_ccavenuepay_access_code'] = $this->request->post['payment_ccavenuepay_access_code'];
		}
		else
		{
			$data['payment_ccavenuepay_access_code'] = $this->config->get('payment_ccavenuepay_access_code');
		}
		if (isset($this->request->post['payment_ccavenuepay_working_key']))
		{
			$data['payment_ccavenuepay_working_key'] = $this->request->post['payment_ccavenuepay_working_key'];
		}
		else
		{
			$data['payment_ccavenuepay_working_key'] = $this->config->get('payment_ccavenuepay_working_key');
		}
		if (isset($this->request->post['payment_ccavenuepay_payment_confirmation_mail']))
		{
			$data['payment_ccavenuepay_payment_confirmation_mail'] = $this->request->post['payment_ccavenuepay_payment_confirmation_mail'];
		}
		else
		{
			$data['payment_ccavenuepay_payment_confirmation_mail'] = $this->config->get('payment_ccavenuepay_payment_confirmation_mail');
		}
		if (isset($this->request->post['payment_ccavenuepay_test_mode']))
		{
			$data['payment_ccavenuepay_test_mode'] = $this->request->post['payment_ccavenuepay_test_mode'];
		}
		else
		{
			$data['payment_ccavenuepay_test_mode'] = $this->config->get('payment_ccavenuepay_test_mode');
		}
		if (isset($this->request->post['payment_ccavenuepay_currency_option']))
		{
			$data['payment_ccavenuepay_currency_option'] = $this->request->post['payment_ccavenuepay_currency_option'];
		}
		else
		{
			$data['payment_ccavenuepay_currency_option'] = $this->config->get('payment_ccavenuepay_currency_option');
		}
		$this->load->model('localisation/currency');
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
		$data['allowcurrencies'] = $ccavenue->AllowedCurrency();
		if (isset($this->request->post['payment_ccavenuepay_total']))
		{
			$data['payment_ccavenuepay_total'] = $this->request->post['payment_ccavenuepay_total'];
		}
		else
		{
			$data['payment_ccavenuepay_total'] = $this->config->get('payment_ccavenuepay_total');
		}
		if (isset($this->request->post['payment_ccavenuepay_completed_status_id']))
		{
			$data['payment_ccavenuepay_completed_status_id'] = $this->request->post['payment_ccavenuepay_completed_status_id'];
		}
		else
		{
			$data['payment_ccavenuepay_completed_status_id'] = $this->config->get('payment_ccavenuepay_completed_status_id');
		}
		if (isset($this->request->post['payment_ccavenuepay_failed_status_id']))
		{
			$data['payment_ccavenuepay_failed_status_id'] = $this->request->post['payment_ccavenuepay_failed_status_id'];
		}
		else
		{
			$data['payment_ccavenuepay_failed_status_id'] = $this->config->get('payment_ccavenuepay_failed_status_id');
		}
		if (isset($this->request->post['payment_ccavenuepay_pending_status_id']))
		{
			$data['payment_ccavenuepay_pending_status_id'] = $this->request->post['payment_ccavenuepay_pending_status_id'];
		}
		else
		{
			$data['payment_ccavenuepay_pending_status_id'] = $this->config->get('payment_ccavenuepay_pending_status_id');
		}
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		if (isset($this->request->post['payment_ccavenuepay_geo_zone_id']))
		{
			$data['payment_ccavenuepay_geo_zone_id'] = $this->request->post['payment_ccavenuepay_geo_zone_id'];
		}
		else
		{
			$data['payment_ccavenuepay_geo_zone_id'] = $this->config->get('payment_ccavenuepay_geo_zone_id');
		}
		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		if (isset($this->request->post['payment_ccavenuepay_sort_order']))
		{
			$data['payment_ccavenuepay_sort_order'] = $this->request->post['payment_ccavenuepay_sort_order'];
		}
		else
		{
			$data['payment_ccavenuepay_sort_order'] = $this->config->get('payment_ccavenuepay_sort_order');
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['user_token'] = $this->session->data['user_token'];
		$this->response->setOutput($this->load->view('extension/payment/ccavenuepay', $data));
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'extension/payment/ccavenuepay'))
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->request->post['payment_ccavenuepay_merchant_id'])
		{
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}
		if (!$this->request->post['payment_ccavenuepay_working_key'])
		{
			$this->error['working_key'] = $this->language->get('error_working_key');
		}
		if (!$this->request->post['payment_ccavenuepay_access_code'])
		{
			$this->error['access_code'] = $this->language->get('error_access_code');
		}
		if (!isset($this->request->post['payment_ccavenuepay_currency_option']))
		{
			$this->error['currency_option'] = $this->language->get('error_currency_option');
		}
		if (!$this->error)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}