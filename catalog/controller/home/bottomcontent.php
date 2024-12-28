<?php
class ControllerHomeBottomcontent extends Controller{
     public function index(){

        
        return $this->load->view('home/bottomcontent', $data);
     }
}
?>