<?php
class ControllerApiProduct extends Controller
{
    public function index()
    {
        $this->load->language('api/cart');
        $this->load->model('catalog/product2');
        $this->load->model('tool/image');
        $json = array();
        $json['products'] = array();
        $product_id = (int)$this->request->get['product_id'];
        $results = $this->model_catalog_product2->getProduct($product_id);
        $resultImages = $this->model_catalog_product2->getProductImagesAPI($product_id);
        
            if ($results['image']) {
                $image = $this->model_tool_image->resize($results['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
            }
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($results['price'], $results['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $price = false;
            }
            if ((float) $results['special']) {
                $special = $this->currency->format($this->tax->calculate($results['special'], $results['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $special = false;
            }
            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float) $results['special'] ? $results['special'] : $results['price'], $this->session->data['currency']);
            } else {
                $tax = false;
            }
            if ($this->config->get('config_review_status')) {
                $rating = (int) $results['rating'];
            } else {
                $rating = false;
            }
            $data['products'][] = array(
                'product_id' => $results['product_id'],
                'thumb' => $image,
                'name' => $results['name'],
                // 'description' => utf8_substr(trim(strip_tags(html_entity_decode($results['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                'price' => $price,
                'special' => $special,
                // 'tax' => $tax,
                // 'minimum' => $results['minimum'] > 0 ? $results['minimum'] : 1,
                'rating' => $results['rating'],
                'href' => $this->url->link('product/product', 'product_id=' . $results['product_id']),
                'images' => $resultImages,
            );
        
        $json['products'] = $data['products'];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}