<?php
class ControllerCatalogUploadImg extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/upload_img');

		$this->getList();
	}
	public function bulkadd(){
		
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/upload_img');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			// Allowed mime types
			$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
			// Validate whether selected file is a CSV file
			if(!empty($this->request->files['file']['name']) && in_array($this->request->files['file']['type'], $csvMimes)){
				
				// If the file is uploaded
				if(is_uploaded_file($this->request->files['file']['tmp_name'])){
					
					// Open uploaded CSV file with read-only mode
					$csvFile = fopen($this->request->files['file']['tmp_name'], 'r');
					
					// Skip the first line
					$heads = fgetcsv($csvFile);
					$new = array();
					// Parse data from CSV file line by line
					while(($line = fgetcsv($csvFile)) !== FALSE){
						// Get row data

						$data['product_description'][1]['name']   = $line[0];
						$data['product_description'][1]['description'] = $line[1];
						$data['product_description'][1]['meta_title'] = $line[2];
						$data['product_description'][1]['meta_description'] = $line[3];
						$data['product_description'][1]['meta_keyword'] = $line[4];
            			$data['product_description'][1]['tag'] = $line[5];
						$data['model']  = $line[6];
						$data['sku']  = $line[7];
						$data['upc'] = $line[8];
						$data['ean'] = $line[9];
						$data['jan'] = $line[10];
						$data['isbn'] = $line[11];
						$data['mpn'] = $line[12];
						$data['location'] = $line[13];
						$data['price'] = $line[14];
						$data['tax_class'] = $line[15];
						$data['quantity'] = $line[16];
						$data['minimum'] = $line[17];
						$data['subtract'] = $line[18];
						$data['stock_status'] = $line[19];
						$data['shipping'] = $line[20];
						$data['date_available'] = $line[21];
						$data['length'] = $line[22];
						$data['width'] = $line[23];
						$data['height'] = $line[24];
						$data['length_class'] = $line[25];
						$data['weight'] = $line[26];
						$data['weight_class'] = $line[27];
						$data['status'] = $line[28];
						$data['sort_order'] = $line[29];
						$data['seller'] = $line[30];
						$data['seller'] = $line[31];
						
						//product category data
						$pro_cat = preg_split("/\,/", $line[31]);
						$data['product_category'] = $pro_cat;
						$pro_fil = preg_split("/\,/", $line[32]);
						$data['product_filter'] = $pro_fil;
						//product store data
						$pro_store = preg_split("/\,/", $line[33]);
						$data['product_store'] = $pro_store;

						$data['download'] = $line[34];
						$data['related'] = $line[35];
						$pro_attri_name = preg_split("/\,/", $line[37]);
						$data['product_attribute_name'] = $pro_attri_name;
						$pro_attri_des = preg_split("/\,/", $line[38]);
						$data['product_attribute_text'] = $pro_attri_des;
						
						foreach($data['product_attribute_name'] as $value){
							$new['name'] = $value;
							$data['product_attribute'][] = $new;
						}
						//options
						$data['option'] = $line[39];
						
						$data['product_option_name'] = $line[40];
						$data['product_option_required'] = $line[41];
						$option_value_name = preg_split("/\,/", $line[42]);
						$data['option_value_name'] = $option_value_name;

						$data['product_option'][0]['name'] = $data['product_option_name']; 
						$data['product_option'][0]['required'] = $data['product_option_required']; 
						//$data['product_option'][0]['product_option_value'] = $data['option_value_name']; 

						$option_quantity = preg_split("/\-/", $line[43]);
						$data['option_quantity'] = $option_quantity;

						$option_subtract = preg_split("/\,/", $line[44]);
						$data['option_subtract'] = $option_subtract;

						$option_price_prefix = preg_split("/\,/", $line[45]);
						$data['option_price_prefix'] = $option_price_prefix;

						$option_price = preg_split("/\-/", $line[46]);
						$data['option_price'] = $option_price;

						$option_point_prefix = preg_split("/\,/", $line[47]);
						$data['option_point_prefix'] = $option_point_prefix;

						$option_points = preg_split("/\-/", $line[48]);
						$data['option_points'] = $option_points;

						$option_weight_prefix = preg_split("/\,/", $line[49]);
						$data['option_weight_prefix'] = $option_weight_prefix;

						$option_weight = preg_split("/\-/", $line[50]);
						$data['option_weight'] = $option_weight;

						//option2
						$data['product_option_name2'] = $line[51];
						$data['product_option_required2'] = $line[52];
						$option_value_name2 = preg_split("/\,/", $line[53]);
						$data['option_value_name2'] = $option_value_name2;

						$data['product_option'][1]['name'] = $data['product_option_name2']; 
						$data['product_option'][1]['required'] = $data['product_option_required2']; 
						//$data['product_option'][0]['product_option_value'] = $data['option_value_name']; 

						$option_quantity2 = preg_split("/\-/", $line[54]);
						$data['option_quantity2'] = $option_quantity2;

						$option_subtract2 = preg_split("/\,/", $line[55]);
						$data['option_subtract2'] = $option_subtract2;

						$option_price_prefix2 = preg_split("/\,/", $line[56]);
						$data['option_price_prefix2'] = $option_price_prefix2;

						$option_price2 = preg_split("/\-/", $line[57]);
						$data['option_price2'] = $option_price2;

						$option_point_prefix2 = preg_split("/\,/", $line[58]);
						$data['option_point_prefix2'] = $option_point_prefix2;

						$option_points2 = preg_split("/\-/", $line[59]);
						$data['option_points2'] = $option_points2;

						$option_weight_prefix2 = preg_split("/\,/", $line[60]);
						$data['option_weight_prefix2'] = $option_weight_prefix2;

						$option_weight2 = preg_split("/\-/", $line[61]);
						$data['option_weight2'] = $option_weight2;

						
						$data['points'] = $line[70];
						//product reward data
						
						$data['product_reward'][1]['points'] = $line[71];
						//seo url data
						//$pro_seo_url = preg_split("/\,/", $line[63]);
						$data['product_seo_url'][0][0] = $line[72];
						//product layout data
						$pro_lay = preg_split ("/\,/", $line[73]); 
						$data['product_layout'] = $pro_lay;
												
						// Insert member data in the database
						$this->model_catalog_upload_img->addProductBulk($data);
						
					}
					
					// Close opened CSV file
					fclose($csvFile);
					if(!empty($this->request->files['file'])){
						$this->session->data['success'] = $this->language->get('text_success');
					}
					$qstring = '?status=succ';
				}else{
					$qstring = '?status=err';
				}
			}else{
				$qstring = '?status=invalid_file';
			}

			//$url = '';

			

			$this->response->redirect($this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'], true)); // . $url, true));
		}
		

		$this->getFormBulk();
	}
	public function add() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/upload_img');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_upload_img->addProduct($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			// if (isset($this->request->get['filter_name'])) {
			// 	$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			// }

			// if (isset($this->request->get['filter_model'])) {
			// 	$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			// }

			// if (isset($this->request->get['filter_price'])) {
			// 	$url .= '&filter_price=' . $this->request->get['filter_price'];
			// }

			// if (isset($this->request->get['filter_quantity'])) {
			// 	$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			// }

			// if (isset($this->request->get['filter_status'])) {
			// 	$url .= '&filter_status=' . $this->request->get['filter_status'];
			// }

			// if (isset($this->request->get['sort'])) {
			// 	$url .= '&sort=' . $this->request->get['sort'];
			// }

			// if (isset($this->request->get['order'])) {
			// 	$url .= '&order=' . $this->request->get['order'];
			// }

			// if (isset($this->request->get['page'])) {
			// 	$url .= '&page=' . $this->request->get['page'];
			// }

			$this->response->redirect($this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/upload_img');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_upload_img->editProduct($this->request->get['id'], $this->request->post);
			// print_r($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			// if (isset($this->request->get['filter_name'])) {
			// 	$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			// }

			// if (isset($this->request->get['filter_model'])) {
			// 	$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			// }

			// if (isset($this->request->get['filter_price'])) {
			// 	$url .= '&filter_price=' . $this->request->get['filter_price'];
			// }

			// if (isset($this->request->get['filter_quantity'])) {
			// 	$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			// }

			// if (isset($this->request->get['filter_status'])) {
			// 	$url .= '&filter_status=' . $this->request->get['filter_status'];
			// }

			// if (isset($this->request->get['sort'])) {
			// 	$url .= '&sort=' . $this->request->get['sort'];
			// }

			// if (isset($this->request->get['order'])) {
			// 	$url .= '&order=' . $this->request->get['order'];
			// }

			// if (isset($this->request->get['page'])) {
			// 	$url .= '&page=' . $this->request->get['page'];
			// }

			$this->response->redirect($this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/upload_img');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_upload_img->deleteProduct($product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	public function copy() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/upload_img');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_upload_img->copyProduct($product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = '';
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = '';
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/upload_img/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['copy'] = $this->url->link('catalog/upload_img/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/upload_img/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['bulkadd'] = $this->url->link('catalog/upload_img/bulkadd', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['products'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$product_total = $this->model_catalog_upload_img->getTotalProducts($filter_data);

		$results = $this->model_catalog_upload_img->getProducts($filter_data);
//   print_r($results);
		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}


			$data['products'][] = array(
				'id' => $result['id'],
				'image'      => $image,
				'name'       => $result['name'],
				'edit'       => $this->url->link('catalog/upload_img/edit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $result['id'] . $url, true)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . '&sort=pd.name' . $url, true);
		$data['sort_model'] = $this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . '&sort=p.model' . $url, true);
		$data['sort_price'] = $this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . '&sort=p.price' . $url, true);
		$data['sort_quantity'] = $this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . '&sort=p.quantity' . $url, true);
		$data['sort_status'] = $this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . '&sort=p.status' . $url, true);
		$data['sort_order'] = $this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . '&sort=p.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_model'] = $filter_model;
		$data['filter_price'] = $filter_price;
		$data['filter_quantity'] = $filter_quantity;
		$data['filter_status'] = $filter_status;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/upload_img_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['model'])) {
			$data['error_model'] = $this->error['model'];
		} else {
			$data['error_model'] = '';
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['id'])) {
			$data['action'] = $this->url->link('catalog/upload_img/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/upload_img/edit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $this->request->get['id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/upload_img', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_catalog_upload_img->getProduct($this->request->get['id']);
	
			
			}
		
			// print_r($result1);
			
		
		

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($product_info)) {
			$data['name'] = $product_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['meta_tag'])) {
			$data['meta_tag'] = $this->request->post['meta_tag'];
		} elseif (!empty($product_info)) {
			$data['meta_tag'] = $product_info['meta_tag'];
		} else {
			$data['meta_tag'] = '';
		}
		// Image
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($product_info)) {
			$data['image'] = $product_info['image'];
		} else {
			$data['image'] = '';
		}
		if (isset($this->request->post['product_id'])) {
			$data['product_id'] = $this->request->post['product_id'];
		} 
		 elseif (!empty($product_info)) {
			$data['product_id'] = $product_info['product_id'];
		} else {
			$data['product_id'] = '';
		} 
		 $data['product_name'] = $this->model_catalog_upload_img->getProduct_name($product_info['product_id']);
		//  print_r($product_name);

		// if (isset($this->request->post['product_name'])) {
		// 	$data['product_name'] = $this->request->post['product_name'];
		// 	} 
		//      elseif (!empty($product_info)) {
		// 	$data['product_name'] = $product_info['product_name'];
		// } else {
		// 	$data['product_name'] = '';
		// }
		  

		$this->load->model('tool/image');
		
		     

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		// Images
		if (isset($this->request->post['product_image'])) {
			$product_images = $this->request->post['product_image'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_images = $this->model_catalog_upload_img->getProductImages($this->request->get['product_id']);
		} else {
			$product_images = array();
		}

		$data['product_images'] = array();

		foreach ($product_images as $product_image) {
			if (is_file(DIR_IMAGE . $product_image['image'])) {
				$image = $product_image['image'];
				$thumb = $product_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}

	


		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/upload_form;', $data));
	}
	protected function getFormBulk() {
		$data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		

		$url = '';

		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['product_id'])) {
			$data['actionbulk'] = $this->url->link('catalog/product/bulkadd', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['actionbulk'] = $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $this->request->get['product_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_catalog_upload_img->getProduct($this->request->get['product_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();


		

		
		// Image
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($product_info)) {
			$data['image'] = $product_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/upload_form;_bulk', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/upload_img')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['product_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['image']) < 1) || (utf8_strlen($value['image']) > 255)) {
				$this->error['image'][$language_id] = $this->language->get('image');
			}
		}

		if ((utf8_strlen($this->request->post['meta_tag']) < 1) || (utf8_strlen($this->request->post['meta_tag']) > 64)) {
			$this->error['meta_tag'] = $this->language->get('meta_tag');
		}

		// if ($this->request->post['product_seo_url']) {
		// 	$this->load->model('design/seo_url');
			
		// 	foreach ($this->request->post['product_seo_url'] as $store_id => $language) {
		// 		foreach ($language as $language_id => $keyword) {
		// 			if (!empty($keyword)) {
		// 				if (count(array_keys($language, $keyword)) > 1) {
		// 					$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_unique');
		// 				}						
						
		// 				$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);
						
		// 				foreach ($seo_urls as $seo_url) {
		// 					if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['product_id']) || (($seo_url['query'] != 'product_id=' . $this->request->get['product_id'])))) {
		// 						$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');
								
		// 						break;
		// 					}
		// 				}
		// 			}
		// 		}
		// 	}
		// }

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/upload_img');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_upload_img->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_upload_img->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
