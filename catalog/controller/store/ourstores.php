<?php
class ControllerStoreOurstores extends Controller{
     public function index(){

        $this->load->model('localisation/location');

        $data['locationList'] = array();
        foreach((array)$this->config->get('config_location') as $location_id) {
        	$location_info = $this->model_localisation_location->getLocation($location_id);
        
        	if ($location_info) {
        		if ($location_info['image']) {
        			$image = $this->model_tool_image->resize($location_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_location_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_location_height'));
        		} else {
        			$image = false;
        		}
            
        		$data['locationList'][] = array(
        			'location_id' => $location_info['location_id'],
        			'name'        => $location_info['name'],
                    'address'     => nl2br($location_info['address']),
					'geocode'     => $location_info['geocode'],
					'telephone'   => $location_info['telephone'],
					'fax'         => $location_info['fax'],
					'image'       => $image,
					'open'        => nl2br($location_info['open']),
					'comment'     => $location_info['comment']
        		);
        	}
        }

        $data['header'] = $this->load->controller('common/header');  
        $data['footer'] = $this->load->controller('common/footer');  
        $data['menu'] = $this->load->controller('common/menu');  

        $this->response->setOutput($this->load->view('store/ourstores', $data));
    }
}