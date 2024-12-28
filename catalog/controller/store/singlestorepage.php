<?php
class ControllerStoreSinglestorepage extends Controller{
     public function index(){
		

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setReplyTo($this->request->post['email']);
			$mail->setSender(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->request->post['name']), ENT_QUOTES, 'UTF-8'));
			$mail->setText($this->request->post['message']);
			if($mail->send()){
				echo 'success';
			}else{
				echo 'failed';
			}

			//$this->response->redirect($this->url->link('information/contact/success'));
		}
	// 	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
	// 	$to="raoshsbh6@@gmail.com";
	// 	$subject="Request Callback enquiry";
	// 	$senderName = 'Urbanwood Furniture';
	// 	$senderEmail = 'deepeshurbanwood@gmail.com';
	 
	// 	$from = stripslashes($senderName)."<".stripslashes($senderEmail).">";
	  
	// 	$mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";
	 
		
	// 	$headers = "From: $from\r\n" . 
	// 	"MIME-Version: 1.0\r\n" .
	// 	"Content-Type: multipart/mixed;\r\n" .
	//    " boundary=\"{$mime_boundary}\"";
	// 	 if(isset($_POST["time_slot"]) || isset($_POST["date"])){
	// 		 $message="Enquiry For Furniture (Request Callback)\n\n";
	// 		 $message .= "Name:".$_POST["name"]."\n"."EMail:".$_POST['email']."\nMobile:".$_POST['mobile']."\nTime Slot:".$_POST["time_slot"]."\nDate:".$_POST["date"]."\nLooking For:".$_POST["message"];
	// 	 }else{
	// 		 $message="Enquiry For Furniture (Contact Form)\n\n";
	// 		 $message .= "Name:".$_POST["name"]."\n"."EMail:".$_POST['email']."\nMobile:".$_POST['mobile']."\nLooking For:".$_POST["message"];
	// 	 }
	// 	 $message = "This is a multi-part message in MIME format.\n\n" .
	// 	 "--{$mime_boundary}\n" .
	// 	 "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
	// 	 "Content-Transfer-Encoding: 7bit\n\n" .
	// 	  $message . "\n\n";
	 
	// 	$i=0;
	//   $message.="--{$mime_boundary}--\n";
	//    if (@mail($to, $subject, $message, $headers)){
	// 	  echo 'Service Working';exit;
	//    }else{
	// 	  echo 'Service Error ' . error_get_last()['message'];exit;
	//    }

	// }
	elseif (($this->request->get['s_id'])) {
      $this->load->model('localisation/location');

      $location_id = $this->request->get['s_id'];
      $location_info = $this->model_localisation_location->getLocation($location_id);

			if ($location_info) {
				if ($location_info['image']) {
					$image = $this->model_tool_image->resize($location_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_location_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_location_height'));
				} else {
					$image = false;
				}

				$data['locations'] = array(
					'location_id' => $location_info['location_id'],
					'name'        => $location_info['name'],
					'address'     => nl2br($location_info['address']),
					'geocode'     => $location_info['geocode'],
					'location_link'     => $location_info['location_link'],
					'telephone'   => $location_info['telephone'],
					'fax'         => $location_info['fax'],
					'image'       => $image,
					'open'        => nl2br($location_info['open']),
					'comment'     => $location_info['comment']
				);
			}

		$data['locationList'] = array();
		foreach((array)$this->config->get('config_location') as $location_id) {
			$location_info = $this->model_localisation_location->getLocation($location_id);
		
			if ($location_info) {
				if ($location_info['image']) {
					$image = $this->model_tool_image->resize($location_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_location_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_location_height'));
				} else {
					$image = false;
				}
			
				$data['locationList'][] = array(
					'location_id' => $location_info['location_id'],
					'name'        => $location_info['name'],
				);
			}
		}
		//print_r($data['locationList']);

        $data['header'] = $this->load->controller('common/header');  
        $data['footer'] = $this->load->controller('common/footer');  
        $data['menu'] = $this->load->controller('common/menu');  
        $data['singlestorehead'] = $this->load->controller('store/singlestorehead');  
        $data['storedetail'] = $this->load->controller('store/storedetail'); 
        $data['catelogrequest'] = $this->load->controller('store/catelogrequest'); 
        $data['customerstories'] = $this->load->controller('store/customerstories'); 
        $data['bottomcontent'] = $this->load->controller('store/bottomcontent');  
          
        
        $this->response->setOutput($this->load->view('store/singlestorepage', $data));
		}else{
			$this->response->redirect($this->url->link('store/ourstores'));
		}
    }
}
?>