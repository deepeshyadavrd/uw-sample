<?php
class ControllerProductNewProducts extends Controller {
    public function index() {
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->document->setTitle("New Arrivals | Latest Furniture Designs & Trends – Urbanwood");
        $this->document->setDescription('Explore the newest arrivals at Urbanwood! Browse stylish furniture, from elegant sofas to smart storage solutions. Upgrade your space with fresh designs today!');
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
			$sort = 'p.sort_order';
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
        $data['products'] = [];
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
        $limit = 50;
        
        $results = $this->model_catalog_product->getProductsNewArrival([
            'sort' => 'p.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => $limit
        ]);

        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], 400, 281);
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
            $product_group = $this->model_catalog_product->getGroupedProduct($result['product_id']);
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
					'discount'	  	=> $percentage_off,
					'brand'			=> $result['manufacturer'],
					'rating'      	=> $result['rating'],
					'review_count'  => $result['reviews'],
					'stock_status'	=> $result['stock_status'],
					'quantity'	=>	$result['quantity'],
					'href'        	=> $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url, true),
					'pro_gro' => $pro_gro
				);
        }
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
        $data['heading_title'] = 'New Arrivals';

        $data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['menu'] = $this->load->controller('common/menu');
			$data['filter'] = $this->load->controller('extension/module/filter');
        $this->response->setOutput($this->load->view('product/new_products', $data));
    }
}