<?php

class ControllerCustomCustomfurniture extends Controller{

     public function index(){
        
        $this->document->setTitle('Custom Furniture : Buy Customized Furniture Online in India : UrbanWood');
		$this->document->setDescription('Custom Furniture: Buy Custom Made Furniture Online in India. Select Your Design and Customize Our Wide Range of Custom Sofa, Dining, Bed, Chairs at Best Price. ');
		$this->document->setKeywords('custom furniture, custom made furniture, customized furniture online, customize furniture design');
		$this->document->addLink('https://www.urbanwood.in/custom-furniture','canonical');
        $data['header'] = $this->load->controller('common/header');  

        $data['footer'] = $this->load->controller('common/footer');  

        $data['menu'] = $this->load->controller('common/menu');  

        $this->response->setOutput($this->load->view('custom/customfurniture', $data));

    }

    public function customerRequest(){
        $json = array();
        if(($this->request->server['REQUEST_METHOD'] == 'POST') && $_POST['name'] != ' ' && $_POST['name'] != NULL && $_POST['email'] != ' ' && $_POST['email'] != NULL && $this->validate()){

			if (!isset($json['error'])) {
				$to="urbanwoodindia.in@gmail.com";
   				$subject="Custom furniture enquiry";
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
						$message="Enquiry For Custom Furniture (Request Callback)\n\n";
						$message .= "Name:".$_POST["name"]."\n"."EMail: ".$_POST['email']."\nCity:".$_POST['city']."\nPincode:".$_POST['pincode']."\nMobile:".$_POST['mobile']."\nTime Slot:".$_POST["time_slot"]."\nDate:".$_POST["date"]."\nLooking For:".$_POST["message"];
					}else{
						$message="Enquiry For Custom Furniture\n\n";
						$message .= "Name:".$_POST["name"]."\n"."EMail: ".$_POST['email']."\nMobile:".$_POST['mobile']."\nState:".$_POST['state']."\nLooking For:".$_POST["message"];
					}
					$message = "This is a multi-part message in MIME format.\n\n" .
    				"--{$mime_boundary}\n" .
    				"Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
    				"Content-Transfer-Encoding: 7bit\n\n" .
    				 $message . "\n\n";

					$i=0;
					// foreach($_FILES['customImages']['name'] as $userfile){ 
						//  print_r($userfile);
						$tmp_name = $_FILES['customImage']['tmp_name'];
						$type = $_FILES['customImage']['type'];
						$name = $_FILES['customImage']['name'];
						$size = $_FILES['customImage']['size'];
					
						$i++;
					   
						if (file_exists($tmp_name)){
						
							// echo 'file_exists';
							 if(is_uploaded_file($tmp_name)){
						
							   
								$file = fopen($tmp_name,'rb');
						
							   
								$data = fread($file,filesize($tmp_name));
						
							   
								fclose($file);
						
								
								$data = chunk_split(base64_encode($data));
							}
							$message .= "--{$mime_boundary}\n" .
								"Content-Type: {$type};\n" .
								" name=\"{$name}\"\n" .
								"Content-Disposition: attachment;\n" .
								" filename=\"{$name}\"\n" .
								"Content-Transfer-Encoding: base64\n\n" .
							$data . "\n\n";
						}
					// }
    				$message = "This is a multi-part message in MIME format.\n\n" .
    				"--{$mime_boundary}\n" .
    				"Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
    				"Content-Transfer-Encoding: 7bit\n\n" .
     				$message . "\n\n";

   					$i=0;
 					$message.="--{$mime_boundary}--\n";
  					if (@mail($to, $subject, $message, $headers)){
						 $response =  'Service Working';
  					}else{
						$response = 'Service Error';
  					}
				$this->load->model('information/requestcallback');

				$rcId = $this->model_information_requestcallback->addRequestcallback($this->request->post);
                 
				if(!empty($this->request->files) && isset($this->request->files['customImage']) && $this->request->files['customImage']['error'] == 0){
					$result = $this->uploadCustomImage($this->request->files, $rcId);
					// print_r($this->request->files);
				}else{
					// print_r($this->request->files);
					$result['success'][] = $json['success'][] = 'text_upload';
				}
				$json = $result;//$this->language->get('text_success');
			}
		}else{
			$json['error'] = 'Empty Data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
        
    }

