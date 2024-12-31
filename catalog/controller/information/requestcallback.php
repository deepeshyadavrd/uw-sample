<?php
class ControllerInformationRequestcallback extends Controller {
	private $error = array();

	public function index() {
        $this->load->model('information/requestcallback');
        $json = array();
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // Restricted words
        $restrictedWords = ['example', 'test', 'xxx'];

            if (strpos(strtolower($_POST['name']), 'test') !== false || strpos(strtolower($_POST['email']), 'test') !== false) {
            $json['error'] ="Invalid input detected. Please avoid using restricted words.";
            }else{
        $result = $this->model_information_requestcallback->addRequestcallback($this->request->post);
        

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
       $json['error'] =  'error -> '.error_get_last()['message'];//'Service Error';//exit;
     }
           //$json['data'] = $this->request->post;
    }
    }else{
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