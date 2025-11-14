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

class ControllerExtensionPaymentCcavenuepay extends Controller{
	public function index(){
		$this->language->load('extension/payment/ccavenuepay');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$ccavenue_main = new Ccavenue_main();
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		if ($order_info)
		{
			$currency = $ccavenue_main->getAllowedCurrency($order_info['currency_code']);
			if ($currency == false)
			{
				echo "Currency not allowed ";
				return false;
			}
			$language = $ccavenue_main->getAllowedLanguage($this->session->data['language']);
			$Amount = $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);
			$Merchant_Id = $this->config->get('payment_ccavenuepay_merchant_id');
			$access_code = $this->config->get('payment_ccavenuepay_access_code');
			$Order_Id = $this->session->data['order_id'];
			$Url = $this->getBaseUrl('extension/payment/ccavenuepay/callback');
			$workingKey = $this->config->get('payment_ccavenuepay_working_key');
			$Url = $this->getBaseUrl('extension/payment/ccavenuepay/callback');
			$this->load->model('localisation/zone');
			$billing_name = '';
			$billing_address = '';
			$billing_city = '';
			$billing_state = '';
			$billing_tel = '';
			$billing_zip = '';
			$billing_country = '';
			$billing_email = '';
			$delivery_name = '';
			$delivery_address = '';
			$delivery_city = '';
			$delivery_state = '';
			$delivery_tel = '';
			$delivery_zip = '';
			$delivery_country = '';
			$merchant_param1 = '';
			if ($order_info['payment_firstname'])
			{
				$customer_firstname = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
				$customer_lastname = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
				$billing_name = $customer_firstname . " " . $customer_lastname;
				$address1 = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
				$address2 = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
				$billing_address = $address1 . " " . $address2;
				$billing_city = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
				$billing_state = $order_info['payment_zone'];
				$billing_tel = html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');
				$billing_zip = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
				$billing_country_iso_code_3 = $order_info['payment_iso_code_3'];
				$billing_country_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "country where iso_code_3='" . $billing_country_iso_code_3 . "'");
				$billing_country = $billing_country_query->row['name'];
			}
			if ($order_info['shipping_firstname'])
			{
				$customer_firstname = html_entity_decode($order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8');
				$customer_lastname = html_entity_decode($order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');
				$delivery_name = $customer_firstname . " " . $customer_lastname;
				$address1 = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8');
				$address2 = html_entity_decode($order_info['shipping_address_2'], ENT_QUOTES, 'UTF-8');
				$delivery_address = $address1 . " " . $address2;
				$delivery_city = html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8');
				$delivery_state = $order_info['shipping_zone'];
				$delivery_tel = html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');
				$delivery_zip = html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');
				$delivery_country_iso_code_3 = $order_info['shipping_iso_code_3'];
				$delivery_country_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "country where iso_code_3='" . $billing_country_iso_code_3 . "'");
				$delivery_country = $delivery_country_query->row['name'];
			}
			$billing_email = $order_info['email'];
			$merchant_param1 = $this->session->data['comment'];
			$redirect_url = 'https://www.urbanwood.in?route=extension/payment/ccavenuepay/callback';//$this->getBaseUrl('extension/payment/ccavenuepay/callback');
			$cancel_url = 'https://www.urbanwood.in?route=extension/payment/ccavenuepay/callback';//$this->getBaseUrl('extension/payment/ccavenuepay/callback');
			$merchant_data_array = array();
			$merchant_data_array['merchant_id'] = $Merchant_Id;
			$merchant_data_array['order_id'] = $Order_Id;
			$merchant_data_array['currency'] = $order_info['currency_code'];
			$merchant_data_array['amount'] = $Amount;
			$merchant_data_array['redirect_url'] = $redirect_url;
			$merchant_data_array['cancel_url'] = $cancel_url;
			$merchant_data_array['language'] = $language;
			$merchant_data_array['billing_name'] = $billing_name;
			$merchant_data_array['billing_address'] = $billing_address;
			$merchant_data_array['billing_city'] = $billing_city;
			$merchant_data_array['billing_state'] = $billing_state;
			$merchant_data_array['billing_zip'] = $billing_zip;
			$merchant_data_array['billing_country'] = $billing_country;
			$merchant_data_array['billing_tel'] = $billing_tel;
			$merchant_data_array['billing_email'] = $billing_email;
			$merchant_data_array['delivery_name'] = $delivery_name;
			$merchant_data_array['delivery_address'] = $delivery_address;
			$merchant_data_array['delivery_city'] = $delivery_city;
			$merchant_data_array['delivery_state'] = $delivery_state;
			$merchant_data_array['delivery_zip'] = $delivery_zip;
			$merchant_data_array['delivery_country'] = $delivery_country;
			$merchant_data_array['delivery_tel'] = $delivery_tel;
			$merchant_data_array['merchant_param1'] = $merchant_param1;
			$merchant_data = implode("&", $merchant_data_array);
			$data['access_code'] = $access_code;
			$test_mode = $this->config->get('payment_ccavenuepay_test_mode');
			$data['action'] = $ccavenue_main->getPaymentGatewayUrl($test_mode);
			$passdata = array(
				"merchantdata" => $merchant_data_array,
				"encryptkey" => $workingKey,
				"data" => $data
			);
			$form_response = $ccavenue_main->getfrontform(json_encode($passdata));
			$data['form_response'] = $form_response;
			return $this->load->view('extension/payment/ccavenuepay', $data);
		}
	}
	
	public function callback(){
		$ccavenue_main = new Ccavenue_main();
		$workingKey = $this->config->get('payment_ccavenuepay_working_key');
		$encResponse = $_REQUEST["encResp"]; //This is the response sent by the CCAvenue Server
		$rcvdString = $ccavenue_main->decrypt($encResponse, $workingKey); //Crypto Decryption used as per the specified working key.
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
		if (isset($response_array['mer_amount'])) $amount = $response_array['mer_amount'];
		$this->load->language('extension/payment/ccavenuepay');
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($resOrderId);
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
			
			$payment_status = false;
			$order_status_id = 8;
			if ($order_status == "Success") {
				if ($resOrderId == $order_info['order_id'] && round($amount, 2) == round($order_info['total'], 2)) {
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
			$this->model_checkout_order->addOrderHistory($resOrderId, $order_status_id, $payment_status_message);

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