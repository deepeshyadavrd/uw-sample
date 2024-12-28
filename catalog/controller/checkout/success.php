<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		$this->load->language('checkout/success');
		// print_r($this->session->data);
		$this->load->model('checkout/order');
		$orderdetails = $this->model_checkout_order->getOrderProducts($this->session->data['order_id']);
		
			print_r($orderdetails);
		if (isset($this->session->data['order_id'])) {
			// echo "UPDATE oc_camp_track SET price ='". $this->session->data['totals'] ."' where session_id = '" . $this->request->cookie['OCSESSID'] . "'";
			$this->cart->clear();
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}
		session_start();
		$txnResponse = array (
			'order_id' => $_SESSION['orderid'],
			'tracking_id' => $_SESSION["tracking_id"],
			'bank_ref_no' => $_SESSION["bank_ref_no"],
			'order_status' => $_SESSION["order_status"],
			'failure_message' => $_SESSION["failure_message"],
			'payment_mode' => $_SESSION["payment_mode"],
			'card_name' => $_SESSION["card_name"],
			'status_code' => $_SESSION["status_code"],
			'status_message' => $_SESSION["status_message"],
			'currency' => $_SESSION["currency"],
			'amount' => $_SESSION["amount"]
		);		
		
		if($_SESSION['order_id'] && $_SESSION["tracking_id"]){
			 $this->load->model('checkout/order');
			 $this->model_checkout_order->addTxnResponse($txnResponse);
		}
		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$data['orderdetails'] = $orderdetails;
		
		$this->response->setOutput($this->load->view('common/success', $data));
	}
}