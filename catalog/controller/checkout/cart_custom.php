<?php
class ControllerCheckoutCartCustom extends Controller {
    public function index() {
        $this->load->language('checkout/cart');

        // $this->load->model('checkout/cart');
        $this->load->model('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            $data['products'][] = array(
                'key'      => $product['key'],
                'name'     => $product['name'],
                'model'    => $product['model'],
                'quantity' => $product['quantity'],
                'price'    => $this->currency->format($product['price'], $this->session->data['currency']),
                'total'    => $this->currency->format($product['total'], $this->session->data['currency']),
                'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id'])
            );
        }

        $data['checkout'] = $this->url->link('checkout/address', '', true); // link to next page

        $data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['menu'] = $this->load->controller('common/menu');

        $this->response->setOutput($this->load->view('checkout/cart_custom', $data));
    }
}
