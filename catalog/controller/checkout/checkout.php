<?php
class ControllerCheckoutCheckout extends Controller {
	public function index() {
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$this->response->redirect($this->url->link('checkout/cart'));
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();
		$data['cart_total_items'] = $this->cart->countProducts();
		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$this->response->redirect($this->url->link('checkout/cart'));
			}
		}
		
		$data['products'] = array();
		$this->load->model('tool/image');
			$this->load->model('tool/upload');
			$total_mrp = 0;
			$discount_amt = 0;
		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
			}

			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));
			} else {
				$image = '';
			}

			$option_data = array();

			foreach ($product['option'] as $option) {
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

			// Display prices
			$to_inc_price2 = $product['price']*(20/100);
				$inc_price2 = $product['price']+$to_inc_price2;
				if($this->session->data['coupon'] == NULL){
				$to_inc_price = $product['price']*(20/100);
				$inc_price = $product['price']+$to_inc_price;
				}else{
					$inc_price = $product['price'];
				}
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$unit_price = $this->tax->calculate($inc_price, $product['tax_class_id'], $this->config->get('config_tax'));
				
				$price = $this->currency->format($unit_price, $this->session->data['currency']);
				$total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
			} else {
				$price = false;
				$total = false;
			}
			$total_mrp+=$product['mrp']*$product['quantity'];
			$recurring = '';

			if ($product['recurring']) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year')
				);

				if ($product['recurring']['trial']) {
					$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
				}

				if ($product['recurring']['duration']) {
					$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
				} else {
					$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
				}
			}
			// $data['total_save'] =  $product['ourPrice'] - $product['price'];
			$discount_amt += ($product['mrp'] * $product['quantity'] )- ($inc_price2 * $product['quantity']);
			$percentage_off = (($product['ourPrice'] - $product['price']) / $product['ourPrice'])* 100;
			//echo $percentage_off;
			$data['products'][] = array(
				'cart_id'   => $product['cart_id'],
				'thumb'     => $image,
				'name'      => $product['name'],
				'model'     => $product['model'],
				'option'    => $option_data,
				'recurring' => $recurring,
				'quantity'  => $product['quantity'],
				'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
				'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
				'price'     => $price,
				'total'     => $total,
				'discount'	=> $percentage_off,
				'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
			);
		}
		$data['discount_amt'] = $this->currency->format($discount_amt,$this->session->data['currency']);
		//print_r($data['products']);
		$this->load->language('checkout/checkout');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		// Required by klarna
		if ($this->config->get('payment_klarna_account') || $this->config->get('payment_klarna_invoice')) {
			$this->document->addScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_cart'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['text_checkout_option'] = sprintf($this->language->get('text_checkout_option'), 1);
		$data['text_checkout_account'] = sprintf($this->language->get('text_checkout_account'), 2);
		$data['text_checkout_payment_address'] = sprintf($this->language->get('text_checkout_payment_address'), 2);
		$data['text_checkout_shipping_address'] = sprintf($this->language->get('text_checkout_shipping_address'), 3);
		$data['text_checkout_shipping_method'] = sprintf($this->language->get('text_checkout_shipping_method'), 4);
		
		if ($this->cart->hasShipping()) {
			$data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 5);
			$data['text_checkout_confirm'] = sprintf($this->language->get('text_checkout_confirm'), 6);
		} else {
			$data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 3);
			$data['text_checkout_confirm'] = sprintf($this->language->get('text_checkout_confirm'), 4);	
		}

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}
			// Totals
			$this->load->model('setting/extension');
				
			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;
				
			// Because __call can not keep var references so we put them into an array. 			
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);
			
			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$sort_order = array();
			
				$results = $this->model_setting_extension->getExtensions('total');
			
				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
				}
			
				array_multisort($sort_order, SORT_ASC, $results);
			
				foreach ($results as $result) {
					if ($this->config->get('total_' . $result['code'] . '_status')) {
						$this->load->model('extension/total/' . $result['code']);
						
						// We have to put the totals in an array so that they pass by reference.
						$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
					}
				}
			
				$sort_order = array();
			
				foreach ($totals as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
			
				array_multisort($sort_order, SORT_ASC, $totals);
			}
			
			$data['totals'] = array();
			$total_price = 0;
			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
				);
				$total_price = $total['value'];
			}
			
		$data['logged'] = $this->customer->isLogged();

		if (isset($this->session->data['account'])) {
			$data['account'] = $this->session->data['account'];
		} else {
			$data['account'] = '';
		}

		$data['shipping_required'] = $this->cart->hasShipping();

		$data['total_mrp'] = $this->currency->format($total_mrp,$this->session->data['currency']);//$total_mrp;
		$total_save = $total_mrp - $total_price;
		$data['total_save'] = $this->currency->format($total_save,$this->session->data['currency']);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('checkout/checkout', $data));
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function customfield() {
		$json = array();

		$this->load->model('account/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}