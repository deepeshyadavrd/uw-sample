<?php
class ControllerProductCategory extends Controller {
	public function index() {
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');
		if($this->customer->isLogged()) { 
			$data['cid'] = $this->customer->getId();
		} else{
			$data['cid'] = '';
		}

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['pr'])) {
			$pr = $this->request->get['pr'];
		} else {
			$pr = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = '';
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

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => 'https://www.urbanwood.in',//$this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			// $path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if($category_info['parent_id'] != 0){
			$data['breadcrumbs'][] = $this->loop_again($category_info['parent_id'], $url);
		}
		if ($category_info) {

			$this->document->setTitle($category_info['meta_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			$data['heading_title'] = $category_info['name'];

			if ($category_info['custom_filter']) {
                $data['custom_filter'] = $category_info['custom_filter'];
            }
			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'], true)
			);
			
			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			$data['long_description'] = html_entity_decode($category_info['long_description'], ENT_QUOTES, 'UTF-8');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['products'] = array();
			if($category_id == 256){
				$limit = 20;
			}
			$filter_data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit,
				'pr'				 =>	$pr
			);
			$exception_categories = [358]; // CHANGE IDS

			if (!in_array($category_id, $exception_categories)) {
				$filter_data['hide_recent_products'] = true;
			}
			// $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$results = $this->model_catalog_product->getProducts($filter_data);
			// print_r($results);
			foreach ($results as $result) {
				if ($result['image']) {
					// echo DIR_IMAGE . $result['image'];
					$image = $this->model_tool_image->resize($result['image'], 400, 281);
					// var_dump($image);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}
				
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				$product_group = $this->model_catalog_product->getGroupedProduct($result['product_id']);
				// print_r($product_group);
				$pro_gro = array();
				foreach($product_group as  $value){
					$pro_gro[] = array(
						'text' => $value['text'],  
					'link' => $this->url->link('product/product','&product_id=' . $value['trail_product_id'], true )
					);
				}
				$percentage_off = round((($result['price'] - $result['special']) / $result['price'])* 100);
				
				list($name, $finish) = preg_split('@(?=\()@', $result['name']);
				$data['products'][] = array(
					'product_id'  	=> $result['product_id'],
					'thumb'       	=> $image,
					'name'        	=> $name,
					'finish'        => $finish,
					'description' 	=> utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       	=> $price,
					'special'     	=> $special,
					'special_number'=>  $result['special'],
					'bestseller'=>  $result['bestseller'],
					'discount'	  	=> $percentage_off,
					'brand'			=> $result['manufacturer'],
					// 'tax'         	=> $tax,
					// 'minimum'     	=> $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      	=> $result['rating'],
					'review_count'  => $result['reviews'],
					'stock_status'	=> $result['stock_status'],
					'quantity'	=>	$result['quantity'],
					'href'        	=> $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url, true),
					'p_cat' => $category_info['name'],
					'pro_gro' => $pro_gro
				);
			}
			$data['category_id'] = $category_id;
			// if($category_id == 256 ){
			// 	$data['products'] = array_reverse($data['products']);
			// 	$data['badge'] = true;
	//print_r($data['products']);
				// }
			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value, true)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;
			$data['pr'] = $pr;
			$data['filters_data'] = $filter;

			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['menu'] = $this->load->controller('common/menu');
			$data['filter'] = $this->load->controller('extension/module/filter');
			

			$this->response->setOutput($this->load->view('product/category', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url, true)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function loop_again($num, $url) {
		
		if (isset($num)) {
			$cat_info = $this->model_catalog_category->getCategory($num);
			
			$data['breadcrumbs'] = array(
						'text' => $cat_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $num. $url, true)
					);
			if ($cat_info['parent_id'] != 0) {
	
				$data['breadcrumbs'][] = $this->loop_again($cat_info['parent_id'], $url);
	
			}
	
			return $data['breadcrumbs'];
	
		}
	
	}

	public function productLoading() {

		$this->load->language('product/category');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
	  
		if (isset($this->request->get['filter'])) {
		 $filter = $this->request->get['filter'];
	  } else {
		 $filter = '';
	  }

	  if (isset($this->request->get['pr'])) {
		 $pr = $this->request->get['pr'];
	  } else {
		 $pr = '';
	  }

	  if (isset($this->request->get['sort'])) {
		 $sort = $this->request->get['sort'];
	  } else {
		 $sort = '';
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
	  
	  if (isset($this->request->get['limit'])) {
		 $limit = (int)$this->request->get['limit'];
	  } else {
		 $limit = 12;
	  }
	  
	  if (isset($this->request->get['category_id'])) {
		 $category_id = (int)$this->request->get['category_id'];
	  } else {
		 $category_id = 0;
	  }
	  
	  $category_info = $this->model_catalog_category->getCategory($category_id);
	  $data['category_id'] = $category_id;
	  
	  if ($category_info) {
	  
		 $data['products'] = array();
	  
		 $filter_data = array(
		  'filter_category_id' => $category_id,
		  'filter_filter'      => $filter,
		  'sort'               => $sort,
		  'order'              => $order,
		  'start'              => ($page - 1) * $limit,
		  'limit'              => $limit,
		  'pr'				=> $pr
	  );
	  $exception_categories = [358]; // CHANGE IDS

			if (!in_array($category_id, $exception_categories)) {
				$filter_data['hide_recent_products'] = true;
			}
	  
		 $results = $this->model_catalog_product->getProducts($filter_data);

		 foreach ($results as $result) {
		  $options = array();
	  
	  if ($result['image']) {
		$image = $this->model_tool_image->resize($result['image'], 400, 281);
	  } else {
		$image = $this->model_tool_image->resize('placeholder.png',735, 507);
	  }
	  
	  
	  if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
	   $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
	  } else {
	   $price = false;
	  }
	  
	  if ((float)$result['special']) {
	   $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
	  } else {
	   $special = false;
	  }
	  
	  if ($this->config->get('config_tax')) {
	   $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
	  } else {
	   $tax = false;
	  }
	  
	  if ($this->config->get('config_review_status')) {
	   $rating = (int)$result['rating'];
	  } else {
	   $rating = false;
	  }
	  if ($result['special']) {
	   $discount = round(($result['price']-$result['special'])*100/$result['price']);
	  }   
	  $this->load->model('account/wishlist');
	  $wishlist_p = 0;
	  $wishlist_array = array_column($this->model_account_wishlist->getWishlist(),'product_id');
	  if(in_array($result['product_id'],$wishlist_array)){
	   $wishlist_p = 1;
	  }
	  $pclass = '';
	  if (strpos(strtolower($result['name']), 'modular') !== false) {
	  
	   $pclass = 'outOFStock';
	  }
	  if ($result['quantity']<1) {
	  
	   $pclass = 'outOFStock';
	  }
	  $newname1  = explode('(', $result['name']);
	  
	  $product_name = $newname1[0];
	  
	  
	  if ($newname1[1]) {
	   $product_name = $newname1[0].'<small>('.$newname1[1].'</small>';
	  }
	  if ($newname1[2]) {
	   $product_name = $newname1[0].'<small>('.$newname1[1].'('.$newname1[2].'</small>';
	  }
	  $product_group = $this->model_catalog_product->getGroupedProduct($result['product_id']);
				// print_r($product_group);
				$pro_gro = array();
				foreach($product_group as  $value){
					$pro_gro[] = array(
						'text' => $value['text'],  
					'link' => $this->url->link('product/product','&product_id=' . $value['trail_product_id'], true )
					);
				}
	//   list($name, $finish) = explode('(', $result['name']);
	  $product_seo_url = '';
	  $product_seo_url = $this->model_catalog_product->getProductSeoUrls($result['product_id']);
	  list($name, $finish) = preg_split('@(?=\()@', $result['name']);
	  $plink      =  'https://www.urbanwood.in/'.$product_seo_url[0][1];
	  if ($result['name']!='') {
	  
	   $data['products'][] = array(
		'product_id'  => $result['product_id'],
		'thumb'       => $image,
		'fast_shipping'=>$result['fast_shipping'],
		'wishlist_p'  => $wishlist_p,
		'name'        => $name,
		'finish'        => $finish,
		'fullname'    => $result['name'],
		'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
		'price'       => $price,
		'special'     => $special,
		'special_number'=>  $result['special'],
		'options'	  => $options,
		'brand'		  => $result['manufacturer'],
		'tax'         => $tax,
		'delivery'    => $result['delivery'],
		'bestseller'  => $result['bestseller'],
		'discount'	  => $discount.'% OFF ',
		'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
		'rating'      => $result['rating'],
		'stock_status'	=> $result['stock_status'],
		'pclass'      => $pclass,
		'href'		  => $plink, //$this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
		'pro_gro' => $pro_gro
	  );
	  }
	  }
		 
	   $this->response->setOutput($this->load->view('product/categoryAjax', $data));
	  }else{
		  $this->response->setOutput($this->load->view('product/categoryAjax', $data));
	  }
	
	  }

	public function categoryFilter(){

		$this->load->model('catalog/category');
		$this->load->model('tool/image');
		if (isset($this->request->post['catids'])) {
			$main_category_id = (int)$this->request->post['main_category_id'];
			$catss = explode(',',$this->request->post['catids']);
			$data['categories'] = array();
			if (!empty($catss)) {

				foreach ($catss as $key => $category_id) {
					$category_info = $this->model_catalog_category->getCategory($category_id);
					$liclass = '';
					if($category_id == $main_category_id){
						$liclass = 'active';
					}
					$data['categories'][] = array(
						'name' => $category_info['name'],
						'href' => $this->url->link('product/category', '&path='.$category_info['category_id'], true),
						'image'=> $this->model_tool_image->resize($category_info['image'],160,75),
						'liclass' => $liclass
					);
				}
			}
			$this->response->setOutput($this->load->view('product/custom_filter', $data));

		}
	}
}
