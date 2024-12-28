<?php
class ControllerHomeTalktoexpert extends Controller{
     public function index(){

        
        return $this->load->view('home/talktoexpert', $data);
     }
}
?>