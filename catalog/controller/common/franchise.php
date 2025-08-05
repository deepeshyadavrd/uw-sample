<?php

class ControllerCommonFranchise extends Controller {
private $error = [];
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

            // $to="deepeshurbanwood@gmail.com";
            // $subject="Request Callback enquiry";
            // $senderName = 'Urbanwood Furniture';
            // $senderEmail = 'support@urbanwood.in';

            // $from = stripslashes($senderName)."<".stripslashes($senderEmail).">";
            
            // $mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";

            // $headers = "From: $from\r\n" . 
            // // "Cc: urbanwood.in@gmail.com\r\n".
            // "MIME-Version: 1.0\r\n" .
            // "Content-Type: multipart/mixed;\r\n" .
            // " boundary=\"{$mime_boundary}\"";
	        // if(isset($_POST["time_slot"]) || isset($_POST["date"])){
	        // 	$message="Enquiry For Furniture (Request Callback)\n\n";
	        // 	$message .= "Name:".$_POST["fullname"]."\n"."EMail:".$_POST['email']."\nMobile:".$_POST['mobile']."\nTime Slot:".$_POST["time_slot"]."\nDate:".$_POST["date"]."\nLooking For:".$_POST["message"];
	        // }else{
	        // 	$message="Enquiry For Furniture (Contact Form)\n\n";
	        // 	$message .= "Name:".$_POST["fullname"]."\n"."EMail:".$_POST['email']."\nMobile:".$_POST['mobile']."\nLooking For:".$_POST["msg"];
	        // }
            // $message = "This is a multi-part message in MIME format.\n\n" .
            // "--{$mime_boundary}\n" .
            // "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
            // "Content-Transfer-Encoding: 7bit\n\n" .
            //  $message . "\n\n";
        
            // $i=0;
            // $message.="--{$mime_boundary}--\n";
            //  if (@mail($to, $subject, $message, $headers)){
            // 	 $json['success'] = 'Service Working';//exit;
            //  }else{
            //     http_response_code(400);
            //    $json['error'] =  'error -> '.error_get_last()['message'];//'Service Error';//exit;
            //  }
                   //$json['data'] = $this->request->post;
                   $json['success'] = 'Form submitted successfully.';
        }
    }else{
        http_response_code(400);
         $json['errors'] = $this->error;
    //    if (isset($this->error['name'])) {
    //        $json['errors'] = $this->error;
    //    } else {
    //        $json['error_name'] = '';
    //    }

    //    if (isset($this->error['email'])) {
    //        $json['errors'] = $this->error;
    //    } else {
    //        $json['error_email'] = '';
    //    }

    //    if (isset($this->error['mobile'])) {
    //        $json['errors'] = $this->error;
    //    } else {
    //        $json['error_mobile'] = '';
    //    }
    //    if (isset($this->error['message'])) {
    //        $json['errors'] = $this->error;
    //    } else {
    //        $json['error_message'] = '';
    //    }
    }
    // print_r($this->request->post['name']);
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

