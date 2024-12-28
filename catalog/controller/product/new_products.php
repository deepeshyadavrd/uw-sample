<?php
class ControllerProductNewProducts extends Controller {
    public function index() {
        // $this->load->language('product/new_products');
        $this->load->model('catalog/product2');
        $this->load->model('tool/image');

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
        // Define the limit for new products
        $limit = 50; // Adjust as needed
        
        // Get new products based on date_added descending order
        $results = $this->model_catalog_product2->getProductsNewArrival([
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
            $product_group = $this->model_catalog_product2->getGroupedProduct($result['product_id']);
            // print_r($product_group);
            $pro_gro = array();
            foreach($product_group as  $value){
                $pro_gro[] = array(
                    'text' => $value['text'],  
                'link' => $this->url->link('product/product','&product_id=' . $value['trail_product_id'] )
                );
            }
            // print_r($pro_gro);
            $percentage_off = (($result['price'] - $result['special']) / $result['price'])* 100;
            //echo $percentage_off;
            list($name, $finish) = explode('(', $result['name']);
            //echo $finish;
            $data['products'][] = array(
                'product_id'  => $result['product_id'],
                'thumb'       => $image,
                'name'        => $name,
                'finish'        => $finish,
                'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price'       => $price,
                'special'     => $special,
                'discount'	=> $percentage_off,
                'brand'		=> $result['manufacturer'],
                'tax'         => $tax,
                'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
                'rating'      => $result['rating'],
                'review_count'      => $result['reviews'],
                'stock_status'	=> $result['stock_status'],
                'quantity'	=>	$result['quantity'],
                'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url),
                'pro_gro' => $pro_gro
            );
        }

        $this->document->setTitle('New Arrivals');
        $data['heading_title'] = 'New Arrivals';

        $data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			$data['menu'] = $this->load->controller('common/menu');
			$data['filter'] = $this->load->controller('extension/module/filter');
        $this->response->setOutput($this->load->view('product/new_products', $data));
    }
}