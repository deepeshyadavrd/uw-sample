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
                     <div class="swiffy-slider slider-item-show2 slider-item-reveal slider-nav-outside slider-nav-round slider-nav-visible slider-indicators-outside slider-indicators-round slider-indicators-dark slider-nav-animation slider-nav-animation-fadein slider-item-first-visible ">
    <ul class="slider-container py-4">';
                foreach ($result->rows as $row) {
        
                   if($row['rating']){
                      $ratstr = '';
                      for ($i=0; $i < $row['rating']; $i++) { 
                         $ratstr .="<span><i class='bx bxs-star'></i></span>";
                      }
                   }
                   $image = $this->model_tool_image->resize($row['image'], 244,233); 
                //  echo $row['image'];
                   $html .= '<li class="slide-visible">
        <div class="card shadow h-100">
                <div class="ratio ratio-16x9">
                    <img src="http://localhost/opencartpro/image/'. $row['image'] . '" class="card-img-top" loading="lazy" alt="'. ucfirst($row['author']) . '">
                </div>
                <div class="card-body p-3 p-xl-5">
                    <h3 class="card-title h5">'. ucfirst($row['author']) . '</h3>
                    <p class="card-text">'. ucfirst($row['text']) . '</p>
                    <div class="rating">
                               '. $ratstr . '
                            </div>
                    <div><a href="{{review.href}}" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
    </li>';
                }
               }
                    $html .= '</ul>

    <button type="button" class="slider-nav" aria-label="Go to previous"></button>
    <button type="button" class="slider-nav slider-nav-next" aria-label="Go to next"></button>

</div></div>
</div></div>';
        echo $html;

     }
}
?>