<?php
class ControllerCheckoutPaymentAddressCustom extends Controller {
    public function index() {
        // Redirect if cart is empty
        if (!$this->cart->hasProducts()) {
            $this->response->redirect($this->url->link('checkout/cart_custom'));
        }
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
    if ($this->customer->isLogged()) {
        if ($this->request->post['address_option'] == 'new') {
            $customer_id = $this->customer->getId();
            // Save new address
            $this->load->model('account/address');
            $this->session->data['payment_address'] = $this->request->post;
            $this->session->data['payment_address']['address_id'] = $this->model_account_address->addAddress($customer_id,$this->request->post);
        } else {
            // Use existing address
            $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->post['address_id']);
        }
    } else {
        // Guest user
        $this->session->data['payment_address'] = $this->request->post;
    }

    $this->response->redirect($this->url->link('checkout/shipping_method_custom'));
}

        // Redirect if not logged in and not guest
        if (!$this->customer->isLogged() && !isset($this->session->data['guest'])) {
            $this->response->redirect($this->url->link('checkout/auth_custom'));
        }

        $this->load->language('checkout/checkout');
        $this->load->model('account/address');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            // Save address in session
            $this->session->data['payment_address'] = $this->request->post;

            $this->response->redirect($this->url->link('checkout/shipping_method_custom'));
        }

        $data['action'] = $this->url->link('checkout/payment_address_custom', '', true);
        $data['addresses'] = [];

        if ($this->customer->isLogged()) {
            $data['addresses'] = $this->model_account_address->getAddresses();
        }

        $data['logged'] = $this->customer->isLogged();

        $this->response->setOutput($this->load->view('checkout/payment_address_custom', $data));
    }
    
}
