<?php
class ControllerTestAbc extends Controller{
     public function index(){

        $this->load->language('test/abc');

        $this->load->model('test/abc');
        $results = $this->model_test_abc->getProductByCategory(153);
        print_r($results);
        $data['text_welcome'] = $this->language->get('text_welcome');
        $data['text_para'] = $this->language->get('text_para');

        $data['header'] = $this->load->controller('common/header');  
        $data['footer'] = $this->load->controller('common/footer');  
        $data['menu'] = $this->load->controller('common/menu');  
          
        
        $this->response->setOutput($this->load->view('test/abc', $data));
     }
}
?>