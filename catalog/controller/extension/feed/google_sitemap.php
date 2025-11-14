<?php

class ControllerExtensionFeedGoogleSitemap extends Controller {

	public function index() {

		if ($this->config->get('feed_google_sitemap_status')) {

			$output  = '<?xml version="1.0" encoding="UTF-8"?>';

			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';



			$this->load->model('catalog/product');

			$this->load->model('tool/image');

			$output .= '<url>';
			$output .= '  <loc>' .HTTPS_SERVER . '</loc>';
			$output .= '  <changefreq>daily</changefreq>';
			$output .= '  <priority>1.0</priority>';
			$output .= '</url>';

			$this->load->model('catalog/category');

			$output .= $this->getCategories(0);

			$products = $this->model_catalog_product->getProductsForfeed();



			foreach ($products as $product) {

				if ($product['image']) {

					$output .= '<url>';

					$output .= '  <loc>' . $this->url->link('product/product', 'product_id=' . $product['product_id'], true) . '</loc>';

					$output .= '  <changefreq>daily</changefreq>';

					$output .= '  <lastmod>' . date('Y-m-d'). '</lastmod>';

					$output .= '  <priority>0.8</priority>';

					$output .= '  <image:image>';

					$output .= '  <image:loc>' . $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height')) . '</image:loc>';

					$output .= '  <image:caption>' . $product['name'] . '</image:caption>';

					$output .= '  <image:title>' . $product['name'] . '</image:title>';

					$output .= '  </image:image>';

					$output .= '</url>';

				}

			}



			// $this->load->model('catalog/category');



			// $output .= $this->getCategories(0);



			$this->load->model('catalog/information');



			$informations = $this->model_catalog_information->getInformationSM();



			foreach ($informations as $information) {

				$output .= '<url>';

				$output .= '  <loc>' . $this->url->link('information/information', 'information_id=' . $information['information_id'], true) . '</loc>';

				$output .= '  <changefreq>daily</changefreq>';

				$output .= '  <priority>0.8</priority>';

				$output .= '</url>';

			}

			$output .= '<url>
				<loc>https://www.urbanwood.in/contact-us</loc>
				<changefreq>daily</changefreq>
				<priority>0.8</priority>
			</url>';
			$output .= '<url>
				<loc>https://www.urbanwood.in/postal-address</loc>
				<changefreq>daily</changefreq>
				<priority>0.8</priority>
			</url>';
			$output .= '</urlset>';


			file_put_contents('sitemap.xml', $output);
			$this->response->addHeader('Content-Type: application/xml');

			$this->response->setOutput($output);

		}

	}



	protected function getCategories($parent_id, $current_path = '') {

		$output = '';



		$results = $this->model_catalog_category->getCategories($parent_id);



		foreach ($results as $result) {

			// if (!$current_path) {

				$new_path = $result['category_id'];

			// } else {

			// 	$new_path = $current_path . '_' . $result['category_id'];

			// }



			$output .= '<url>';

			$output .= '  <loc>' . $this->url->link('product/category', 'path=' . $new_path, true) . '</loc>';

			$output .= '  <changefreq>daily</changefreq>';

			$output .= '  <priority>0.7</priority>';

			$output .= '</url>';



			// $products = $this->model_catalog_product->getProducts(array('filter_category_id' => $result['category_id']));



			// foreach ($products as $product) {

			// 	$output .= '<url>';

			// 	$output .= '  <loc>' . $this->url->link('product/product', 'path=' . $new_path . '&product_id=' . $product['product_id']) . '</loc>';

			// 	$output .= '  <changefreq>weekly</changefreq>';

			// 	$output .= '  <priority>1.0</priority>';

			// 	$output .= '</url>';

			// }



			$output .= $this->getCategories($result['category_id'], $new_path);

		}



		return $output;

	}

}

