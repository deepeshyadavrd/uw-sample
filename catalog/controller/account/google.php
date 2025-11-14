<?php
class ControllerAccountGoogle extends Controller {
    private $client_id = "700955355223-fhdsm58a44baincfsccutn19rrqvdbd5.apps.googleusercontent.com";
    private $client_secret = "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";
    private $redirect_uri = "https://www.urbanwood.in/?route=account/google/callback";

    public function login() {
        $url = "https://accounts.google.com/o/oauth2/auth?response_type=code";
        $url .= "&client_id=" . $this->client_id;
        $url .= "&redirect_uri=" . urlencode($this->redirect_uri);
        $url .= "&scope=email%20profile";

        $this->response->redirect($url);
    }

    public function callback() {
        if (!isset($this->request->get['code'])) {
            $this->response->redirect($this->url->link('account/login', '', true));
        }

        // Exchange code for token
        $code = $this->request->get['code'];

        $token = $this->getAccessToken($code);

        if (isset($token['access_token'])) {
            $user = $this->getUserInfo($token['access_token']);

            if ($user && isset($user['email'])) {
                $this->load->model('account/customer');

                // Check if user already exists
                $customer = $this->model_account_customer->getCustomerByEmail($user['email']);

                if (!$customer) {
                    // Register new customer
                    $customer_id = $this->model_account_customer->addCustomer([
                        'firstname' => $user['given_name'] ?? 'Google',
                        'lastname'  => $user['family_name'] ?? '',
                        'email'     => $user['email'],
                        'telephone' => '',
                        'password'  => token(8), // random password
                    ]);

                    $customer = $this->model_account_customer->getCustomer($customer_id);
                }

                // Login user
                $this->customer->login($user['email'], '', true);

                $this->response->redirect($this->url->link('account/account', '', true));
            }
        }

        $this->response->redirect($this->url->link('account/login', '', true));
    }

    private function getAccessToken($code) {
        $url = "https://oauth2.googleapis.com/token";
        $post = [
            'code' => $code,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'grant_type' => 'authorization_code',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    private function getUserInfo($access_token) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/oauth2/v2/userinfo");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $access_token]);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
