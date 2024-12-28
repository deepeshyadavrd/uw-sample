<?php
class ControllerHomeFaq extends Controller{
     public function index(){

        
        return $this->load->view('home/faq', $data);
     }
}
?>