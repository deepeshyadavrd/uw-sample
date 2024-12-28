<?php

class ControllerHomeCarousel extends Controller{

     public function index(){

      

        return $this->load->view('home/carousel', $data);

     }

}

?>