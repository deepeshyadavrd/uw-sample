<?php
class ControllerDesignHomeSection extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/home_section');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/home_section');

		$this->getList();
	}

	public function add() {
		$this->load->language('design/home_section');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/home_section');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_home_section->addHomepageSection($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('design/home_section', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('design/home_section');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/home_section');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_home_section->editHomeSection($this->request->get['home_section_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('design/home_section', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('design/home_section');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/home_section');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $home_section_id) {
				$this->model_design_home_section->deleteHomeSection($home_section_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('design/home_section', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/home_section', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('design/home_section/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('design/home_section/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['home_sections'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$home_section_total = $this->model_design_home_section->getTotalHomepageSections();

		$results = $this->model_design_home_section->getHomeSections($filter_data);
		
		foreach ($results as $result) {
			$data['home_sections'][] = array(
				'home_section_id' => $result['home_section_id'],
				'name'      => $result['name'],
				'status'    => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'edit'      => $this->url->link('design/home_section/edit', 'user_token=' . $this->session->data['user_token'] . '&home_section_id=' . $result['home_section_id'] . $url, true)
			);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('design/home_section', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
		$data['sort_status'] = $this->url->link('design/home_section', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $home_section_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('design/home_section', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($home_section_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($home_section_total - $this->config->get('config_limit_admin'))) ? $home_section_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $home_section_total, ceil($home_section_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/home_section_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['home_section_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['home_section_image'])) {
			$data['error_home_section_image'] = $this->error['home_section_image'];
		} else {
			$data['error_home_section_image'] = array();
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/home_section', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['home_section_id'])) {
			$data['action'] = $this->url->link('design/home_section/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('design/home_section/edit', 'user_token=' . $this->session->data['user_token'] . '&home_section_id=' . $this->request->get['home_section_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('design/home_section', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['home_section_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$home_section_info = $this->model_design_home_section->getHomeSection($this->request->get['home_section_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($home_section_info)) {
			$data['name'] = $home_section_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($home_section_info)) {
			$data['status'] = $home_section_info['status'];
		} else {
			$data['status'] = true;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('tool/image');

		if (isset($this->request->post['home_section_image'])) {
			$home_section_images = $this->request->post['home_section_image'];
		} elseif (isset($this->request->get['home_section_id'])) {
			$home_section_images = $this->model_design_home_section->getHomeSectionImages($this->request->get['home_section_id']);
		} else {
			$home_section_images = array();
		}
		$data['home_section_images'] = array();

		foreach ($home_section_images as $key => $value) {
			foreach ($value as $home_section_image) {
				if (is_file(DIR_IMAGE . $home_section_image['image'])) {
					$image = $home_section_image['image'];
					$thumb = $home_section_image['image'];
				} else {
					$image = '';
					$thumb = 'no_image.png';
				}
				
				$data['home_section_images'][$key][] = array(
					'title'      => $home_section_image['title'],
					'link'       => $home_section_image['link'],
					'image'      => $image,
					'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
					'price'		 => $home_section_image['price'],
					'sort_order' => $home_section_image['sort_order']
				);
			}
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/home_section_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/home_section')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (isset($this->request->post['home_section_image'])) {
			foreach ($this->request->post['home_section_image'] as $language_id => $value) {
				foreach ($value as $home_section_image_id => $home_section_image) {
					if ((utf8_strlen($home_section_image['title']) < 2) || (utf8_strlen($home_section_image['title']) > 64)) {
						$this->error['home_section_image'][$language_id][$home_section_image_id] = $this->language->get('error_title');
					}
				}
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/home_section')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}