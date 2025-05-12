<?php
class ControllerSaleCustomOrderStatus extends Controller {
    private $error = array();
private $shipped_status_id;
    public function index() {
        $this->load->language('sale/custom_order_status');
        $this->document->setTitle($this->language->get('heading_title'));
        
        // Check permissions
        if (!$this->user->hasPermission('modify', 'sale/order')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
            $this->load->model('sale/order');
            $order_info = $this->model_sale_order->getOrder($order_id);
            if ($order_info) {
                $data['order_id'] = $order_id;
                $data['current_order_status_id'] = $order_info['order_status_id'];
            }
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            $this->load->model('sale/order'); // Use custom model
            $this->model_sale_order->addOrderHistory(
            $this->request->post['order_id'],
            $this->request->post['order_status_id'],
            isset($this->request->post['tracking_id']) ? trim($this->request->post['tracking_id']) : '',true);
            
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'], true));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['entry_order_id'] = $this->language->get('entry_order_id');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['button_submit'] = $this->language->get('button_submit');
        
        // Status dropdown
        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->shipped_status_id = null;
        foreach ($data['order_statuses'] as $status) {
            if (trim(strtolower($status['name'])) === 'shipped') {
                $this->shipped_status_id = $status['order_status_id'];
                break;
            }
        }
        $data['shipped_status_id'] = $this->shipped_status_id;

        // Error handling
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('sale/custom_order_status', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');
        $data['column_left'] = $this->load->controller('common/column_left');

        $this->response->setOutput($this->load->view('sale/custom_order_status_form', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'sale/custom_order_status')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->post['order_id']) || !$this->request->post['order_id']) {
            $this->error['warning'] = $this->language->get('error_order_id');
        }
        if (isset($this->request->post['order_status_id']) && 
            $this->request->post['order_status_id'] == $this->shipped_status_id &&
            empty(trim($this->request->post['tracking_id']))) {
            $this->error['warning'] = $this->language->get('error_tracking_id');
        }

        return !$this->error;
    }
}
