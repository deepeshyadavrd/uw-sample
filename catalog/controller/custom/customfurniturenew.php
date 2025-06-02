<?php
class ControllerCustomCustomfurniturenew extends Controller{
     public function index(){
        if (($this->request->post)) {
            print_r($this->request->post);
        }else{
        $data['header'] = $this->load->controller('common/header');  
        $data['footer'] = $this->load->controller('common/footer');  
        $data['menu'] = $this->load->controller('common/menu');  

        $this->response->setOutput($this->load->view('custom/customfurniturenew', $data));
        }
    }
    public function customerRequest(){
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
				$this->load->model('information/requestcallback');

				$rcId = $this->model_information_requestcallback->addRequestcallback($this->request->post);
// print_r($rcId);
				$this->uploadCustomImage($this->request->files, $rcId);
				$json['success'] = $this->language->get('text_success');
			}
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
				$this->load->model('custom/customfurniture');

				$this->model_custom_customfurniture->addToWhatsappList($this->request->post);

				$json['success'] = $this->language->get('text_success');
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

    protected function uploadCustomImage($img, $cfrId){
		
		$json = array();

	$name = $img['customImage']['name'];
	$tmp_name = $img['customImage']['tmp_name'];
    $error = $img['customImage']['error'];
    $size = $img['customImage']['size'];
    $type = $img['customImage']['type'];
		if (!empty($name) && is_file($tmp_name)) {
			// Sanitize the filename
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($name, ENT_QUOTES, 'UTF-8')));

			// Validate the filename length
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// Allowed file extension types
			$allowed = array();
			$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));
			$filetypes = explode("\n", $extension_allowed);

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Allowed file mime types
			$allowed = array();
			$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));
			$filetypes = explode("\n", $mime_allowed);

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array($type, $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Check to see if any PHP files are trying to be uploaded
			$content = file_get_contents($tmp_name);

			if (preg_match('/\<\?php/i', $content)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Return any upload error
			if ($error != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $error);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json) {
			$file = "./image/customRequestImage/" . $filename;
			move_uploaded_file($tmp_name, $file);

			// Hide the uploaded file name so people can not link to it directly.
			$this->load->model('tool/upload');
			$this->model_tool_upload->addCustomImages($cfrId, $file);
		}
	
		
	}
}