<?php
class ControllerMarketingCampaign extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('marketing/marketing');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('marketing/campaign');

		$this->getList();
	}

	public function add() {
		$this->load->language('marketing/marketing');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('marketing/campaign');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_marketing_campaign->addMarketing($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_code'])) {
				$url .= '&filter_code=' . $this->request->get['filter_code'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('marketing/campaign', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('marketing/marketing');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('marketing/campaign');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_marketing_campaign->editMarketing($this->request->get['campaign_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_code'])) {
				$url .= '&filter_code=' . $this->request->get['filter_code'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('marketing/campaign', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('marketing/marketing');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('marketing/campaign');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $campaign_id) {
				$this->model_marketing_campaign->deleteMarketing($campaign_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_code'])) {
				$url .= '&filter_code=' . $this->request->get['filter_code'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('marketing/campaign', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_code'])) {
			$filter_code = $this->request->get['filter_code'];
		} else {
			$filter_code = '';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

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
			'text' => 'Campaign Track',
			'href' => $this->url->link('marketing/campaign', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('marketing/campaign/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('marketing/campaign/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['marketings'] = array();

		$filter_data = array(
			'filter_name'       => $filter_name,
			'filter_code'       => $filter_code,
			'filter_date_added' => $filter_date_added,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$marketing_total = $this->model_marketing_campaign->getTotalMarketings($filter_data);

		$results = $this->model_marketing_campaign->getMarketings($filter_data);

		foreach ($results as $result) {
			$data['marketings'][] = array(
				'campaign_id' => $result['campaign_id'],
				'campaign_name'         => $result['campaign_name'],
				'utm_source'         => $result['utm_source'],
				'clicks'       => $result['clicks'],
				'orders'       => $result['orders'],
				'total_orders'       => $result['total_orders'],
				'date_added'   => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'         => $this->url->link('marketing/campaign/edit', 'user_token=' . $this->session->data['user_token'] . '&campaign_id=' . $result['campaign_id'] . $url, true)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('marketing/campaign', 'user_token=' . $this->session->data['user_token'] . '&sort=m.campaign_name' . $url, true);
		$data['sort_code'] = $this->url->link('marketing/campaign', 'user_token=' . $this->session->data['user_token'] . '&sort=m.utm_source' . $url, true);
		$data['sort_date_added'] = $this->url->link('marketing/campaign', 'user_token=' . $this->session->data['user_token'] . '&sort=m.date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $marketing_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('marketing/campaign', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($marketing_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($marketing_total - $this->config->get('config_limit_admin'))) ? $marketing_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $marketing_total, ceil($marketing_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_code'] = $filter_code;
		$data['filter_date_added'] = $filter_date_added;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/campaign_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['campaign_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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

		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . $this->request->get['filter_code'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

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
			'text' =>'Campaign Track',
			'href' => $this->url->link('marketing/campaign', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['campaign_id'])) {
			$data['action'] = $this->url->link('marketing/campaign/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('marketing/campaign/edit', 'user_token=' . $this->session->data['user_token'] . '&campaign_id=' . $this->request->get['campaign_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('marketing/campaign', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['campaign_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$marketing_info = $this->model_marketing_campaign->getMarketing($this->request->get['campaign_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['store'] = HTTP_CATALOG;

		if (isset($this->request->post['campaign_name'])) {
			$data['campaign_name'] = $this->request->post['campaign_name'];
		} elseif (!empty($marketing_info)) {
			$data['campaign_name'] = $marketing_info['campaign_name'];
		} else {
			$data['campaign_name'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($marketing_info)) {
			$data['description'] = $marketing_info['description'];
		} else {
			$data['description'] = '';
		}

		if (isset($this->request->post['utm_source'])) {
			$data['utm_source'] = $this->request->post['utm_source'];
		} elseif (!empty($marketing_info)) {
			$data['utm_source'] = $marketing_info['utm_source'];
		} else {
			$data['utm_source'] = uniqid();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/campaign_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'marketing/campaign')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['campaign_name']) < 1) || (utf8_strlen($this->request->post['campaign_name']) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['utm_source']) {
			$this->error['code'] = $this->language->get('error_code');
		}

		$marketing_info = $this->model_marketing_campaign->getMarketingByCode($this->request->post['utm_source']);

		if (!isset($this->request->get['campaign_id'])) {
			if ($marketing_info) {
				$this->error['code'] = $this->language->get('error_exists');
			}
		} else {
			if ($marketing_info && ($this->request->get['campaign_id'] != $marketing_info['campaign_id'])) {
				$this->error['code'] = $this->language->get('error_exists');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'marketing/campaign')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function getReport(){
		$this->load->model('marketing/campaign');

		$campid = $this->request->get['campid'];
		$startDate = $this->request->get['start_date'];
		$endDate = $this->request->get['end_date'];
		$marketing_info = $this->model_marketing_campaign->getReport($this->request->post);

		print_r(json_encode($marketing_info));
	}
}