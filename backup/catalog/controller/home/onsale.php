<?php
class ControllerHomeOnsale extends Controller{
     public function index(){

        
        return $this->load->view('home/onsale', $data);
     }
}
?>