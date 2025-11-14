<?php

class ControllerInformationInformation extends Controller {

	public function index() {

		$this->load->language('information/information');



		$this->load->model('catalog/information');



		$data['breadcrumbs'] = array();



		$data['breadcrumbs'][] = array(

			'text' => $this->language->get('text_home'),

			'href' => $this->url->link('common/home')

		);



		if (isset($this->request->get['information_id'])) {

			$information_id = (int)$this->request->get['information_id'];

		} else {

			$information_id = 0;

		}



		$information_info = $this->model_catalog_information->getInformation($information_id);



		if ($information_info) {

			$this->document->setTitle($information_info['meta_title']);

			$this->document->setDescription($information_info['meta_description']);

			$this->document->setKeywords($information_info['meta_keyword']);



			$data['breadcrumbs'][] = array(

				'text' => $information_info['title'],

				'href' => $this->url->link('information/information', 'information_id=' .  $information_id)

			);

			$data['information_id'] = $information_id;

			$data['heading_title'] = $information_info['title'];




			
			$data['description'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
			


			$data['continue'] = $this->url->link('common/home');



			$data['column_left'] = $this->load->controller('common/column_left');

			$data['column_right'] = $this->load->controller('common/column_right');

			$data['content_top'] = $this->load->controller('common/content_top');

			$data['content_bottom'] = $this->load->controller('common/content_bottom');

			$data['footer'] = $this->load->controller('common/footer');

			$data['header'] = $this->load->controller('common/header');

			$data['menu'] = $this->load->controller('common/menu');
			$data['customerstories'] = $this->load->controller('home/customerstories');



			$this->response->setOutput($this->load->view('information/information', $data));

		} else {

			$data['breadcrumbs'][] = array(

				'text' => $this->language->get('text_error'),

				'href' => $this->url->link('information/information', 'information_id=' . $information_id)

			);



			$this->document->setTitle($this->language->get('text_error'));



			$data['heading_title'] = $this->language->get('text_error');



			$data['text_error'] = $this->language->get('text_error');



			$data['continue'] = $this->url->link('common/home');



			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');



			$data['column_left'] = $this->load->controller('common/column_left');

			$data['column_right'] = $this->load->controller('common/column_right');

			$data['content_top'] = $this->load->controller('common/content_top');

			$data['content_bottom'] = $this->load->controller('common/content_bottom');

			$data['footer'] = $this->load->controller('common/footer');

			$data['header'] = $this->load->controller('common/header');



			$this->response->setOutput($this->load->view('error/not_found', $data));

		}

	}



	public function agree() {

		$this->load->model('catalog/information');



		if (isset($this->request->get['information_id'])) {

			$information_id = (int)$this->request->get['information_id'];

		} else {

			$information_id = 0;

		}



		$output = '';



		$information_info = $this->model_catalog_information->getInformation($information_id);



		if ($information_info) {

			$output .= html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8') . "\n";

		}



		$this->response->setOutput($output);

	}

	public function savebulk(){

	    
		if($_POST['name'] != ' ' && $_POST['name'] != NULL && $_POST['email'] != ' ' && $_POST['email'] != NULL){
	    	$name = $_POST['name'];

	    	$email = $_POST['email'];

	    	$mobile =$_POST['contact'];

	    	$city = $_POST['city'];

	    	$description = $_POST['description'];
			$now = date('Y-m-d H:i:s');

	    	$this->db->query("INSERT INTO oc_bulk_order (name,email,mobile,city,description,indate) VALUES ('$name','$email','$mobile','$city','$description', '$now')");


	    	return true;
		}
		return false;

	}
	public function savejob(){
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
				$to="hr@urbanwood.in";
   				$subject="Job enquiry";
   				$senderName = 'Urbanwood Furniture';
   				$senderEmail = 'support@urbanwood.in';

   				$from = stripslashes($senderName)."<".stripslashes($senderEmail).">";

   				$mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";

   				$headers = "From: $from\r\n" . 
   					"Cc: urbanwood.in@gmail.com\r\n".
   					"MIME-Version: 1.0\r\n" .
   					"Content-Type: multipart/mixed;\r\n" .
  					" boundary=\"{$mime_boundary}\"";
					if(isset($_POST["time_slot"]) || isset($_POST["date"])){
						$message="Job applied\n\n";
						$message .= "Name:".$_POST["name"]."\n"."EMail: ".$_POST['email']."\nCity:".$_POST['city']."\nPincode:".$_POST['pincode']."\nMobile:".$_POST['mobile']."\nTime Slot:".$_POST["time_slot"]."\nDate:".$_POST["date"]."\nLooking For:".$_POST["message"];
					}else{
						$message="Enquiry For Jobs\n\n";
						$message .= "Name:".$_POST["name"]."\n"."EMail: ".$_POST['email']."\nMobile:".$_POST['mobile']."\nAbout me:".$_POST["message"];
					}
					$message = "This is a multi-part message in MIME format.\n\n" .
    "--{$mime_boundary}\n" .
    "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" .
     $message . "\n\n";

					
						//  print_r($userfile);
							  $tmp_name = $_FILES['resume']['tmp_name'];
							  $type = $_FILES['resume']['type'];
							  $name = $_FILES['resume']['name'];
							  $size = $_FILES['resume']['size'];
					
						
					   
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

				$crerId = $this->model_information_requestcallback->addCareer($this->request->post);
                //print_r($this->request);
				$result = $this->uploadResume($this->request->files, $crerId);
				$json['success'] = $result;//$this->language->get('text_success');
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

        if ((utf8_strlen($this->request->post['pincode']) < 6) || (utf8_strlen($this->request->post['pincode']) > 6)) {
			$this->error['pincode'] = $this->language->get('error_pincode');
		}

        if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32)) {
			$this->error['city'] = $this->language->get('error_city');
		}

        if ((utf8_strlen($this->request->post['message']) < 10) || (utf8_strlen($this->request->post['message']) > 3000)) {
			$this->error['message'] = $this->language->get('message');
		}

		return !$this->error;
	}

	protected function uploadResume($img, $crerId){
		$file=$img["resume"]["name"];
		$tmp_name=$img["resume"]["tmp_name"];
		$path="image/resumes/".$file;
		$file1=explode(".",$file);
		$ext=$file1[1];
		$allowed=array("pdf","wmv","png","gif","pdf","zip","jpg");
		if(in_array($ext,$allowed)){
 		move_uploaded_file($tmp_name,$path);
		 $this->load->model('tool/upload');

		 return $this->model_tool_upload->addResumepath($crerId, $path);
		}
	}

}