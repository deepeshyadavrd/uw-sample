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
		$this->load->model('tool/image');

      $homeBanner = $this->db->query('SELECT * FROM oc_banner_image WHERE banner_id=7 ORDER BY sort_order ASC');
      if(!empty($homeBanner)){
         foreach ($homeBanner->rows as $key => $banner) {



            if ($banner['image']) {
               $image = $this->model_tool_image->resize($banner['image'],1303,488);
            } else {
               $image = '';
            }
			// print_r($banner['image']);
            $active = ($key==0)?'active':'';


            $data['banners'][] = array(
               'active'=>$active,
               'image' =>$banner['image'],
               'link'=>$banner['link'],
               'title'=>$banner['title']

            );
         }
      }

		$data['menu'] = $this->load->controller('common/menu');
		$data['carousel'] = $this->load->controller('home/carousel');
		$data['quality'] = $this->load->controller('home/quality');
		$data['popularcategory'] = $this->load->controller('home/popularcategory');
		$data['talktoexpert'] = $this->load->controller('home/talktoexpert');
		$data['trending'] = $this->load->controller('home/trending');
		$data['shopbyroom'] = $this->load->controller('home/shopbyroom');
		$data['customize'] = $this->load->controller('home/customize');
		$data['topselling'] = $this->load->controller('home/topselling');
		$data['onsale'] = $this->load->controller('home/onsale');
		$data['stores'] = $this->load->controller('home/stores');
		$data['customerstories'] = $this->load->controller('home/customerstories');
		$data['social'] = $this->load->controller('home/social');
		$data['faq'] = $this->load->controller('home/faq');
		$data['bottomcontent'] = $this->load->controller('home/bottomcontent');
		// $data['register'] = $this->load->controller('account/register');
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
