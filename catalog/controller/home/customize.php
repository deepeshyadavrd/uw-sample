<?php
class ControllerHomeCustomize extends Controller{
     public function index(){

        
        return $this->load->view('home/customize', $data);
     }
}
?>