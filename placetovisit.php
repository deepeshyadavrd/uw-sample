      <!-- <section class="pt-5 pb-5 position-relative dark-caption" style="background: url('images/slider-4.jpg') no-repeat; background-size: cover; background-position: center;">
        <div class="container">
         <div class="row d-flex justify-content-center">                
            <div class="col-12">
              <div class="section-heading-1 mb-5 mt-5 text-white">
                <h1 class="text-white">Delhi Incrediable Package</h1>                
                  <p class="mb-0"><i class="fa fa-map-marker text-theme mr-1" aria-hidden="true"></i> New Delhi <span class="mr-2 ml-2">|</span> <i class="fa fa-calendar text-theme mr-1" aria-hidden="true"></i> 8 Nights / 9 Days</p>
              </div>
            </div>
          </div>
        </div>
      </section> -->

      <section class="page-breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url() ?>">Home</a></li>
                             <li class="breadcrumb-item"><a href="<?php echo base_url('destinations') ?>">Places to visit</a></li>
                            <li class="breadcrumb-item" aria-current="page"><?php echo $record->name; ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

      <section class="pt-5 pb-5 f2f2f2-bg p-detail">
      <div class="container">       
            <div class="main_detail_frame"> 

              <div class="row table-row">
                <div class="col-md-8 col-sm-12 col-12 left_detail_frame">
                  <div class="package-detail">
                    <div class="row">
                       <div class="col-sm-12">
                          <div class="detail_view_wrp">
                             <div class="package-title-card">
                              <h2 class="box-offer-title mb-10"><?php echo $record->name; ?></h2>
                              
                            </div>
                             <div class="tour_slider mt-20">                             
                                <div class="fotorama" data-nav="thumbs" data-allowfullscreen="native"> <?php if(!empty($slider_record)){
                                  foreach ($slider_record as $key => $slider) { 
                                  $images = galleryThumbURL($slider->image,$slider->type,$slider->type_id,1000,1000);
                                    ?>
                                 
                                  <a href="#"><img src="<?php echo $images; ?>" class="img-fluid w-100" alt=""></a>
                                <?php  } } ?>
                                  
                                </div>
                            </div> 

                             
                              <div class="paragraph mb-4" id="overview">
                                <p><?php echo $record->description; ?></p>
                              </div>
                              <?php echo $attractions; ?>
                            </div>
                        </div>
                    </div>

                  </div>
                </div>

                <div class="col-md-4 col-sm-12 col-12 mob-hide right_detail_frame">
                  <div class="right_widgets">
                    

                    <?php echo $enquiry_form; ?>

                  
                  </div>
                </div>
              </div>
            </div>
      </div>
    </section>

          <?php echo $similar_tour; ?>
       <?php echo $similar_camps; ?>
                 
         <section class="kode-newsletters-2 pt-5 pb-5 position-relative dark-caption" style="background: url('images/news2-bg.jpg') no-repeat; background-size: cover; background-position: center;">
            <div class="container">
               <div class="row">
                  <div class="col-12 text-center">
                     <div class="section-heading-1 mb-5 text-white">
                           <h3 class="text-white"><strong>Subscribe Our Newsletter</strong></h3>
                          <p>Subscribe here with your email us and get up to date.</p>
                      </div>
                  </div>
               </div>
               <div class="newsletters-container">
                  <div class="row">
                     <div class="col-md-9 col-sm-8">
                        <div class="input-container">
                           <i class="fa fa-envelope-o"></i>
                           <input type="text" placeholder="Enter your Email">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4">
                     <button class="bg-theme b-lighttheme">Subscribe<i class="fa fa-paper-plane"></i></button>
                    </div>
                  </div>
               </div>
            </div>
        </section>
        