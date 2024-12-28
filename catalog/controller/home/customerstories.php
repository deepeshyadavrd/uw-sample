<?php
class ControllerHomeCustomerstories extends Controller{
      public function index(){
        $this->load->model('tool/image');
              $result = $this->db->query("SELECT * FROM oc_review WHERE status=1  ORDER BY review_id DESC LIMIT 10");
               if(!empty($result)){
                $html = '<div class="container container-custom ">
                 <div class="row">
                 <div class="col-lg-3 col-md-12">
                     <h2 class="pg-heading text-lg-start text-center mt-lg-5">Customer Stories</h2>
                     <p class="heading-para text-lg-start text-center">Crafted to Perfection, Cherished by All! Experience the Rave-Worthy Journey from Website to Doorstep. Join Us for Exquisite, Custom Furniture Experiences.
                     </p>
                     
                 </div>
                 <div class="col-lg-9 col-md-12">
                     <section class="owl-carousel owl-theme owl-loaded owl-drag">';
                foreach ($result->rows as $row) {
        
                   if($row['rating']){
                      $ratstr = '';
                      for ($i=0; $i < $row['rating']; $i++) { 
                         $ratstr .="<span><i class='bx bxs-star'></i></span>";
                      }
                   }
                   $image = $this->model_tool_image->resize($row['image'], 244,233); 
                //  echo $row['image'];
                   $html .= '<div class="owl-item"><div class=" client-box">

                            <div class="client-img d-flex flex-column align-items-center justify-content-center">
                                <img src="http://localhost/opencartpro/image/'. $row['image'] . '" class="img-responsive img-fluid " loading="lazy" alt="'.ucfirst($row['author']).'">
                            </div>
                            <div class="client-content">
                                <div class="client-name">
                                    <p>'.ucfirst($row['author']).'</p>
                                </div>
                                <div class="client-comment">
                                    <p>'.ucfirst($row['text']).'</p>
                                    <div class="rating">'.$ratstr.'</div>
                                    <a href="'.$this->url->link('product/product',  '&product_id=' . $row['product_id'],true).'" type="button" class="see-product-btn">See product</a>
                                </div>
                            </div>
        
                        </div></div>';
                }
               }
                    $html .= '</section>
                </div>
            </div>
        
        </div>';
        echo $html;

     }
}
?>