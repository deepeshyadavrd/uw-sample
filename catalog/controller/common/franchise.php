<?php

class ControllerCommonFranchise extends Controller {

	public function index() {

		// $this->document->setTitle($this->config->get('config_meta_title'));

		// $this->document->setDescription($this->config->get('config_meta_description'));

		// $this->document->setKeywords($this->config->get('config_meta_keyword'));

		// if (isset($this->request->get['route'])) {

		// 	$this->document->addLink($this->config->get('config_url'), 'canonical');

		// }

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
        $json = array();
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // Restricted words
        $restrictedWords = ['example', 'test', 'xxx', 'sperm', 'sex'];

        // Sanitize input
        $fieldsToCheck = ['name', 'email', 'message']; // Add more fields as needed
        $errors = [];

        foreach ($fieldsToCheck as $field) {
            if (isset($_POST[$field])) {
                $input = htmlspecialchars($_POST[$field]);

                // Check for restricted words
                foreach ($restrictedWords as $word) {
                    if (strpos(strtolower($input), $word) !== false) {
                        $errors[] = "Invalid input detected in field '$field': Avoid using '$word'.";
                    }
                }
            }
        }
        if (!empty($errors)) {
            http_response_code(400);
            $json['error'] = implode("\n", $errors);
        } else {
            $result = $this->model_information_requestcallback->addFrachiseRequest($this->request->post);

            $to="deepeshurbanwood@gmail.com";
            $subject="Request Callback enquiry";
            $senderName = 'Urbanwood Furniture';
            $senderEmail = 'support@urbanwood.in';

            $from = stripslashes($senderName)."<".stripslashes($senderEmail).">";
            
            $mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";

            $headers = "From: $from\r\n" . 
            // "Cc: urbanwood.in@gmail.com\r\n".
            "MIME-Version: 1.0\r\n" .
            "Content-Type: multipart/mixed;\r\n" .
            " boundary=\"{$mime_boundary}\"";
	        if(isset($_POST["time_slot"]) || isset($_POST["date"])){
	        	$message="Enquiry For Furniture (Request Callback)\n\n";
	        	$message .= "Name:".$_POST["name"]."\n"."EMail:".$_POST['email']."\nMobile:".$_POST['mobile']."\nTime Slot:".$_POST["time_slot"]."\nDate:".$_POST["date"]."\nLooking For:".$_POST["message"];
	        }else{
	        	$message="Enquiry For Furniture (Contact Form)\n\n";
	        	$message .= "Name:".$_POST["name"]."\n"."EMail:".$_POST['email']."\nMobile:".$_POST['mobile']."\nLooking For:".$_POST["message"];
	        }
            $message = "This is a multi-part message in MIME format.\n\n" .
            "--{$mime_boundary}\n" .
            "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
            "Content-Transfer-Encoding: 7bit\n\n" .
             $message . "\n\n";
        
            $i=0;
            $message.="--{$mime_boundary}--\n";
             if (@mail($to, $subject, $message, $headers)){
            	 $json['success'] = 'Service Working';//exit;
             }else{
                http_response_code(400);
               $json['error'] =  'error -> '.error_get_last()['message'];//'Service Error';//exit;
             }
                   //$json['data'] = $this->request->post;
        }
    }else{
        http_response_code(400);
       if (isset($this->error['name'])) {
           $json['error_name'] = $this->error['name'];
       } else {
           $json['error_name'] = '';
       }

       if (isset($this->error['email'])) {
           $json['error_email'] = $this->error['email'];
       } else {
           $json['error_email'] = '';
       }

       if (isset($this->error['mobile'])) {
           $json['error_mobile'] = $this->error['mobile'];
       } else {
           $json['error_mobile'] = '';
       }
       if (isset($this->error['message'])) {
           $json['error_message'] = $this->error['message'];
       } else {
           $json['error_message'] = '';
       }
    }
    // print_r($this->request->post['name']);
        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = 'Name must be between 3 to 32 charaters';//$this->language->get('error_name');
        }

        if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = 'Email should be valid';//$this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['mobile']) < 10) || (utf8_strlen($this->request->post['mobile']) > 10)) {
            $this->error['mobile'] = 'Mobile number must be of 10 digits';//$this->language->get('error_enquiry');
        }
        if ((utf8_strlen($this->request->post['message']) < 10) || (utf8_strlen($this->request->post['message']) > 3000)) {
            $this->error['message'] = 'Message must be between 10 to 3000 charaters';//$this->language->get('error_enquiry');
        }

        return !$this->error;
    }
	

}

