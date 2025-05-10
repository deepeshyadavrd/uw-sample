<?php
class ControllerCheckoutCartCustom extends Controller {
    public function index() {
        $this->load->language('checkout/cart');

        // $this->load->model('checkout/cart');
        $this->load->model('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));
$this->load->model('tool/image');
			$this->load->model('tool/upload');
        $data['products'] = array();
        $products = $this->cart->getProducts();

        
        foreach ($products as $product) {
            if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));
			} else {
				$image = '';
			}
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
				
				$price = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
				$total = $this->currency->format($product['mrp'] * $product['quantity'], $this->session->data['currency']);
			} else {
				$price = false;
				$total = false;
			}
            $percentage_off = round((($product['mrp'] - $product['price']) / $product['mrp'])* 100);
            $data['products'][] = array(
                'cart_id'   => $product['cart_id'],
					'product_id'   => $product['product_id'],
					'thumb'     => $image,
					'name'      => $product['name'],
					'model'     => $product['model'],
					// 'option'    => $option_data,
					// 'recurring' => $recurring,
					'quantity'  => $product['quantity'],
					'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
					'price'     => $price,
					'total'     => $total,
					'discount'	=> $percentage_off,
					'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
            );
        }

        $data['checkout'] = $this->url->link('checkout/address', '', true); // link to next page

        $data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['menu'] = $this->load->controller('common/menu');

        $this->response->setOutput($this->load->view('checkout/cart_custom', $data));
    }
}
