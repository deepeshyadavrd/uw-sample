<?php

class ControllerCommonFranchise extends Controller {
private $error = [];
	public function index() {

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['menu'] = $this->load->controller('common/menu');
		// $data['register'] = $this->url->link('account/register');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->load->model('tool/image');

		$this->response->setOutput($this->load->view('common/franchise', $data));

	}
    public function sendtodb(){
                $this->load->model('information/requestcallback');
                // print_r($this->request->post);
        $json = [];
        $existing = $this->model_information_requestcallback->getFranchiseByEmailOrMobile($_POST['email'], $_POST['mobile']);

        if ($existing) {
            http_response_code(400);
            $json['errors'] = ['duplicate' => 'You have already submitted a request with this email or phone number.'];
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            return;
        }
        elseif (($this->request->server['REQUEST_METHOD'] === 'POST') && $this->validate()) {
            // Restricted words
            $restrictedWords = ['example', 'test', 'xxx', 'sperm', 'sex'];
            $fieldsToCheck = ['fullname', 'email', 'msg'];
            $restrictedErrors = [];

            foreach ($fieldsToCheck as $field) {
                if (isset($this->request->post[$field])) {
                    $input = strtolower($this->request->post[$field]);
                    foreach ($restrictedWords as $word) {
                        if (strpos($input, $word) !== false) {
                            $restrictedErrors[$field] = "Invalid input in '$field': avoid using '$word'.";
                            break; // Avoid duplicate messages for same field
                        }
                    }
                }
            }

            if (!empty($restrictedErrors)) {
                http_response_code(400);
                $json['errors'] = $restrictedErrors;
            } else {
                $result = $this->model_information_requestcallback->addFranchiseRequest($this->request->post);

                $to="urbanwoodindia.in@gmail.com";
                $subject="Franchise enquiry";
                $senderName = 'Urbanwood Furniture';
                $senderEmail = 'support@urbanwood.in';

                $from = stripslashes($senderName)."<".stripslashes($senderEmail).">";

                // $mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";

                $headers = "From: $from\r\n" . 
                "Cc: urbanwood.in@gmail.com"."\r\n".
                "MIME-Version: 1.0\r\n" .
                "Content-Type: text/plain;\r\n" .
                // " boundary=\"{$mime_boundary}\"";
                	
                		$message="Franchise Request\n\n";
                		$message .= "Name: ".$_POST["fullname"]."\n"."EMail: ".$_POST['email']."\nMobile: ".$_POST['mobile']."\nCity: ".$_POST['city']."\nAlready own Franchise: ".$_POST["franchisee"]."\nHave retail space: ".$_POST["retail_space"]."\nAmount: ".$_POST["investamt"]."\nMessage: ".$_POST["msg"];

                  $message = "This is a multi-part message in MIME format.\n\n" .
                //   "--{$mime_boundary}\n" .
                //   "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
                //   "Content-Transfer-Encoding: 7bit\n\n" .
                   $message . "\n\n";
                
                 $i=0;
                // $message.="--{$mime_boundary}--\n";
            if (@mail($to, $subject, $message, $headers)){
            	 $json['success'] = 'Form submitted, mail sent';//exit;
             }else{
                $json['error'] = 'error -> '.error_get_last()['message'];//'Service Error';//exit;
             }
        }
    }else{
        http_response_code(400);
         $json['errors'] = $this->error;
    }
        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }

    protected function validate() {
         $this->error = array();

    if ((utf8_strlen($this->request->post['fullname']) < 3) || (utf8_strlen($this->request->post['fullname']) > 32)) {
        $this->error['name'] = 'Name must be between 3 to 32 characters';
        // print_r($this->error['name']);
    }

    if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
        $this->error['email'] = 'Email should be valid';
    }

    if ((utf8_strlen($this->request->post['mobile']) < 10) || (utf8_strlen($this->request->post['mobile']) > 10)) {
        $this->error['mobile'] = 'Mobile number must be exactly 10 digits';
    }

    if (!isset($this->request->post['msg']) || utf8_strlen($this->request->post['msg']) < 10 || utf8_strlen($this->request->post['msg']) > 3000) {
        $this->error['msg'] = 'Message must be between 10 to 3000 characters';
// print_r($this->error['msg']);
    }

    // error_log("Validation Errors: " . print_r($this->error, true));
// print_r($this->error);
    return !$this->error;
    }
	

}

