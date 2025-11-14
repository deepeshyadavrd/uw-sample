<?php
class ControllerInformationRequestcallback extends Controller {
	private $error = array();

	public function index() {
        $this->load->model('information/requestcallback');
        if($_POST['name'] != ' ' && $_POST['name'] != NULL && $_POST['email'] != ' ' && $_POST['email'] != NULL){
        $result = $this->model_information_requestcallback->addRequestcallback($this->request->post);
        $json = array();
        $to="urbanwoodindia.in@gmail.com";
   $subject="Request Callback enquiry";
   $senderName = 'Urbanwood Furniture';
   $senderEmail = 'support@urbanwood.in';

   $from = stripslashes($senderName)."<".stripslashes($senderEmail).">";
 
   $mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";

   
   $headers = "From: $from\r\n" . 
   "Cc: urbanwood.in@gmail.com"."\r\n".
   "MIME-Version: 1.0\r\n" .
   "Content-Type: multipart/mixed;\r\n" .
  " boundary=\"{$mime_boundary}\"";
	if(isset($_POST["time_slot"]) || isset($_POST["date"])){
		$message="Enquiry For Furniture (Request Callback)\n\n";
		$message .= "Name: ".$_POST["name"]."\n"."EMail: ".$_POST['email']."\nMobile: ".$_POST['mobile']."\nTime Slot:".$_POST["time_slot"]."\nDate:".$_POST["date"]."\nLooking For:".$_POST["message"];
	}else{
		$message="Call Back Request\n\n";
		$message .= "Name: ".$_POST["name"]."\n"."EMail: ".$_POST['email']."\nMobile: ".$_POST['mobile']."\nState: ".$_POST['state']."\nLooking For: ".$_POST["message"];
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
     $json['error'] = 'error -> '.error_get_last()['message'];//'Service Error';//exit;
  }
        //$json['data'] = $this->request->post;
        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }
    $json['error'] = 'empty data';
    $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }
}