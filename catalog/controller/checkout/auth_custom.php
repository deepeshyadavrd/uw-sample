<?php
class ControllerCheckoutAuthCustom extends Controller {
    public function index() {
        if ($this->customer->isLogged()) {
        $this->response->redirect($this->url->link('checkout/payment_address_custom'));
    }
        $this->load->language('checkout/checkout');
        $this->document->setTitle('Login or Continue as Guest');

        $data['action_login'] = $this->url->link('checkout/auth_custom/login', '', true);
        $data['action_register'] = $this->url->link('checkout/auth_custom/register', '', true);
        $data['action_guest'] = $this->url->link('checkout/payment_address_custom', '', true); // Step 3

        $data['login_error'] = '';

        if (isset($this->session->data['error'])) {
            $data['login_error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        }

        $this->response->setOutput($this->load->view('checkout/auth_custom', $data));
    }

    public function login() {
        $this->load->model('account/customer');

        $email = $this->request->post['email'];
        $password = $this->request->post['password'];

        if ($this->customer->login($email, $password)) {
            $this->response->redirect($this->url->link('checkout/payment_address_custom'));
        } else {
            $this->session->data['error'] = 'Invalid login.';
            $this->response->redirect($this->url->link('checkout/auth_custom'));
        }
    }

    public function register() {
        // Ideally, validate and create a customer account here.
        // For now, redirect to payment address page assuming success
        // You can copy full validation from catalog/controller/account/register.php
        $this->session->data['account'] = 'register';
        $this->response->redirect($this->url->link('checkout/payment_address_custom'));
    }
}
