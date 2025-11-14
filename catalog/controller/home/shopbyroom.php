<?php
class ControllerHomeShopbyroom extends Controller{
     public function index(){

        
        return $this->load->view('home/shopbyroom', $data);
     }
}
?>