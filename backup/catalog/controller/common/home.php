<?php

class ControllerCommonHome extends Controller {

	public function index() {

		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}
      if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['topselling'] = $this->load->controller('home/topselling');
		$data['footer'] = $this->load->controller('common/footer');
		// $this->load->model('tool/image');

      // $homeBanner = $this->db->query('SELECT * FROM oc_banner_image WHERE banner_id=7 ORDER BY sort_order ASC');
      // if(!empty($homeBanner)){
      //    foreach ($homeBanner->rows as $key => $banner) {
      //       if ($banner['image']) {
      //          $image = $this->model_tool_image->resize($banner['image'],980,367);
      //       } else {
      //          $image = '';
      //       }
      //       $active = ($key==0)?'active':'';
      //       $data['banners'][] = array(
      //          'active'=>$active,
      //          'image' =>$image,
      //          'link'=>$banner['link'],
      //          'title'=>$banner['title']

      //       );
      //    }
      // }
		$this->response->setOutput($this->load->view('common/home', $data));

	}
	public function gettrending(){
		$this->load->model('catalog/product');
		$trending = $this->model_catalog_product->gettrending();
		foreach ($trending as $result) {
			$product_data[] = $result;
		}
		
		echo json_encode($product_data);
	}

	public function social(){
				echo '<div class="container container-custom">
		    <h2 class="pg-heading text-center my-lg-3">
		      <span class="" style="font-weight:200;">Follow us</span> on Instagram
		    </h2>
		  </div>
		  <div class="row row-1">
		    <div class="column-2">
		      <div class="img-box">
		        <a href="https://www.instagram.com/p/CxDB5HtP39o/?utm_source=ig_web_copy_link" target="_blank" id="zoomIn">
		            <img src="catalog/view/javascript/assets/image/instapost/post1.avif" alt="follow on insta for more trendy shoe rack">
		          <i class="bx bxl-instagram"></i>
		        </a>
		      </div>
		      <div class="img-box">
		        <a href="https://www.instagram.com/p/CiUGWRBIrZx/?utm_source=ig_web_copy_link" target="_blank" id="zoomIn">
		            <img src="catalog/view/javascript/assets/image/instapost/barcabinet.avif" alt="follow on insta for more sofa sets">
		          <i class="bx bxl-instagram"></i>
		        </a>
		      </div>
		      <div class="img-box">
		        <a href="https://www.instagram.com/p/CfGR5dyNo4k/?utm_source=ig_web_copy_link" target="_blank" id="zoomIn">
		            <img src="catalog/view/javascript/assets/image/instapost/bed.avif" alt="follow on insta for more wardrobes">
		          <i class="bx bxl-instagram"></i>
		        </a>
		      </div>
		      <div class="img-box">
		        <a href="https://www.instagram.com/p/CwXJ_M6v8lN/?utm_source=ig_web_copy_link" target="_blank" id="zoomIn">
		            <img src="catalog/view/javascript/assets/image/instapost/calinshoerack2.avif" alt="follow on insta for more shoe racks">
		          <i class="bx bxl-instagram"></i>
		        </a>
		      </div>
		      <div class="img-box">
		        <a href="https://www.instagram.com/p/CwXJ_M6v8lN/?utm_source=ig_web_copy_link" target="_blank" id="zoomIn">
		            <img src="catalog/view/javascript/assets/image/instapost/25-8-23.avif" alt="follow on insta for more sofa cum beds">
		          <i class="bx bxl-instagram"></i>
		        </a>
		      </div>
		      <div class="img-box">
		        <a href="https://www.instagram.com/p/CxFqQeLvVwM/?utm_source=ig_web_copy_link" target="_blank" id="zoomIn">
		            <img src="catalog/view/javascript/assets/image/instapost/22-6-23.avif" alt="follow on insta for more platform beds">
		          <i class="bx bxl-instagram"></i>
		        </a>
		      </div>
		      <div class="img-box">
		        <a href="https://www.instagram.com/p/CsGB5qjvmvi/?utm_source=ig_web_copy_link" id="zoomIn" target="_blank">
		            <img src="catalog/view/javascript/assets/image/instapost//relaysinglebedwithdrawer.avif" alt="follow on insta for more beds">
		          <i class="bx bxl-instagram"></i>
		        </a>
		      </div>
		    </div>
		  </div>';
	}
}

