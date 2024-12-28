<?php
class ControllerExtensionModuleFilter extends Controller {
	public function index() {
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		$category_id = end($parts);

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
		$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => 'Recommended', //$this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => '?route=product/category&path='.$this->request->get['path'].'&sort=p.sort_order&order=ASC'//$this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			// $data['sorts'][] = array(
			// 	'text'  => $this->language->get('text_name_asc'),
			// 	'value' => 'pd.name-ASC',
			// 	'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			// );

			// $data['sorts'][] = array(
			// 	'text'  => $this->language->get('text_name_desc'),
			// 	'value' => 'pd.name-DESC',
			// 	'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			// );

			$data['sorts'][] = array(
				'text'  => 'Price (Low to High)', //$this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => '?route=product/category&path='.$this->request->get['path'].'&sort=p.price&order=ASC' //$this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => 'Price (High to Low)', //$this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			// if ($this->config->get('config_review_status')) {
			// 	$data['sorts'][] = array(
			// 		'text'  => $this->language->get('text_rating_desc'),
			// 		'value' => 'rating-DESC',
			// 		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
			// 	);

			// 	$data['sorts'][] = array(
			// 		'text'  => $this->language->get('text_rating_asc'),
			// 		'value' => 'rating-ASC',
			// 		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
			// 	);
			// }

			$data['sorts'][] = array(
				'text'  => 'Latest', //$this->language->get('text_model_asc'),
				'value' => 'p.date_added-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.date_added&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => 'Fast Shipping', //$this->language->get('text_model_desc'),
				'value' => 'p.Fast_shipping',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.fast_shipping&order=DESC' . $url)
			);
			$data['sort'] = $sort;
			$data['order'] = $order;
		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$this->load->language('extension/module/filter');

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

			if (isset($this->request->get['pr'])) {
				$url .= '&pr=' . $this->request->get['pr'];
			}

			//$data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url));
			$data['action'] = str_replace('&amp;', '&', '?route=product/category&path=' . $this->request->get['path'] . $url);

			if (isset($this->request->get['filter'])) {
				$data['filter_category'] = explode(',', $this->request->get['filter']);
			} else {
				$data['filter_category'] = array();
			}

			$this->load->model('catalog/product');

			$data['filter_groups'] = array();

			$filter_groups = $this->model_catalog_category->getCategoryFilters($category_id);

			if ($filter_groups) {
				foreach ($filter_groups as $filter_group) {
					$childen_data = array();

					foreach ($filter_group['filter'] as $filter) {
						$filter_data = array(
							'filter_category_id' => $category_id,
							'filter_filter'      => $filter['filter_id']
						);

						$childen_data[] = array(
							'filter_id' => $filter['filter_id'],
							'name'      => $filter['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : '')
						);
					}

					$data['filter_groups'][] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $childen_data
					);
				}
				$this->document->addScript('catalog/view/javascript/bootstrap-slider.js');
				$this->document->addStyle('catalog/view/theme/default/stylesheet/bootstrap-slider.css');
				if (isset($this->request->get['filter'])) {
					$filter = $this->request->get['filter'];
				} else {
					$filter = '';
				}
				$min_max = '';
				if (isset($this->request->get['path'])) {
					$parts = explode('_', (string)$this->request->get['path']);
		
					$category_id = (int)array_pop($parts);
		
				} else {
					$category_id = 0;
				}
				$category_info = $this->model_catalog_category->getCategory($category_id);
		
				if ($category_info) {
					$filter_data = array(
						'filter_category_id' => $category_id,
					);
					$results = Array ( 'min' => 15000.0000, 'max' => 90000.0000 );//$this->model_catalog_product->getMinMaxProduct($filter_data);
		
					foreach ($results as $result) {
						if (!$min_max) {
							$min_max = $result;
						} else {
							$min_max .= '-' . $result;
						}
		
					}
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
					$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
				}
				if (isset($this->request->get['path'])) {
					if (stristr($this->request->get['path'], '_') === FALSE) {
						$parts = $this->request->get['path'];
						$category_id = $this->request->get['path'];
					} else {
						$path = '';
						$parts = explode('_', (string)$this->request->get['path']);
						$category_id = '';
						$category_id = (int)array_pop($parts);
					}
					$data['price_slider_status'] = $this->config->get('module_price_slider_status');
					$data['price_slider_title'] = $this->config->get('module_price_slider_heading');
					if (!isset($price_slider)) {
						$price_slider = array();
					}
					if (isset($this->request->get['pr'])) {
						$data['price_range'] = explode(',', $this->request->get['pr']);
						$price_min = $this->currency->convert($data['price_range'][0], $this->session->data['currency'], $this->config->get('config_currency'));
						$price_max = $this->currency->convert($data['price_range'][1], $this->session->data['currency'], $this->config->get('config_currency'));
		
						if ($price_max != null) {
							$price_max = round($price_max);
							$price_max = $price_max + (10 - (substr($price_max, -1)));
		
						}
					} else {
						$data['price_range'] = array();
					}
		
					if (version_compare(VERSION, '2.2.0.0', '<') == true) {
						$pcode = $this->currency->getCode();
					} else {
						$pcode = $this->session->data['currency'];
					}
		
					if ($this->currency->getSymbolLeft($pcode)) {
						$code = $this->currency->getSymbolLeft($pcode);
						$data['right_code'] = false;
					} else {
						$code = $this->currency->getSymbolRight($pcode);
						$data['right_code'] = true;
					}
		
					$data['price_code'] = $code;
		
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
					if (isset($this->request->get['filter'])) {
						$url .= '&filter=' . $this->request->get['filter'];
					}
					if (isset($this->request->get['manufacturer'])) {
						$url.='&manufacturer=' . $this->request->get['manufacturer'];
					}
		//echo $url;
					//$data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url));
					$data['action2'] = str_replace('&amp;', '&', '?route=product/category&path=' . $this->request->get['path'] . $url);

					if (!$min_max) {
						$range = explode('-', '0-0');
					} else {
						$range = explode('-', $min_max);
					}
					$data['min_max'] = $min_max;
					$data['price_range_min'] = $this->currency->format($range[0], $pcode, '', false);
					$data['price_range_max'] = $this->currency->format($range[1], $pcode, '', false);
		
					$data['price_min'] = $range[0];//$this->currency->format($range[0], $pcode);
					$data['price_max'] = $range[1];//$this->currency->format($range[1], $pcode);
		
					
				}
				return $this->load->view('extension/module/filter', $data);
			}
		}
	}
}