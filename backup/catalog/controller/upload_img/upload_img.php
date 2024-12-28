<?php
class ControllerUploadImgUploadImg extends Controller {


    public function index(){
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $data['menu'] = $this->load->controller('common/menu');
        $this->load->model('tool/image');
        $this->load->model('upload_img/upload_img');

        $results = $this->model_upload_img_upload_img->getImg();

        
           
        
        foreach ($results as $result) {
            
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 421,292);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 100, 100);
			}

            if (is_file(DIR_IMAGE . $result['image'])) {
				$image2 = $this->model_tool_image->resize($result['image'], 1760,1340);
			} else {
				$image2 = $this->model_tool_image->resize('no_image.png', 100, 100);
			}
            // print_r($image);
            if($result['product_id'] != 0){
                $href = $this->url->link('product/product', '&product_id=' . $result['product_id'] );
            }else{
                $href = '';
            }
			$data['products'][] = array(
				'id' =>    $result['id'],
				'image'      => $image,
                'image2'      => $image2,
				'name'       => $result['name'],
                'product_id'       => $result['product_id'],
                'product_name'       => $this->model_upload_img_upload_img->product_name($result['product_id']),
                'href'        => $href
			
			);
            

            
		}

        // print_r($data['products']);
      
		
        // print_r($data);
        $this->response->setOutput($this->load->view('upload_img/upload_img', $data));
       
    }


   
    
   
}


