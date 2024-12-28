    <?php
    class ControllerProductCategory21jul extends Controller {
    	public function index() {
    		$data['category_css'] = file_get_contents('css/category.css');
    		$this->load->language('product/category');

    		$this->load->model('catalog/category');

    		$this->load->model('catalog/product');

    		$this->load->model('tool/image');

    		if (isset($this->request->get['filter'])) {
    			$data['filters_data'] = $filter = $this->request->get['filter'];
    		} else {
    			$filter = '';
    		}

    		if (isset($this->request->get['sort'])) {
    			$sort = $this->request->get['sort'];
    		} else {
    			$sort = 'p.price';
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

    		$data['breadcrumbs'] = array();

    		$data['breadcrumbs'][] = array(
    			'text' => $this->language->get('text_home'),
    			'href' => 'https://www.urbanwood.in/'
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

    			$path = '';

    			$parts = explode('_', (string)$this->request->get['path']);

    			$category_id = (int)array_pop($parts);


    		} else {
    			$category_id = 0;
    		}

    		$category_info = $this->model_catalog_category->getCategory($category_id);
    		$data['category_id'] = $category_id;

    		if ($category_info) {

    			$this->session->data['last_category']['name'] = $category_info['name'];
    			$this->session->data['last_category']['id'] = $category_id;


    			$this->document->setTitle($category_info['meta_title']);
    			$this->document->setDescription($category_info['meta_description']);
    			$this->document->setKeywords($category_info['meta_keyword']);

    			$data['heading_title'] = $category_info['name'];

    			if ($category_info['custom_filter']) {
    				$data['custom_filter'] = $category_info['custom_filter'];
    			}
    			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

    			$parentData = $this->model_catalog_category->getParentCategory($category_id);
    			$parentsCats = explode(',', $parentData['cats']);
    			$parentsCatName = explode(',', $parentData['name']);

    			if(!empty($parentsCats)){
    				foreach ($parentsCats as $key => $pcats) {
    					if ($category_info) {
    						$data['breadcrumbs'][] = array(
    							'text' => $parentsCatName[$key],
    							'href' => $this->url->link('product/category', 'path=' . $pcats . $url)
    						);
    					}
    				}
    			}

    			if ($category_info['image']) {
    				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height'));
    			} else {
    				$data['thumb'] = '';
    			}

    			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
    			$data['long_description'] = html_entity_decode($category_info['long_description'], ENT_QUOTES, 'UTF-8');
    			$data['compare'] = $this->url->link('product/compare');

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

    			$filter_data = array(
    				'filter_category_id' => $category_id,
    				'filter_filter'      => $filter,
    				'sort'               => $sort,
    				'order'              => $order,
    				'start'              => ($page - 1) * $limit,
    				'limit'              => $limit
    			);


    			$results = $this->model_catalog_product->getProducts($filter_data);

    			$total_product = $this->model_catalog_product->getTotalProducts($filter_data);
    			$data['total'] = $total_product; 
    			foreach ($results as $result) {
    				if ($result['image']) {
    					$image = $this->model_tool_image->resize($result['image'], 735, 507);
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

    				if ($result['name']!='') {

    					$data['products'][] = array(
    						'product_id'  => $result['product_id'],
    						'thumb'       => $image,
    						'wishlist_p'  => $wishlist_p,
    						'name'        => $product_name,
    						'fullname'    => $result['name'],
    						'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
    						'price'       => $price,
    						'special'     => $special,
    						'tax'         => $tax,
    						'delivery'    => $result['delivery'],
    						'bestseller'  => $result['bestseller'],
    						'discount'	  => $discount.'% OFF',
    						'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
    						'rating'      => $result['rating'],
    						'pclass'      => $pclass,
    						'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'] . $url)
    					);
    				}
    			}

    			$url = '';

    			if (isset($this->request->get['filter'])) {
    				$url .= '&filter=' . $this->request->get['filter'];
    			}

    			if (isset($this->request->get['limit'])) {
    				$url .= '&limit=' . $this->request->get['limit'];
    			}

    			$data['sorts'] = array();

    			$data['sorts'][] = array(
    				'text'  => 'Recommended',
    				'value' => 'p.sort_order-ASC',
    				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
    			);

    			$data['sorts'][] = array(
    				'text'  => 'Latest',
    				'value' => 'p.product_id-ASC',
    				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.product_id&order=ASC' . $url)
    			);



    			$data['sorts'][] = array(
    				'text'  => 'Price ↑',
    				'value' => 'p.price-ASC',
    				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
    			);

    			$data['sorts'][] = array(
    				'text'  => 'Price ↓',
    				'value' => 'p.price-DESC',
    				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
    			);







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
    					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
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


    			$data['continue'] = $this->url->link('common/home');
    			$data['filter'] = $this->load->controller('extension/module/filter');
    			$data['reset_filter'] = $this->url->link('product/category', 'path=' . $this->request->get['path']);
    			$data['footer'] = $this->load->controller('common/footer');
    			$data['header'] = $this->load->controller('common/header');
    			// print_r($data);exit;
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
    				'href' => $this->url->link('product/category', $url)
    			);

    			$this->document->setTitle($this->language->get('text_error'));

    			$data['continue'] = $this->url->link('common/home');

    			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');


    			$data['footer'] = $this->load->controller('common/footer');
    			$data['header'] = $this->load->controller('common/header');

    			$this->response->setOutput($this->load->view('error/not_found', $data));
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

    		if (isset($this->request->get['sort'])) {
    			$sort = $this->request->get['sort'];
    		} else {
    			$sort = 'p.price';
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
    				'limit'              => $limit
    			);
			// print_r($filter_data);exit;

    			$results = $this->model_catalog_product->getProducts($filter_data);
       		// print_r(array_column($results,'name'));exit;	
    			foreach ($results as $result) {
    				if ($result['image']) {
    					$image = $this->model_tool_image->resize($result['image'], 735, 507);
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

    				if ($result['name']!='') {

    					$data['products'][] = array(
    						'product_id'  => $result['product_id'],
    						'thumb'       => $image,
    						'wishlist_p'  => $wishlist_p,
    						'name'        => $product_name,
    						'fullname'    => $result['name'],
    						'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
    						'price'       => $price,
    						'special'     => $special,
    						'tax'         => $tax,
    						'delivery'    => $result['delivery'],
    						'bestseller'  => $result['bestseller'],
    						'discount'	  => $discount.'% OFF',
    						'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
    						'rating'      => $result['rating'],
    						'pclass'      => $pclass,
    						'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'] . $url)
    					);
    				}
    			}



			// print_r($data);exit;
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
    						'href' => $this->url->link('product/category', '&path='.$category_info['category_id']),
    						'image'=> $this->model_tool_image->resize($category_info['image'],140,140),
    						'liclass' => $liclass
    					);
    				}
    			}
    			$this->response->setOutput($this->load->view('product/custom_filter', $data));

    		}
    	}
    }
