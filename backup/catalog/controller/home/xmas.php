<?php

class ControllerHomeXmas extends Controller{

  public function index(){
    $this->load->language('product/product');
    $this->load->model('catalog/product');

    if (isset($this->request->post['product_ids'])) {
      $product_ids = explode(',', $this->request->post['product_ids']);
   } else {
      $product_ids = 0;
   }

   if (isset($this->request->post['height'])) {
      $height = $this->request->post['height'];
   }else{
      $height = 1335;
   }
   if (isset($this->request->post['width'])) {
      $width = $this->request->post['width'];
   }else{
      $width = 1920;
   }
   $product_ids = ['2261','1561','454','2234','1931','1847','1861','299','349','1155','522', '749', '89'];
   $product_info = array();
   if(!empty($product_ids)){
      $this->load->model('tool/image');
      foreach ($product_ids as $product_id) {
         $result = $this->model_catalog_product->getProduct($product_id);

         if ($result['image']) {
            $image = $this->model_tool_image->resize($result['image'],430,300);
         } else {
            $image = $this->model_tool_image->resize('placeholder.png',638, 684);
         }

         if ((float)$result['price']) {
            $price = $this->currency->format($result['price'], $this->session->data['currency']);
         } else {
            $price = false;
         }

         if ((float)$result['special']) {
            $special = $this->currency->format($result['special'], $this->session->data['currency']);
         } else {
            $special = false;
         }
         $discount = (($result['price'] - $result['special'])/$result['price'])*100;
         $product_seo_url = '';
         $product_seo_url = $this->model_catalog_product->getProductSeoUrls($result['product_id']);
         $plink      =  'https://www.urbanwood.in/'.$product_seo_url[0][1];
         $data['products'][] = array(
            'product_id'  => $result['product_id'],
            'thumb'       => $image,
            'name'        => $result['name'],
            'priceR'   => $result['price'],
            'specialR'    => $result['special'],
            'yoursaving'  => $result['price']-$result['special'],
            'discount'    => round($discount),

            'price'       => $price,
            'special'     => $special,    
            'mrp'      => $price,
            'href'        => $plink
         );
      }

   }       

   // print_r($data);exit;

   return $this->load->view('home/xmas', $data);

}

}

?>