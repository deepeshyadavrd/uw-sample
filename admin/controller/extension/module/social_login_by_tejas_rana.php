<?php
class ControllerExtensionModuleSocialLoginByTejasRana extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/social_login_by_tejas_rana');
		$data['objlang'] = $this->language;

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');
		$this->load->model('setting/setting');

		$module_id = '';
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$action = isset($this->request->post["action"]) ? $this->request->post["action"] : "";
			unset($this->request->post['action']);
			
			$params = $this->request->post['social_login_by_tejas_rana'];
			$this->model_setting_setting->editSetting('social_login_by_tejas_rana', $params);

			$params_module = array_merge($params, array('name'=>$params['social_login_by_tejas_rana_name'], 'status'=>$params['social_login_by_tejas_rana_enable']));
			
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('social_login_by_tejas_rana', $params_module);
				$module_id = $this->db->getLastId();
			}
			else {
				$module_id = $this->request->get['module_id'];
				$this->model_setting_module->editModule($this->request->get['module_id'], $params_module);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			if($action == "save_edit") {
				$this->response->redirect($this->url->link('extension/module/social_login_by_tejas_rana', 'user_token=' . $this->session->data['user_token'] . '&module_id='.$module_id, 'SSL'));
			}elseif($action == "save_new"){
				$this->response->redirect($this->url->link('extension/module/social_login_by_tejas_rana', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}else{
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			}
		}

		// Save and Stay --------------------------------------------------------------
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_title_so'] = $this->language->get('heading_title_so');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['tab_setting'] = $this->language->get('tab_setting');
		$data['tab_facbook'] = $this->language->get('tab_facbook');
		$data['tab_twitter'] = $this->language->get('tab_twitter');
		$data['tab_google'] = $this->language->get('tab_google');
		$data['tab_linkedin'] = $this->language->get('tab_linkedin');
		$data['tab_introductions'] = $this->language->get('tab_introductions');
		
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_apikey'] = $this->language->get('entry_apikey');
		$data['entry_apisecret'] = $this->language->get('entry_apisecret');
		$data['entry_twapikey'] = $this->language->get('entry_twapikey');
		$data['entry_twapisecret'] = $this->language->get('entry_twapisecret');
		$data['entry_googleapikey'] = $this->language->get('entry_goapikey');
		$data['entry_googleapisecret'] = $this->language->get('entry_goapisecret');
		$data['entry_liapikey'] = $this->language->get('entry_liapikey');
		$data['entry_liapisecret'] = $this->language->get('entry_liapisecret');
		$data['entry_iconsize'] = $this->language->get('entry_iconsize');
		$data['entry_icon'] = $this->language->get('entry_icon');
		$data['entry_buttonsocial'] = $this->language->get('entry_buttonsocial');
		$data['text_fblink'] = $this->language->get('text_fblink');
		$data['text_twitlink'] = $this->language->get('text_twitlink');
		$data['text_googlelink'] = $this->language->get('text_googlelink');
		$data['text_linkdinlink'] = $this->language->get('text_linkdinlink');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['fbapikey'])) {
			$data['error_fbapikey'] = $this->error['fbapikey'];
		} else {
			$data['error_fbapikey'] = '';
		}
		
		if (isset($this->error['fbsecretapi'])) {
			$data['error_fbsecretapi'] = $this->error['fbsecretapi'];
		} else {
			$data['error_fbsecretapi'] = '';
		}
		
		if (isset($this->error['twitapikey'])) {
			$data['error_twitapikey'] = $this->error['twitapikey'];
		} else {
			$data['error_twitapikey'] = '';
		}
		
		
		if (isset($this->error['twitsecretapi'])) {
			$data['error_twitsecret'] = $this->error['twitsecretapi'];
		} else {
			$data['error_twitsecret'] = '';
		}
		
	
		if (isset($this->error['googleapikey'])) {
			$data['error_googleapikey'] = $this->error['googleapikey'];
		} else {
			$data['error_googleapikey'] = '';
		}
		
		if (isset($this->error['googlesecretapi'])) {
			$data['error_googlesecret'] = $this->error['googlesecretapi'];
		} else {
			$data['error_googlesecret'] = '';
		}
		
		if (isset($this->error['linkdinapikey'])) {
			$data['error_linkdinapikey'] = $this->error['linkdinapikey'];
		} else {
			$data['error_linkdinapikey'] = '';
		}
		
		if (isset($this->error['linkdinsecretapi'])) {
			$data['error_linkdinsecret'] = $this->error['linkdinsecretapi'];
		} else {
			$data['error_linkdinsecret'] = '';
		}

		if (isset($this->error['error_width'])) {
			$data['error_width'] = $this->error['error_width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['error_height'])) {
			$data['error_height'] = $this->error['error_height'];
		} else {
			$data['error_height'] = '';
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/social_login_by_tejas_rana', 'user_token=' . $this->session->data['user_token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/social_login_by_tejas_rana', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);			
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/social_login_by_tejas_rana', 'user_token=' . $this->session->data['user_token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/social_login_by_tejas_rana', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			// $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
			$module_info = $this->model_setting_setting->getSetting('social_login_by_tejas_rana');
		}
		else
			$module_info = $this->model_setting_setting->getSetting('social_login_by_tejas_rana');
		
		$params = isset($this->request->post['social_login_by_tejas_rana']) ? $this->request->post['social_login_by_tejas_rana'] : array();

		if (isset($params['social_login_by_tejas_rana_name'])) {
			$data['name'] = $params['social_login_by_tejas_rana_name'];
		} elseif (!empty($module_info)) {
			$data['name'] = isset($module_info['social_login_by_tejas_rana_name']) ? $module_info['social_login_by_tejas_rana_name'] : '';
		} else {
			$data['name'] = '';
		}

		if (isset($params['social_login_by_tejas_rana_button'])) {
			$data['button_social'] = $params['social_login_by_tejas_rana_button'];
		} elseif (!empty($module_info)) {
			$data['button_social'] = isset($module_info['social_login_by_tejas_rana_button']) ? $module_info['social_login_by_tejas_rana_button'] : '';
		} else {
			$data['button_social'] = '';
		}

		if (isset($params['social_login_by_tejas_rana_popuplogin'])) {
			$data['popuplogin'] = $params['social_login_by_tejas_rana_popuplogin'];
		} elseif (!empty($module_info)) {
			$data['popuplogin'] = isset($module_info['social_login_by_tejas_rana_popuplogin']) ? $module_info['social_login_by_tejas_rana_popuplogin'] : '';
		} else {
			$data['popuplogin'] = '';
		}

		if (isset($params['social_login_by_tejas_rana_fbtitle'])) {
			$data['fbtitle'] = $params['social_login_by_tejas_rana_fbtitle'];
		} elseif (!empty($module_info)) {
			$data['fbtitle'] = isset($module_info['social_login_by_tejas_rana_fbtitle']) ? $module_info['social_login_by_tejas_rana_fbtitle'] : '';
		} else {
			$data['fbtitle'] = '';
		}
			
		if (isset($params['social_login_by_tejas_rana_twittertitle'])) {
			$data['twittertitle'] = $params['social_login_by_tejas_rana_twittertitle'];
		} elseif (!empty($module_info)) {
			$data['twittertitle'] = isset($module_info['social_login_by_tejas_rana_twittertitle']) ? $module_info['social_login_by_tejas_rana_twittertitle'] : '';
		} else {
			$data['twittertitle'] = '';
		}
				
		if (isset($params['social_login_by_tejas_rana_googletitle'])) {
			$data['googletitle'] = $params['social_login_by_tejas_rana_googletitle'];
		} elseif (!empty($module_info)) {
			$data['googletitle'] = isset($module_info['social_login_by_tejas_rana_googletitle']) ? $module_info['social_login_by_tejas_rana_googletitle'] : '';
		} else {
			$data['googletitle'] = '';
		}
					
		if (isset($params['social_login_by_tejas_rana_linkedintitle'])) {
			$data['linkedintitle'] = $params['social_login_by_tejas_rana_linkedintitle'];
		} elseif (!empty($module_info)) {
			$data['linkedintitle'] = isset($module_info['social_login_by_tejas_rana_linkedintitle']) ? $module_info['social_login_by_tejas_rana_linkedintitle'] : '';
		} else {
			$data['linkedintitle'] = '';
		}
						
		
		if (isset($params['social_login_by_tejas_rana_width'])) {
			$data['width'] = $params['social_login_by_tejas_rana_width'];
		} elseif (!empty($module_info)) {
			$data['width'] = isset($module_info['social_login_by_tejas_rana_width']) ? $module_info['social_login_by_tejas_rana_width'] : '';
		} else {
			$data['width'] = '100';
		}
		
		if (isset($params['social_login_by_tejas_rana_height'])) {
			$data['height'] = $params['social_login_by_tejas_rana_height'];
		} elseif (!empty($module_info)) {
			$data['height'] = isset($module_info['social_login_by_tejas_rana_height']) ? $module_info['social_login_by_tejas_rana_height'] : '';
		} else {
			$data['height'] = '100';
		}
			
		if (isset($params['social_login_by_tejas_rana_enable'])) {
			$data['status'] = $params['social_login_by_tejas_rana_enable'];
		} elseif (!empty($module_info)) {
			$data['status'] = isset($module_info['social_login_by_tejas_rana_enable']) ? $module_info['social_login_by_tejas_rana_enable'] : '';
		} else {
			$data['status'] = 0;
		}
					
		if (isset($params['social_login_by_tejas_rana_fbstatus'])) {
			$data['fbstatus'] = $params['social_login_by_tejas_rana_fbstatus'];
		} elseif (!empty($module_info)) {
			$data['fbstatus'] = isset($module_info['social_login_by_tejas_rana_fbstatus']) ? $module_info['social_login_by_tejas_rana_fbstatus'] : '';
		} else {
			$data['fbstatus'] = '';
		}
		
		if (isset($params['social_login_by_tejas_rana_twitstatus'])) {
			$data['twitstatus'] = $params['social_login_by_tejas_rana_twitstatus'];
		} elseif (!empty($module_info)) {
			$data['twitstatus'] = isset($module_info['social_login_by_tejas_rana_twitstatus']) ? $module_info['social_login_by_tejas_rana_twitstatus'] : '';
		} else {
			$data['twitstatus'] = '';
		}
		
		if (isset($params['social_login_by_tejas_rana_googlestatus'])) {
			$data['googlestatus'] = $params['social_login_by_tejas_rana_googlestatus'];
		} elseif (!empty($module_info)) {
			$data['googlestatus'] = isset($module_info['social_login_by_tejas_rana_googlestatus']) ? $module_info['social_login_by_tejas_rana_googlestatus'] : '';
		} else {
			$data['googlestatus'] = '';
		}
		
		if (isset($params['social_login_by_tejas_rana_linkstatus'])) {
			$data['linkstatus'] = $params['social_login_by_tejas_rana_linkstatus'];
		} elseif (!empty($module_info)) {
			$data['linkstatus'] = isset($module_info['social_login_by_tejas_rana_linkstatus']) ? $module_info['social_login_by_tejas_rana_linkstatus'] : '';
		} else {
			$data['linkstatus'] = '';
		}
		
		if (isset($params['social_login_by_tejas_rana_fbimage'])) {
			$data['fbimage'] = $params['social_login_by_tejas_rana_fbimage'];
		} elseif (!empty($module_info)) {
			$data['fbimage'] = isset($module_info['social_login_by_tejas_rana_fbimage']) ? $module_info['social_login_by_tejas_rana_fbimage'] : '';
		} else {
			$data['fbimage'] = '';
		}
		
			
		if (isset($params['social_login_by_tejas_rana_twitimage'])) {
			$data['twitimage'] = $params['social_login_by_tejas_rana_twitimage'];
		} elseif (!empty($module_info)) {
			$data['twitimage'] = isset($module_info['social_login_by_tejas_rana_twitimage']) ? $module_info['social_login_by_tejas_rana_twitimage'] : '';
		} else {
			$data['twitimage'] = '';
		}
		
		
		if (isset($params['social_login_by_tejas_rana_googleimage'])) {
			$data['googleimage'] = $params['social_login_by_tejas_rana_googleimage'];
		} elseif (!empty($module_info)) {
			$data['googleimage'] = isset($module_info['social_login_by_tejas_rana_googleimage']) ? $module_info['social_login_by_tejas_rana_googleimage'] : '';
		} else {
			$data['googleimage'] = '';
		}
	
		if (isset($params['social_login_by_tejas_rana_linkdinimage'])) {
			$data['linkdinimage'] = $params['social_login_by_tejas_rana_linkdinimage'];
		} elseif (!empty($module_info)) {
			$data['linkdinimage'] = isset($module_info['social_login_by_tejas_rana_linkdinimage']) ? $module_info['social_login_by_tejas_rana_linkdinimage'] : '';
		} else {
			$data['linkdinimage'] = '';
		}
		
		if (isset($params['social_login_by_tejas_rana_fbapikey'])) {
			$data['fbapikey'] = $params['social_login_by_tejas_rana_fbapikey'];
		} elseif (!empty($module_info)) {
			$data['fbapikey'] = isset($module_info['social_login_by_tejas_rana_fbapikey']) ? $module_info['social_login_by_tejas_rana_fbapikey'] : '';
		} else {
			$data['fbapikey'] = '';
		}
		
		if (isset($params['social_login_by_tejas_rana_fbsecretapi'])) {
			$data['fbsecretapi'] = $params['social_login_by_tejas_rana_fbsecretapi'];
		} elseif (!empty($module_info)) {
			$data['fbsecretapi'] = isset($module_info['social_login_by_tejas_rana_fbsecretapi']) ? $module_info['social_login_by_tejas_rana_fbsecretapi'] : '';
		} else {
			$data['fbsecretapi'] = '';
		}
		
		if (isset($params['social_login_by_tejas_rana_twitapikey'])) {
			$data['twitapikey'] = $params['social_login_by_tejas_rana_twitapikey'];
		} elseif (!empty($module_info)) {
			$data['twitapikey'] = isset($module_info['social_login_by_tejas_rana_twitapikey']) ? $module_info['social_login_by_tejas_rana_twitapikey'] : '';
		} else {
			$data['twitapikey'] = '';
		}
		
		if (isset($params['social_login_by_tejas_rana_twitsecretapi'])) {
			$data['twitsecretapi'] = $params['social_login_by_tejas_rana_twitsecretapi'];
		} elseif (!empty($module_info)) {
			$data['twitsecretapi'] = isset($module_info['social_login_by_tejas_rana_twitsecretapi']) ? $module_info['social_login_by_tejas_rana_twitsecretapi'] : '';
		} else {
			$data['twitsecretapi'] = '';
		}
		
		if (isset($params['social_login_by_tejas_rana_googleapikey'])) {
			$data['googleapikey'] = $params['social_login_by_tejas_rana_googleapikey'];
		} elseif (!empty($module_info)) {
			$data['googleapikey'] = isset($module_info['social_login_by_tejas_rana_googleapikey']) ? $module_info['social_login_by_tejas_rana_googleapikey'] : '';
		} else {
			$data['googleapikey'] = '';
		}
		
		if (isset($params['social_login_by_tejas_rana_googlesecretapi'])) {
			$data['googlesecretapi'] = $params['social_login_by_tejas_rana_googlesecretapi'];
		} elseif (!empty($module_info)) {
			$data['googlesecretapi'] = isset($module_info['social_login_by_tejas_rana_googlesecretapi']) ? $module_info['social_login_by_tejas_rana_googlesecretapi'] : '';
		} else {
			$data['googlesecretapi'] = '';
		}
		if (isset($params['social_login_by_tejas_rana_linkdinapikey'])) {
			$data['linkdinapikey'] = $params['social_login_by_tejas_rana_linkdinapikey'];
		} elseif (!empty($module_info)) {
			$data['linkdinapikey'] = isset($module_info['social_login_by_tejas_rana_linkdinapikey']) ? $module_info['social_login_by_tejas_rana_linkdinapikey'] : '';
		} else {
			$data['linkdinapikey'] = '';
		}
		
		if (isset($params['social_login_by_tejas_rana_linkdinsecretapi'])) {
			$data['linkdinsecretapi'] = $params['social_login_by_tejas_rana_linkdinsecretapi'];
		} elseif (!empty($module_info)) {
			$data['linkdinsecretapi'] = isset($module_info['social_login_by_tejas_rana_linkdinsecretapi']) ? $module_info['social_login_by_tejas_rana_linkdinsecretapi'] : '';
		} else {
			$data['linkdinsecretapi'] = '';
		}
		
		
		$this->load->model('tool/image');

		if (isset($params['social_login_by_tejas_rana_fbimage']) && is_file(DIR_IMAGE . $params['social_login_by_tejas_rana_fbimage'])) {
			$data['fbthumb'] = $this->model_tool_image->resize($params['social_login_by_tejas_rana_fbimage'], 100, 100);
		} elseif (!empty($module_info) && is_file(DIR_IMAGE . $module_info['social_login_by_tejas_rana_fbimage'])) {
			$data['fbthumb'] = $this->model_tool_image->resize($module_info['social_login_by_tejas_rana_fbimage'], 100, 100);
		} else {
			$data['fbthumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($params['social_login_by_tejas_rana_twitimage']) && is_file(DIR_IMAGE . $params['social_login_by_tejas_rana_twitimage'])) {
			$data['twiterthumb'] = $this->model_tool_image->resize($params['social_login_by_tejas_rana_twitimage'], 100, 100);
		} elseif (!empty($module_info) && is_file(DIR_IMAGE . $module_info['social_login_by_tejas_rana_twitimage'])) {
			$data['twiterthumb'] = $this->model_tool_image->resize($module_info['social_login_by_tejas_rana_twitimage'], 100, 100);
		} else {
			$data['twiterthumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($params['social_login_by_tejas_rana_googleimage']) && is_file(DIR_IMAGE . $params['social_login_by_tejas_rana_googleimage'])) {
			$data['googlethumb'] = $this->model_tool_image->resize($params['social_login_by_tejas_rana_googleimage'], 100, 100);
		} elseif (!empty($module_info) && is_file(DIR_IMAGE . $module_info['social_login_by_tejas_rana_googleimage'])) {
			$data['googlethumb'] = $this->model_tool_image->resize($module_info['social_login_by_tejas_rana_googleimage'], 100, 100);
		} else {
			$data['googlethumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}


		if (isset($params['social_login_by_tejas_rana_linkdinimage']) && is_file(DIR_IMAGE . $params['social_login_by_tejas_rana_linkdinimage'])) {
			$data['linkdinthumb'] = $this->model_tool_image->resize($params['social_login_by_tejas_rana_linkdinimage'], 100, 100);
		} elseif (!empty($module_info) && is_file(DIR_IMAGE . $module_info['social_login_by_tejas_rana_linkdinimage'])) {
			$data['linkdinthumb'] = $this->model_tool_image->resize($module_info['social_login_by_tejas_rana_linkdinimage'], 100, 100);
		} else {
			$data['linkdinthumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/social_login_by_tejas_rana', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/social_login_by_tejas_rana')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$params	= $this->request->post['social_login_by_tejas_rana'];

		if ((utf8_strlen($params['social_login_by_tejas_rana_name']) < 3) || (utf8_strlen($params['social_login_by_tejas_rana_name']) > 64)) {
			$this->error['social_login_by_tejas_rana_name'] = $this->language->get('error_name');
		}
		
		if(!empty($params['social_login_by_tejas_rana_fbstatus']) && $params['social_login_by_tejas_rana_fbstatus']==1) {
			
			if(empty($params['social_login_by_tejas_rana_fbapikey'])) {
				$this->error['fbapikey'] = $this->language->get('error_fbapikey');
			}
				
			if(empty($params['social_login_by_tejas_rana_fbsecretapi'])) {
				$this->error['fbsecretapi'] = $this->language->get('error_fbsecretapi');
			}
		
		}
		if(!empty($params['social_login_by_tejas_rana_twitstatus']) && $params['social_login_by_tejas_rana_twitstatus']==1) {
			if(empty($params['social_login_by_tejas_rana_twitapikey'])) {
				$this->error['twitapikey'] = $this->language->get('error_twitapikey');
			}
			
			if(empty($params['social_login_by_tejas_rana_twitsecretapi'])) {
				$this->error['twitsecretapi'] = $this->language->get('error_twitsecret');
			}
		}
		if(!empty($params['social_login_by_tejas_rana_googlestatus']) && $params['social_login_by_tejas_rana_googlestatus']==1) {
			if(empty($params['social_login_by_tejas_rana_googleapikey'])) {
				$this->error['googleapikey'] = $this->language->get('error_googleapikey');
			}
			if(empty($params['social_login_by_tejas_rana_googlesecretapi'])) {
				$this->error['googlesecretapi'] = $this->language->get('error_googlesecret');
			}
		}
		
		if(!empty($params['social_login_by_tejas_rana_linkstatus']) && $params['social_login_by_tejas_rana_linkstatus']==1) {
			if(empty($params['social_login_by_tejas_rana_linkdinapikey'])) {
				$this->error['linkdinapikey'] = $this->language->get('error_linkdinapikey');
			}
			
			if(empty($params['social_login_by_tejas_rana_linkdinsecretapi'])) {
				$this->error['linkdinsecretapi'] = $this->language->get('error_linkdinsecret');
			}
		}

		if (!empty($params['social_login_by_tejas_rana_width'])) {
			if (!is_numeric($params['social_login_by_tejas_rana_width'])) {
				$this->error['error_width'] = $this->language->get('error_width');
			}
		}

		if (!empty($params['social_login_by_tejas_rana_height'])) {
			if (!is_numeric($params['social_login_by_tejas_rana_height'])) {
				$this->error['error_height'] = $this->language->get('error_height');
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
	

		return !$this->error;
	}

	function install() {
		$this->load->model('setting/setting');
		$this->load->model('setting/module');

		$data	= array(
			'social_login_by_tejas_rana_name'				=> 'Social Login By Tejas Rana',
			'social_login_by_tejas_rana_width'				=> 130,
			'social_login_by_tejas_rana_height'				=> 35,
			'social_login_by_tejas_rana_button'				=> 'icon',
			'social_login_by_tejas_rana_enable'				=> 1,
			'social_login_by_tejas_rana_popuplogin'			=> 1,
			'social_login_by_tejas_rana_fbtitle'			=> 'Facebook Login',
			'social_login_by_tejas_rana_fbimage'			=> 'catalog/sociallogin/fb.png',
			'social_login_by_tejas_rana_fbapikey'			=> 'Add-Your-Facebook-App-ID',
			'social_login_by_tejas_rana_fbsecretapi'		=> 'Add-Facebook-App-Secret',
			'social_login_by_tejas_rana_fbstatus'			=> 1,
			'social_login_by_tejas_rana_twittertitle'		=> 'Twitter Login',
			'social_login_by_tejas_rana_twitimage'			=> 'catalog/sociallogin/twitter.png',
			'social_login_by_tejas_rana_twitapikey'			=> 'Add-Your-Consumer-Key',
			'social_login_by_tejas_rana_twitsecretapi'		=> 'Add-Your-Consumer-Secret',
			'social_login_by_tejas_rana_twitstatus'			=> 1,
			'social_login_by_tejas_rana_googletitle'		=> 'Google Login',
			'social_login_by_tejas_rana_googleimage'		=> 'catalog/sociallogin/google.png',
			'social_login_by_tejas_rana_googleapikey'		=> 'Add-Google-Project-Client-ID',
			'social_login_by_tejas_rana_googlesecretapi'	=> 'Add-Google-Project-Client-Secret',
			'social_login_by_tejas_rana_googlestatus'		=> 1,
			'social_login_by_tejas_rana_linkedintitle'		=> 'Linkedin Login',
			'social_login_by_tejas_rana_linkdinimage'		=> 'catalog/sociallogin/linkedin.png',
			'social_login_by_tejas_rana_linkdinapikey'		=> 'Add-Your-LinkedIn-Client-ID',
			'social_login_by_tejas_rana_linkdinsecretapi'	=> 'Add-Your-LinkedIn-Client-Secret',
			'social_login_by_tejas_rana_linkstatus'			=> 1
		);
		$data_module = array_merge($data, array('name'=>$data['social_login_by_tejas_rana_name'], 'status'=>$data['social_login_by_tejas_rana_enable']));
		$this->model_setting_setting->editSetting('social_login_by_tejas_rana', $data);
		$this->model_setting_module->addModule('social_login_by_tejas_rana', $data_module);
	}

	function uninstall() {
		$this->load->model('setting/setting');
		$this->load->model('setting/module');
		$this->model_setting_setting->deleteSetting('social_login_by_tejas_rana');
		$this->model_setting_module->deleteModulesByCode('social_login_by_tejas_rana');
	}
}