	public function addtowhatsapplist(){

        $json = array();
        if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){

			// Captcha
			if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
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
						$message="Enquiry For Custom Furniture (Request Callback)\n\n";
						$message .= "Name:".$_POST["name"]."\n"."EMail: ".$_POST['email']."\nMobile:".$_POST['mobile']."\nTime Slot:".$_POST["time_slot"]."\nDate:".$_POST["date"]."\nLooking For:".$_POST["message"];
					}else{
						$message="Enquiry For Custom Furniture\n\n";
						$message .= "Name:".$_POST["name"]."\n"."EMail: ".$_POST['email']."\nMobile:".$_POST['mobile']."\nLooking For:".$_POST["message"];
					}
    			$message = "This is a multi-part message in MIME format.\n\n" .
    			"--{$mime_boundary}\n" .
    			"Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
    			"Content-Transfer-Encoding: 7bit\n\n" .
     			$message . "\n\n";

   				$i=0;
 				$message.="--{$mime_boundary}--\n";
  				if (@mail($to, $subject, $message, $headers)){
					 $response =  'Service Working';
  				}else{
					$response = 'Service Error';
  				}
				$this->load->model('custom/customfurniture');

				$this->model_custom_customfurniture->addToWhatsappList($this->request->post);

				$json['success'] = $response; //this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
        
    }
    protected function validate() {
		if ((utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
			$this->error['name'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['mobile']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

        if ((utf8_strlen($this->request->post['state']) < 2) || (utf8_strlen($this->request->post['state']) > 32)) {
			$this->error['state'] = $this->language->get('error_city');
		}

        if ((utf8_strlen($this->request->post['message']) < 10) || (utf8_strlen($this->request->post['message']) > 3000)) {
			$this->error['message'] = $this->language->get('message');
		}

		return !$this->error;
	}

    protected function uploadCustomImage($img,$cfrId) {

		$json = array();
		if (isset($img['customImage']['name'])) {
				$name = $img['customImage']['name'];
				$tmp_name = $img['customImage']['tmp_name'];
				$type = $img['customImage']['type'];
	
				if (!empty($name) && is_file($tmp_name)) {
					// Sanitize the filename
					$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($name, ENT_QUOTES, 'UTF-8')));
	
					// Validate the filename length
					if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
						$json['error'] = $this->language->get('error_filename');
					}
	
					// Allowed file extension types
					$allowedExtensions = array_map('trim', explode("\n", preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'))));
					if (!in_array(strtolower(pathinfo($filename, PATHINFO_EXTENSION)), $allowedExtensions)) {
						$json['error'] = $this->language->get('error_filetype');
					}
	
					// Allowed file MIME types
					$allowedMimes = array_map('trim', explode("\n", preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'))));
					if (!in_array($type, $allowedMimes)) {
						$json['error'] = $this->language->get('error_filetype');
					}
	
					// Check for PHP code in the uploaded file
					if (preg_match('/\<\?php/i', file_get_contents($tmp_name))) {
						$json['error'] = $this->language->get('error_filetype');
					}
	
					// Ensure the upload directory exists
					$uploadDir = "./image/customRequestImage/";
	
					// Move the uploaded file to the specified directory
					if (move_uploaded_file($tmp_name, $uploadDir . $filename)) {
						// Save to database or perform additional actions
						$this->load->model('tool/upload');
						$this->model_tool_upload->addCustomImages($cfrId, $uploadDir . $filename);
						$json['success'][] = sprintf($this->language->get('text_upload'), htmlspecialchars($filename));
					} else {
						$json['error'] = sprintf($this->language->get('error_move'), htmlspecialchars($filename));
					}
				} else {
					$json['error'] = sprintf($this->language->get('error_upload'), htmlspecialchars($name));
				}
		} else {
			$json['error'] = 'No files were uploaded.';
		}
	
		return $json;
	}

}