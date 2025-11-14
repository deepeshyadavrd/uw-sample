<?php
/*Payment Name    : CCAvenue MCPG
Description	  : Payment with CCAvenue MCPG.
Module version    : 3.0.2
Author	          : CCAvenue
*/
if (defined('DOM_CC_PATH_PG_MAIN_201') == false)
{
	define("DOM_CC_PATH_PG_MAIN_201", dirname(DIR_APPLICATION) . "/admin/controller/extension/payment/");
}
$file = DOM_CC_PATH_PG_MAIN_201 . "ccavenue_main.php";
if (file_exists($file))
{
	include_once ($file);

}
class ControllerCheckoutCustomPayment extends Controller {
	public function index() {
		

		$data = array();
		if($_GET['ref']!=''){
			$encdst = base64_decode($_GET['ref']);

			$expld = explode('#', $encdst);

			foreach ($expld as $key => $value) {
				$exn = explode('=', $value);
				$data[$exn[0]] = $exn[1];
			}
		}
		// else{
		// 	$this->response->redirect(HTTPS_SERVER);
		// }
		 $this->session->data['order_id'] = $data['order_id'];
		//  print_r($data);exit;
		$data['header'] 		= $this->load->controller('common/header');
		$data['footer'] 		= $this->load->controller('common/footer');
		$data['surl'] 			= "https://www.urbanwood.in/?route=checkout/custompayment/callback";//$this->url->link('checkout/custompayment/callback', '', true);
		$data['curl'] 			= "https://www.urbanwood.in/?route=checkout/custompayment/callback";$this->url->link('checkout/custompayment/callback', '', true);

		
		$this->response->setOutput($this->load->view('checkout/custom_payment', $data));
	}
	public function callback(){
		$ccavenue_main = new Ccavenue_main();
		$workingKey = $this->config->get('payment_ccavenuepay_working_key');
		$encResponse = $_REQUEST["encResp"]; //This is the response sent by the CCAvenue Server
		$rcvdString = $ccavenue_main->decrypt($encResponse, $workingKey); //Crypto Decryption used as per the specified working key.
		// print_r($rcvdString);
		$decryptValues = explode('&', $rcvdString);
		$dataSize = sizeof($decryptValues);
		$response_array = array();
		for ($i = 0; $i < count($decryptValues); $i++)
		{
			$information = explode('=', $decryptValues[$i]);
			if (count($information) == 2)
			{
				$response_array[$information[0]] = urldecode($information[1]);
			}
		}
		$order_status = '';
		$order_id = '';
		$tracking_id = '';
		$bank_ref_no = '';
		$failure_message = '';
		$payment_mode = '';
		$card_name = '';
		$status_code = '';
		$status_message = '';
		$currency = '';
		$amount = '';
		$payment_status_message = '';
// 		$response_array = array(
// 				'order_id' => '111',
// 			    'tracking_id' => '109030280820',
// 			    'bank_ref_no' => '2137681840',
// 			    'order_status' => 'Success',
// 			    'failure_message' => '',
// 			    'payment_mode' => 'Net Banking',
// 			    'card_name' => 'ICICI Bank',
// 			    'status_code' => '',
// 			    'status_message' => 'SUCCESS',
// 			    'currency' => 'INR',
// 			    'amount' => 1.00,
// 			    'billing_name' => 'Girish Sem',
// 			    'billing_address' => 'Sec 6 Udaipur',
// 			    'billing_city' => 'udaipur',
// 			    'billing_state' => 'RA',
// 			    'billing_zip' => '313001',
// 			    'billing_country' => 'India',
// 			    'billing_tel' => '09511578329',
// 			    'billing_email' => 'girish.urbanwood@gmail.com',
// 			    'delivery_name' => 'Girish Sem',
// 			    'delivery_address' => 'Sec 6 Udaipur',
// 			    'delivery_city' => 'udaipur',
// 			    'delivery_state' => 'RA',
// 			    'delivery_zip' => '313001',
// 			    'delivery_country' => 'India',
// 			    'delivery_tel' => '09511578329',
// 			    'merchant_param1' => '',
// 			    'merchant_param2' => '',
// 			    'merchant_param3' => '',
// 			    'merchant_param4' => '',
// 			    'merchant_param5' => '',
// 			    'vault' => 'N',
// 			    'offer_type' => 'null',
// 			    'offer_code' => 'null',
// 			    'discount_value' => '0.0',
// 			    'mer_amount' => '1.00',
// 			    'eci_value' => 'null',
// 			    'retry' => 'N',
// 			    'response_code' => '0',
// 			    'billing_notes' => '',
// 			    'trans_date' => '05/12/2020 12:09:28',
// 			    'bin_country' => '',
// );

		if (isset($response_array['order_id'])) $resOrderId = $response_array['order_id'];
		if (isset($response_array['tracking_id'])) $tracking_id = $response_array['tracking_id'];
		if (isset($response_array['bank_ref_no'])) $bank_ref_no = $response_array['bank_ref_no'];
		if (isset($response_array['order_status'])) $order_status = $response_array['order_status'];
		if (isset($response_array['failure_message'])) $failure_message = $response_array['failure_message'];
		if (isset($response_array['payment_mode'])) $payment_mode = $response_array['payment_mode'];
		if (isset($response_array['card_name'])) $card_name = $response_array['card_name'];
		if (isset($response_array['status_code'])) $status_code = $response_array['status_code'];
		if (isset($response_array['status_message'])) $status_message = $response_array['status_message'];
		if (isset($response_array['currency'])) $currency = $response_array['currency'];
		if (isset($response_array['mer_amount'])) $amount = round($response_array['mer_amount'], 2);

		
		// echo '<pre>';print_r($response_array);exit;
		$this->load->language('extension/payment/ccavenuepay');
		$this->load->model('checkout/order');
		$order_id = $response_array['order_id'];
		$order_info = $this->model_checkout_order->getOrder($order_id);
		// print_r($order_info);exit;
		if ($order_info)
		{
			$this->language->load('extension/payment/ccavenuepay');
			$data = array(
				"order_id" => $resOrderId,
				"tracking_id" => $tracking_id,
				"bank_ref_no" => $bank_ref_no,
				"order_status" => $order_status,
				"failure_message" => $failure_message,
				"payment_mode" => $payment_mode,
				"card_name" => $card_name,
				"status_code" => $status_code,
				"status_message" => $status_message,
				"currency" => $currency,
				"amount" => $amount
			);
			// print_r($data);exit;
			//echo $this->config->get('payment_ccavenuepay_completed_status_id');exit;

			$payment_status = false;
			if ($order_status == "Success") {
				if ($resOrderId == $order_info['order_id'] ) {
					$this->session->data['order_id'] = $order_info['order_id'];
					$payment_status_message = $this->language->get('success_comment');
					$payment_status = true;
					$order_status_id = $this->config->get('payment_ccavenuepay_completed_status_id');
				} else {
					$payment_status_message = $this->language->get('tampared_data_comment');
					$order_status_id = 8;
				}
			} else if ($order_status == "Aborted") {
				$payment_status_message = $this->language->get('pending_comment');
				$order_status_id = $this->config->get('payment_ccavenuepay_pending_status_id');
			} else if ($order_status == "Failure") {
				$payment_status_message = $this->language->get('declined_comment');
				$order_status_id = $this->config->get('payment_ccavenuepay_failed_status_id');
			} else {
				$payment_status_message = $this->language->get('failed_comment');
				$order_status_id = $this->config->get('payment_ccavenuepay_failed_status_id');
			}
			$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $payment_status_message);

			/**************** Start pay failure logic ************/
			$data['title'] = sprintf($this->language->get('heading_title') , $this->config->get('config_name'));
			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on'))
			{
				$data['base'] = HTTP_SERVER;
			}
			else
			{
				$data['base'] = HTTPS_SERVER;
			}
			$data['language'] = $this->language->get('code');
			$data['direction'] = $this->language->get('direction');
			$data['heading_title'] = sprintf($this->language->get('heading_title') , $this->config->get('config_name'));
			$data['text_response'] = $this->language->get('text_response');
			$data['payment_status_message'] = $payment_status_message;
			session_start();

             $_SESSION['payment_status_message'] = $payment_status_message;
             $_SESSION['ourname'] = $this->config->get('config_name');
             $_SESSION['orderid'] = $resOrderId;
             $_SESSION['amount'] = $amount;
			 $_SESSION["tracking_id"] = $tracking_id;
			 $_SESSION["bank_ref_no"] = $bank_ref_no;
			 $_SESSION["order_status"] = $order_status;
			 $_SESSION["failure_message"] = $failure_message;
			 $_SESSION["payment_mode"] = $payment_mode;
			 $_SESSION["card_name"] = $card_name;
			 $_SESSION["status_code"] = $status_code;
			 $_SESSION["status_message"] = $status_message;
			 $_SESSION["currency"] = $currency;
			// echo '<pre>';print_r($this->session->data);exit;
			if ($payment_status)
			{
				$data['text_payment_wait'] = sprintf($this->language->get('text_payment_wait') , $this->getBaseUrl('checkout/success'));
				$data['continue'] = $this->getBaseUrl('checkout/success');
				$this->response->redirect($this->getBaseUrl('checkout/success'));
			}
			else
			{
				$data['text_payment_wait'] = sprintf($this->language->get('text_payment_wait') , $this->getBaseUrl('checkout/cart'));
				$data['continue'] = $this->getBaseUrl('checkout/cart');
			}
			$template = 'extension/payment/ccavenuepay_response ';
			$data['header'] = $this->load->controller('common/header');
			$data['column_content_bottom'] = $this->load->controller('common/content_bottom');
			$data['column_content_top'] = $this->load->controller('common/content_top');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view($template, $data));
		}else{
		$this->language->load('extension/payment/ccavenuepay');
			$data = array(
				"order_id" => $resOrderId,
				"tracking_id" => $tracking_id,
				"bank_ref_no" => $bank_ref_no,
				"order_status" => $order_status,
				"failure_message" => $failure_message,
				"payment_mode" => $payment_mode,
				"card_name" => $card_name,
				"status_code" => $status_code,
				"status_message" => $status_message,
				"currency" => $currency,
				"amount" => $amount
			);
			// print_r($data);exit;
			//echo $this->config->get('payment_ccavenuepay_completed_status_id');exit;

			$payment_status = false;
			if ($order_status == "Success") {
				// if ($resOrderId == $order_info['order_id'] ) {
					$this->session->data['order_id'] = $resOrderId;//$order_info['order_id'];
					$payment_status_message = $this->language->get('success_comment');
					$payment_status = true;
					$order_status_id = $this->config->get('payment_ccavenuepay_completed_status_id');
				// } else {
				// 	$payment_status_message = $this->language->get('tampared_data_comment');
				// 	$order_status_id = 8;
				// }
			} else if ($order_status == "Aborted") {
				$payment_status_message = $this->language->get('pending_comment');
				$order_status_id = $this->config->get('payment_ccavenuepay_pending_status_id');
			} else if ($order_status == "Failure") {
				$payment_status_message = $this->language->get('declined_comment');
				$order_status_id = $this->config->get('payment_ccavenuepay_failed_status_id');
			} else {
				$payment_status_message = $this->language->get('failed_comment');
				$order_status_id = $this->config->get('payment_ccavenuepay_failed_status_id');
			}
			// $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $payment_status_message);

			/**************** Start pay failure logic ************/
			$data['title'] = sprintf($this->language->get('heading_title') , $this->config->get('config_name'));
			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on'))
			{
				$data['base'] = HTTP_SERVER;
			}
			else
			{
				$data['base'] = HTTPS_SERVER;
			}
			$data['language'] = $this->language->get('code');
			$data['direction'] = $this->language->get('direction');
			$data['heading_title'] = sprintf($this->language->get('heading_title') , $this->config->get('config_name'));
			$data['text_response'] = $this->language->get('text_response');
			$data['payment_status_message'] = $payment_status_message;
			session_start();

             $_SESSION['payment_status_message'] = $payment_status_message;
             $_SESSION['ourname'] = $this->config->get('config_name');
             $_SESSION['orderid'] = $resOrderId;
             $_SESSION['amount'] = $amount;
			 $_SESSION["tracking_id"] = $tracking_id;
			 $_SESSION["bank_ref_no"] = $bank_ref_no;
			 $_SESSION["order_status"] = $order_status;
			 $_SESSION["failure_message"] = $failure_message;
			 $_SESSION["payment_mode"] = $payment_mode;
			 $_SESSION["card_name"] = $card_name;
			 $_SESSION["status_code"] = $status_code;
			 $_SESSION["status_message"] = $status_message;
			 $_SESSION["currency"] = $currency;
			// echo '<pre>';print_r($this->session->data);exit;
			if ($payment_status)
			{
				$data['text_payment_wait'] = sprintf($this->language->get('text_payment_wait') , $this->getBaseUrl('checkout/success'));
				$data['continue'] = $this->getBaseUrl('checkout/success');
				$this->response->redirect($this->getBaseUrl('checkout/success'));
			}
			else
			{
				$data['text_payment_wait'] = sprintf($this->language->get('text_payment_wait') , $this->getBaseUrl('checkout/cart'));
				$data['continue'] = $this->getBaseUrl('checkout/cart');
				// $this->response->redirect($this->getBaseUrl('checkout/success'));
			}
			$template = 'extension/payment/ccavenuepay_response ';
			$data['header'] = $this->load->controller('common/header');
			$data['column_content_bottom'] = $this->load->controller('common/content_bottom');
			$data['column_content_top'] = $this->load->controller('common/content_top');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view($template, $data));
		}
	}
	private function getBaseUrl($path = '')
	{
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
		{
			$full_url = $this->url->link($path, '', true);
		}
		else
		{
			$full_url = $this->url->link($path);
		}
		return $full_url;
	}
	

}