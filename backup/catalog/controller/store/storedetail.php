<?php
class ControllerStoreStoredetail extends Controller{
     public function index(){
        
        $data['header'] = $this->load->controller('common/header');  
        $data['footer'] = $this->load->controller('common/footer');  
          
        
        return $this->load->view('store/storedetail', $data);
     }
}
?>