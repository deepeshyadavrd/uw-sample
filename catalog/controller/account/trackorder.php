<?php
class ControllerAccountTrackorder extends Controller {
	public function index() {
        $this->load->language('account/order');
		$this->document->setTitle('Track Order | Urbanwood');
		$this->document->setDescription('Track your order easily with our real-time tracking system. Enter your order details to check the status, expected delivery date, and updates.');
		if (isset($this->request->post['order_id'])) {
			$order_id = $this->request->post['order_id'];
			
		    $this->load->model('account/order');

		    $order_info = $this->model_account_order->getOrder($order_id);
            // print_r($order_info);

		    if ($order_info) {
			    $this->document->setTitle($this->language->get('text_order'));

			    $url = '';

			    if (isset($this->request->get['page'])) {
			    	$url .= '&page=' . $this->request->get['page'];
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
			    	'text' => $this->language->get('heading_title'),
			    	'href' => $this->url->link('account/order', $url, true)
			    );

			    $data['breadcrumbs'][] = array(
			    	'text' => $this->language->get('text_order'),
			    	'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->post['order_id'] . $url, true)
			    );

			    if (isset($this->session->data['error'])) {
			    	$data['error_warning'] = $this->session->data['error'];

			    	unset($this->session->data['error']);
			    } else {
			    	$data['error_warning'] = '';
			    }

			    if (isset($this->session->data['success'])) {
			    	$data['success'] = $this->session->data['success'];

			    	unset($this->session->data['success']);
			    } else {
			    	$data['success'] = '';
			    }

			    if ($order_info['invoice_no']) {
			    	$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			    } else {
			    	$data['invoice_no'] = '';
			    }

			    $data['order_id'] = $this->request->post['order_id'];
			    $data['date_added'] = $order_info['date_added'];//date('m/d/Y', strtotime($order_info['date_added']));

			    if ($order_info['payment_address_format']) {
			    	$format = $order_info['payment_address_format'];
			    } else {
			    	$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			    }

			    $find = array(
			    	'{firstname}',
			    	'{lastname}',
			    	'{company}',
			    	'{address_1}',
			    	'{address_2}',
			    	'{city}',
			    	'{postcode}',
			    	'{zone}',
			    	'{zone_code}',
			    	'{country}'
			    );

			    $replace = array(
			    	'firstname' => $order_info['payment_firstname'],
			    	'lastname'  => $order_info['payment_lastname'],
			    	'company'   => $order_info['payment_company'],
			    	'address_1' => $order_info['payment_address_1'],
			    	'address_2' => $order_info['payment_address_2'],
			    	'city'      => $order_info['payment_city'],
			    	'postcode'  => $order_info['payment_postcode'],
			    	'zone'      => $order_info['payment_zone'],
			    	'zone_code' => $order_info['payment_zone_code'],
			    	'country'   => $order_info['payment_country']
			    );

			    $data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			    $data['payment_method'] = $order_info['payment_method'];

			    if ($order_info['shipping_address_format']) {
			    	$format = $order_info['shipping_address_format'];
			    } else {
			    	$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			    }

			    $find = array(
			    	'{firstname}',
			    	'{lastname}',
			    	'{company}',
			    	'{address_1}',
			    	'{address_2}',
			    	'{city}',
			    	'{postcode}',
			    	'{zone}',
			    	'{zone_code}',
			    	'{country}'
			    );

			    $replace = array(
			    	'firstname' => $order_info['shipping_firstname'],
			    	'lastname'  => $order_info['shipping_lastname'],
			    	'company'   => $order_info['shipping_company'],
			    	'address_1' => $order_info['shipping_address_1'],
			    	'address_2' => $order_info['shipping_address_2'],
			    	'city'      => $order_info['shipping_city'],
			    	'postcode'  => $order_info['shipping_postcode'],
			    	'zone'      => $order_info['shipping_zone'],
			    	'zone_code' => $order_info['shipping_zone_code'],
			    	'country'   => $order_info['shipping_country']
			    );
				$data['name'] = 	 $order_info['payment_firstname'] . " " . $order_info['payment_lastname'];
				$data['address_1'] = $order_info['payment_address_1'];
				$data['address_2'] = $order_info['payment_address_2'];
				$data['city'] = 	 $order_info['payment_city'];
				$data['postcode'] =  $order_info['payment_postcode'];
				$data['zone'] = 	 $order_info['payment_zone'];
				$data['zone_code'] = $order_info['payment_zone_code'];
				$data['country'] =   $order_info['payment_country'];


			    $data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			    $data['shipping_method'] = $order_info['shipping_method'];

			    $this->load->model('catalog/product');
			    $this->load->model('tool/upload');

			    // Products
			    $data['products'] = array();

			    $products = $this->model_account_order->getOrderProducts($this->request->post['order_id']);

			    foreach ($products as $product) {
			    	$option_data = array();

			    	$options = $this->model_account_order->getOrderOptions($this->request->post['order_id'], $product['order_product_id']);

			    	foreach ($options as $option) {
			    		if ($option['type'] != 'file') {
			    			$value = $option['value'];
			    		} else {
			    			$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

			    			if ($upload_info) {
			    				$value = $upload_info['name'];
			    			} else {
			    				$value = '';
			    			}
			    		}

			    		$option_data[] = array(
			    			'name'  => $option['name'],
			    			'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
			    		);
			    	}

			    	$product_info = $this->model_catalog_product->getProduct($product['product_id']);
                
			    	if ($product_info) {
			    		$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], true);
			    	} else {
			    		$reorder = '';
			    	}

			    	$data['products'][] = array(
			    		'name'     => $product['name'],
			    		'model'    => $product['model'],
			    		'option'   => $option_data,
			    		'quantity' => $product['quantity'],
			    		'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
			    		'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
			    		'reorder'  => $reorder,
			    		'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], true)
			    	);
			    }

			    // Voucher
			    $data['vouchers'] = array();

			    $vouchers = $this->model_account_order->getOrderVouchers($this->request->post['order_id']);

			    foreach ($vouchers as $voucher) {
			    	$data['vouchers'][] = array(
			    		'description' => $voucher['description'],
			    		'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
			    	);
			    }

			    // Totals
			    $data['totals'] = array();

			    $totals = $this->model_account_order->getOrderTotals($this->request->post['order_id']);

			    foreach ($totals as $total) {
			    	$data['totals'][] = array(
			    		'title' => $total['title'],
			    		'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
			    	);
			    }

			    $data['comment'] = nl2br($order_info['comment']);

			    // History
			    $data['histories'] = array();

			    $results = $this->model_account_order->getOrderHistories($this->request->post['order_id']);

			    foreach ($results as $result) {
			    	$data['histories'][] = array(
			    		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
			    		'status'     => $result['status'],
			    		'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
			    	);
			    }
                // print_r($data['histories']);
                $data['action'] = $this->url->link('account/trackorder');
			    $data['continue'] = $this->url->link('account/order', '', true);

			    
			    $data['footer'] = $this->load->controller('common/footer');
			    $data['header'] = $this->load->controller('common/header');
			    $data['menu'] = $this->load->controller('common/menu');

			    $this->response->setOutput($this->load->view('account/trackorder', $data));
            }else{
                $data['error'] = 'Provide valid order id';
                $data['action'] = $this->url->link('account/trackorder');
			    $data['footer'] = $this->load->controller('common/footer');
			    $data['header'] = $this->load->controller('common/header');
			    $data['menu'] = $this->load->controller('common/menu');
                $this->response->setOutput($this->load->view('account/trackorder', $data));
            }
        } else {
            $data['action'] = $this->url->link('account/trackorder');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['menu'] = $this->load->controller('common/menu');
            $this->response->setOutput($this->load->view('account/trackorder', $data));
		}
	}
}