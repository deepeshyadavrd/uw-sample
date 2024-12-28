<?php
class ControllerStoreCustomerstories extends Controller{
     public function index(){
         
         $this->load->model('catalog/review');

         $customer_stories = $this->model_catalog_review->customerStories();
         //print_r($data['customer_stories']);
         $data['customer_stories'] = array();

         foreach($customer_stories as $customerstory){
            $data['customer_stories'][] = array(
                  'author' => $customerstory['author'],
                  'text' => $customerstory['text'],
                  'rating' => $customerstory['rating'],
                  'city' => $customerstory['city'],
                  'image' => $customerstory['image'],
                  'href' => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $customerstory['product_id'])
            );
         }

         $data['header'] = $this->load->controller('common/header');  
         $data['footer'] = $this->load->controller('common/footer');  
          
        
         return $this->load->view('store/customerstories', $data);
     }
}
?>