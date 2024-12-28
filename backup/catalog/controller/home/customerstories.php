<?php

class ControllerHomeCustomerstories extends Controller{

     public function index(){


       $result = $this->db->query("SELECT * FROM oc_review WHERE status=1  ORDER BY review_id DESC LIMIT 10");
        if(!empty($result)){
       
         $this->load->model('tool/image');
         foreach ($result->rows as $row) {

            if($row['rating']){
               $ratstr = '';
               for ($i=0; $i < $row['rating']; $i++) { 
                  $ratstr .="<span><i class='bx bxs-star'></i></span>";
               }
            }
            $image = $this->model_tool_image->resize($row['image'], 244,233); 
            $ardata[] = array(
               'title'=>ucfirst($row['title']),
               'author'=>ucfirst($row['author']),
               'text'=>ucfirst($row['text']),
               'rating'=>$ratstr,
               'image'=>$image,
               'href' => $this->url->link('product/product',  '&product_id=' . $row['product_id'],true)
            );
         }
        }
       $data['reviews'] = $ardata;
       // print_r($ardata);exit;
       return $this->load->view('home/customerstories', $data);

     }

}

?>