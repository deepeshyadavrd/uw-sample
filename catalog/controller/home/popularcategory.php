<?php
class ControllerHomePopularcategory extends Controller{
     public function index(){

        
        return $this->load->view('home/popularcategory', $data);
     }
}
?>