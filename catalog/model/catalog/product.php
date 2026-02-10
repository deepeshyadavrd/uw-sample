<?php
class ModelCatalogProduct extends Model {
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	
	public function getProductSeoUrls($product_id) {
		$product_seo_url_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $product_seo_url_data;
	}
	public function getProduct($product_id) {
		// $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, pd.description AS description, p.image, m.name AS manufacturer,m.official_name, m.address_1, m.address_2, m.city,m.country_id, m.zone_id, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW()");
		$query = $this->db->query("SELECT p.product_id, p.bestseller,p.price, pd.name, pd.description,p.image, p.hide_price, m.name AS manufacturer, m.official_name, m.address_1, m.address_2, m.city, m.country_id, m.zone_id, discount.price AS discount, special.price AS special, reward.points AS reward, ss.name AS stock_status, wcd.unit AS weight_class, lcd.unit AS length_class, AVG(r1.rating) AS rating, COUNT(r2.review_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p 
		LEFT JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id
		LEFT JOIN " . DB_PREFIX . "manufacturer m ON p.manufacturer_id = m.manufacturer_id
		LEFT JOIN ( SELECT product_id, price FROM " . DB_PREFIX . "product_discount WHERE customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity = '1' AND (date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW()) ORDER BY priority, price ) AS discount ON discount.product_id = p.product_id
		LEFT JOIN ( SELECT product_id, price FROM " . DB_PREFIX . "product_special WHERE customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW()) ORDER BY priority, price ) AS special ON special.product_id = p.product_id
 		LEFT JOIN " . DB_PREFIX . "product_reward reward ON reward.product_id = p.product_id AND reward.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
		LEFT JOIN " . DB_PREFIX . "stock_status ss ON ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON wcd.weight_class_id = p.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON lcd.length_class_id = p.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		LEFT JOIN " . DB_PREFIX . "review r1 ON r1.product_id = p.product_id AND r1.status = 1
		LEFT JOIN " . DB_PREFIX . "review r2 ON r2.product_id = p.product_id AND r2.status = 1
		WHERE  p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = 1 AND p.date_available <= NOW() GROUP BY p.product_id");
// echo "SELECT p.product_id, p.price, pd.name, pd.description,p.image, m.name AS manufacturer, m.official_name, m.address_1, m.address_2, m.city, m.country_id, m.zone_id, discount.price AS discount, special.price AS special, reward.points AS reward, ss.name AS stock_status, wcd.unit AS weight_class, lcd.unit AS length_class, AVG(r1.rating) AS rating, COUNT(r2.review_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p 
// 		LEFT JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id
// 		LEFT JOIN " . DB_PREFIX . "manufacturer m ON p.manufacturer_id = m.manufacturer_id
// 		LEFT JOIN ( SELECT product_id, price FROM " . DB_PREFIX . "product_discount WHERE customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity = '1' AND (date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW()) ORDER BY priority, price ) AS discount ON discount.product_id = p.product_id
// 		LEFT JOIN ( SELECT product_id, price FROM " . DB_PREFIX . "product_special WHERE customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW()) ORDER BY priority, price ) AS special ON special.product_id = p.product_id
//  		LEFT JOIN " . DB_PREFIX . "product_reward reward ON reward.product_id = p.product_id AND reward.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
// 		LEFT JOIN " . DB_PREFIX . "stock_status ss ON ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "' 
// 		LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON wcd.weight_class_id = p.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'
// 		LEFT JOIN " . DB_PREFIX . "length_class_description lcd ON lcd.length_class_id = p.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "'
// 		LEFT JOIN " . DB_PREFIX . "review r1 ON r1.product_id = p.product_id AND r1.status = 1
// 		LEFT JOIN " . DB_PREFIX . "review r2 ON r2.product_id = p.product_id AND r2.status = 1
// 		WHERE  p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = 1 AND p.date_available <= NOW() GROUP BY p.product_id";
		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],				
				'fast_shipping'    => $query->row['fast_shipping'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				// 'tag'              => $query->row['tag'],
				// 'model'            => $query->row['model'],
				'bestseller'       => $query->row['bestseller'],
				'sku'              => $query->row['sku'],
				// 'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'hide_price'            => $query->row['hide_price'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'brand_name'     => $query->row['official_name'],
				'address_1'     => $query->row['address_1'],
				'address_2'     => $query->row['address_2'],
				'city'     => $query->row['city'],
				'country'     => $this->getcountry($query->row['country_id']),
				'zone'     => $this->getZone($query->row['zone_id']),
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'tax_class_id'     => $query->row['tax_class_id'],
				// 'date_available'   => $query->row['date_available'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				// 'length_class_id'  => $query->row['length_class_id'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				// 'minimum'          => $query->row['minimum'],
				// 'sort_order'       => $query->row['sort_order'],
				// 'status'           => $query->row['status'],
				// 'date_added'       => $query->row['date_added'],
				// 'date_modified'    => $query->row['date_modified'],
				// 'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}
	public function getMatchP($pname,$option,$opcheck=0){

				$sqll = "SELECT p.product_id, pd.name AS name,p.image as pimage, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)  WHERE pd.name LIKE '%" . $this->db->escape($pname) . "%' AND p.status = '1'";
					if($opcheck==1){
						$sqll.="AND pd.name LIKE '% " . $option . "%'";
					}
					$query = $this->db->query($sqll);

					if ($query->num_rows) {
						return $query->row;
					} else {
						return false;
					}
				}
	public function getProducts($data = array()) {
		$validimi = false; $filter_groups = array();
		if((int)$data['filter_category_id']== 96){
			$basenamecount = 6;
		}elseif($data['sp'] == 'search_page'){
			$basenamecount = 4;
		}else{
			$basenamecount = 3;
		}
		$sql = "SELECT SUBSTRING_INDEX(pd.name, ' ', ". $basenamecount .") AS base_name, p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";


		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN oc_product_special ps ON (p.product_id = ps.product_id) WHERE pd.name not like '%custom%' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW()";
		if (!empty($data['hide_recent_products'])) {
			$sql .= " AND p.date_added < DATE_SUB(NOW(), INTERVAL 200 DAY)";
		}

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}
				$sql_check = "SELECT DISTINCT (filter_group_id) FROM  " . DB_PREFIX . "filter WHERE filter_id IN (" . implode(',', $implode) . ") ";    
        		$query_check = $this->db->query($sql_check);
        		$filter_groups = array();
        		foreach ($query_check->rows as $result) {
        		    $filter_groups[$result['filter_group_id']] = array();
        		}

        		if(count($filter_groups) > 1){
        		    $validimi = true;               
        		}else{
        		    $validimi = false;
        		}
				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}
		if (!empty($data['pr'])) {
			$range = explode(',', $data['pr']);
			$sql .= " AND ps.price BETWEEN ".$range[0]." AND ". $range[1];
		}
		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}
			

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		If( $validimi ) {
			$sql .= " GROUP BY p.product_id HAVING COUNT( DISTINCT pf.filter_id)=".count($filter_groups);
			}else{
			
				$sql .= " GROUP BY base_name";
			
			}

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				if (!empty($data['order']) && $data['order'] == 'DESC') {
					$sql .= " ORDER BY special DESC";
				} else {
					$sql .= " ORDER BY special ASC";
				}
			} elseif ($data['sort'] == 'p.viewed') {
				$sql .= " ORDER BY p.viewed DESC";
			} elseif ($data['sort'] == 'p.date_added') {
				$sql .= " ORDER BY p.date_added DESC";
			} else {
				$sql .= " ORDER BY p.viewed DESC";
			}
		} else {
			$sql .= " ORDER BY p.viewed DESC";
		}

// if($data['filter_category_id'] == 201){
// $myfile = fopen("catalog/view/javascript/assets/sql.txt", "w");
// fwrite($myfile, $sql);
// fclose($myfile);
// }
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$product_data = array();

	$query = $this->db->query($sql);
// print_r($sql);
		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}
	public function getProductsNewArrival($data = array()) {
	// 	$validimi = false; $filter_groups = array();
	// 	$sql = "SELECT SUBSTRING_INDEX(pd.name, ' ', 2) AS base_name, p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

	// 	if (!empty($data['filter_category_id'])) {
	// 		if (!empty($data['filter_sub_category'])) {
	// 			$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
	// 		} else {
	// 			$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
	// 		}

	// 		if (!empty($data['filter_filter'])) {
	// 			$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
	// 		} else {
	// 			$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
	// 		}
	// 	} else {
	// 		$sql .= " FROM " . DB_PREFIX . "product p";
	// 	}

	// 	$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN oc_product_special ps ON (p.product_id = ps.product_id) WHERE pd.name not like '%custom%' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND `date_added` >= NOW() - INTERVAL 420 DAY
    //   AND `date_added`  < NOW()";
		
	// 	if (!empty($data['filter_category_id'])) {
	// 		if (!empty($data['filter_sub_category'])) {
	// 			$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
	// 		} else {
	// 			$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
	// 		}

	// 		if (!empty($data['filter_filter'])) {
	// 			$implode = array();

	// 			$filters = explode(',', $data['filter_filter']);

	// 			foreach ($filters as $filter_id) {
	// 				$implode[] = (int)$filter_id;
	// 			}
	// 			$sql_check = "SELECT DISTINCT (filter_group_id) FROM  " . DB_PREFIX . "filter WHERE filter_id IN (" . implode(',', $implode) . ") ";    
    //     		$query_check = $this->db->query($sql_check);
    //     		$filter_groups = array();
    //     		foreach ($query_check->rows as $result) {
    //     		    $filter_groups[$result['filter_group_id']] = array();
    //     		}

    //     		if(count($filter_groups) > 1){
    //     		    $validimi = true;               
    //     		}else{
    //     		    $validimi = false;
    //     		}
	// 			$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
	// 		}
	// 	}
	// 	if (!empty($data['pr'])) {

	// 		$range = explode(',', $data['pr']);

	// 		$sql .= " AND ps.price BETWEEN ".$range[0]." AND ". $range[1];
	// 	}
	// 	if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
	// 		$sql .= " AND (";

	// 		if (!empty($data['filter_name'])) {
	// 			$implode = array();

	// 			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

	// 			foreach ($words as $word) {
	// 				$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
	// 			}

	// 			if ($implode) {
	// 				$sql .= " " . implode(" AND ", $implode) . "";
	// 			}

	// 			if (!empty($data['filter_description'])) {
	// 				$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
	// 			}
	// 		}

	// 		if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
	// 			$sql .= " OR ";
	// 		}
	// 		if (!empty($data['filter_tag'])) {
	// 			$implode = array();

	// 			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

	// 			foreach ($words as $word) {
	// 				$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
	// 			}

	// 			if ($implode) {
	// 				$sql .= " " . implode(" AND ", $implode) . "";
	// 			}
	// 		}
			

	// 		if (!empty($data['filter_name'])) {
	// 			$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
	// 			$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
	// 			$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
	// 			$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
	// 			$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
	// 			$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
	// 			$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
	// 		}

	// 		$sql .= ")";
	// 	}

	// 	if (!empty($data['filter_manufacturer_id'])) {
	// 		$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
	// 	}

	// 	If( $validimi ) {
	// 		$sql .= " GROUP BY p.product_id HAVING COUNT( DISTINCT pf.filter_id)=".count($filter_groups);
	// 		}else{
			
	// 			$sql .= " GROUP BY base_name";
			
	// 		}
	// 	$sort_data = array(
	// 		'pd.name',
	// 		'p.model',
	// 		'p.quantity',
	// 		'p.price',
	// 		'rating',
	// 		'p.sort_order',
	// 		'p.date_added'
	// 	);

	// 	if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
	// 		if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
	// 			$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
	// 		} elseif ($data['sort'] == 'p.price') {
	// 			$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
	// 		} else {
	// 			$sql .= " ORDER BY ". $data['sort'];
	// 		}
	// 	} else {
	// 		$sql .= " ORDER BY p.viewed";
	// 	}

	// 	if (isset($data['order']) && ($data['order'] == 'DESC')) {
	// 		$sql .= " DESC, LCASE(pd.name) DESC";
	// 	} else {
	// 		$sql .= " ASC, LCASE(pd.name) ASC";
	// 	}

	// 		if (isset($data['start']) || isset($data['limit'])) {
	// 			if ($data['start'] < 0) {
	// 				$data['start'] = 0;
	// 			}

	// 			if ($data['limit'] < 1) {
	// 				$data['limit'] = 20;
	// 			}

	// 			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	// 		}
	// 		// echo $sql;
	// 		$product_data = array();
	// 	$query = $this->db->query($sql);

		

	// 	foreach ($query->rows as $result) {
	// 		$product_data[] = $this->getProduct($result['product_id']);
	// 	}

	// 	return $product_data;
	$days = 320; // number of days to consider as "new"

    $sql = "SELECT SUBSTRING_INDEX(pd.name, ' ', 2) AS base_name, pd.name, p.product_id,p.image
            FROM " . DB_PREFIX . "product p
            LEFT JOIN " . DB_PREFIX . "product_description pd
                ON (p.product_id = pd.product_id)
            WHERE
                p.status = '1'
				AND pd.name not like '%custom%'
                AND p.date_available <= NOW()
                AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
                AND p.date_added >= DATE_SUB(NOW(), INTERVAL " . (int)$days . " DAY)
             GROUP BY base_name ORDER BY p.date_added DESC";

    $product_data = array();
    $query = $this->db->query($sql);
// print_r($query->rows);
    foreach ($query->rows as $result) {
        $product_data[] = $this->getProduct($result['product_id']);
    }
// print_r($product_data);
    return $query->rows;
	}
	public function getGroupedProduct($product_id){
		$sql = "SELECT `trail_product_id`, `text` from " . DB_PREFIX . "product_group where lead_product_id=".$product_id . " and (group_id=1 || group_id =5 || group_id=6)";
		$query = $this->db->query($sql);
		
			return $query->rows;
		
	}
	public function getProductForFeed($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, pd.description AS description, p.image, m.name AS manufacturer,m.official_name, m.address_1, m.address_2, m.city,m.country_id, m.zone_id, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW()");

		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],				
				// 'fast_shipping'    => $query->row['fast_shipping'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				// 'meta_title'       => $query->row['meta_title'],
				// 'meta_description' => $query->row['meta_description'],
				// 'meta_keyword'     => $query->row['meta_keyword'],
				// 'tag'              => $query->row['tag'],
				// 'model'            => $query->row['model'],
				// 'bestseller'       => $query->row['bestseller'],
				'sku'              => $query->row['sku'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'brand_name'     => $query->row['official_name'],
				// 'address_1'     => $query->row['address_1'],
				// 'address_2'     => $query->row['address_2'],
				// 'city'     => $query->row['city'],
				// 'country'     => $this->getcountry($query->row['country_id']),
				// 'zone'     => $this->getZone($query->row['zone_id']),
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				// 'tax_class_id'     => $query->row['tax_class_id'],
				// 'date_available'   => $query->row['date_available'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				// 'length_class_id'  => $query->row['length_class_id'],
				// 'rating'           => round($query->row['rating']),
				// 'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				// 'minimum'          => $query->row['minimum'],
				// 'sort_order'       => $query->row['sort_order'],
				// 'status'           => $query->row['status'],
				// 'date_added'       => $query->row['date_added'],
				// 'date_modified'    => $query->row['date_modified'],
				// 'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}
	public function getProductsForfeed($data = array()) {
		$sql = "SELECT p.product_id, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN oc_product_special ps ON (p.product_id = ps.product_id) WHERE pd.name not like '%custom%' AND pd.name not like '%test%' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p.date_added > '" . $data['date'] . "'";

		if (!empty($data['filter_category_id'])) {

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}
		if (!empty($data['pr'])) {

			$range = explode(',', $data['pr']);

			$sql .= " AND ps.price BETWEEN ".$range[0]." AND ". $range[1];
		}
		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}
			

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		// echo $sql;
		$product_data = array();

	$query = $this->db->query($sql);
	
		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProductForFeed($result['product_id']);
		}
// print_r($query->rows);
		return $product_data;
	}

	public function getProductSpecials($data = array()) {
		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getLatestProducts($limit) {
		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getPopularProducts($limit) {
		$product_data = $this->cache->get('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
	
		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);
	
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			$this->cache->set('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}
		
		return $product_data;
	}

	public function getBestSellerProducts($limit) {
		$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$product_data = array();

			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}

		return $product_attribute_group_data;
	}
	
	public function getProductGroup($product_id) {
		$product_group_data = array();

		$product_group_query = $this->db->query("SELECT g.group_id, g.name from " . DB_PREFIX . "product_group pg LEFT JOIN " . DB_PREFIX . "group g ON (pg.group_id = g.group_id) where pg.lead_product_id=". (int)$product_id. " GROUP BY pg.group_id ORDER by pg.group_id");

		foreach ($product_group_query->rows as $product_group) {
			$product_group_data2 = array();

			$product_query = $this->db->query("SELECT * from ". DB_PREFIX."product_group where lead_product_id=". (int)$product_id ." AND group_id=".(int)$product_group['group_id']);

			foreach($product_query->rows as $value){
				$product_query = $this->db->query("SELECT p.product_id, ps.price, pi.image from " . DB_PREFIX . "product p LEFT JOIN ". DB_PREFIX ."product_special ps ON p.product_id = ps.product_id LEFT JOIN ". DB_PREFIX ."product_image pi ON p.product_id = pi.product_id AND pi.sort_order = 1 where p.status = 1  AND p.product_id=". (int)$value['trail_product_id'] . " LIMIT 1");

			foreach ($product_query->rows as $product_attribute) {
				
				$product_group_data2[] = array(
					
					'p_id'         => $product_attribute['product_id'],
					// 'p_name'         => $product_attribute['name'],
					// 'p_price'         => $product_attribute['price'],
					'p_image'         => $product_attribute['image'],
					'text' => $value['text']
				);
			}
			
		}

			$product_group_data[] = array(
				'group_id' => $product_group['group_id'],
				'name'               => $product_group['name'],
				'group_product'          => $product_group_data2
			);
		}
		return $product_group_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductRelated($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id)  WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW()");

		foreach ($query->rows as $result) {
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}

		return $product_data;
	}

	public function getProductBundle($product_id) {
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_bundle pb LEFT JOIN " . DB_PREFIX . "product p ON (pb.bundle_id = p.product_id)  WHERE pb.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW()");

		foreach ($query->rows as $result) {
			$product_data[$result['bundle_id']] = $this->getProduct($result['bundle_id']);
		}

		return $product_data;
	}

	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		if((int)$data['filter_category_id']== 96){
			$basenamecount = 6;
		}elseif($data['sp'] == 'search_page'){
			$basenamecount = 4;
		}else{
			$basenamecount = 3;
		}
		$sql = "SELECT COUNT(DISTINCT SUBSTRING_INDEX(name, ' ', $basenamecount)) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id) WHERE pd.name not like '%custom%' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW()";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		if (!empty($data['pr'])) {
			$range = explode(',', $data['pr']);
			$sql .= " AND ps.price BETWEEN ".$range[0]." AND ". $range[1];
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
		
	}

	public function getProfile($product_id, $recurring_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r JOIN " . DB_PREFIX . "product_recurring pr ON (pr.recurring_id = r.recurring_id AND pr.product_id = '" . (int)$product_id . "') WHERE pr.recurring_id = '" . (int)$recurring_id . "' AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

		return $query->row;
	}

	public function getProfiles($product_id) {
		$query = $this->db->query("SELECT rd.* FROM " . DB_PREFIX . "product_recurring pr JOIN " . DB_PREFIX . "recurring_description rd ON (rd.language_id = " . (int)$this->config->get('config_language_id') . " AND rd.recurring_id = pr.recurring_id) JOIN " . DB_PREFIX . "recurring r ON r.recurring_id = rd.recurring_id WHERE pr.product_id = " . (int)$product_id . " AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getTotalProductSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}

	public function checkPincode($pincode){
		$query = $this->db->query("SELECT * FROM `oc_pincode` WHERE pincode=".$pincode);

		if ($query->num_rows) {
			return (int)$query->row;
		} else {
			return 0;
		}
	}

	public function getcartquantity($cart_id){
		$query = $this->db->query('SELECT quantity from oc_cart where cart_id='.$cart_id);
		if (isset($query->row['quantity'])) {
			return $query->row['quantity'];
		} else {
			return 0;
		}
	}

	public function getCountry($country_id) {
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "' AND status = '1'");

		return $query->row['name'];
	}
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' AND status = '1'");

		return $query->row['name'];
	}

	public function gettrending(){
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "home_section hs LEFT JOIN " . DB_PREFIX . "home_section_image hsi ON (hs.home_section_id = hsi.home_section_id) WHERE hs.home_section_id = 1 AND hs.status = '1' AND hsi.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY hsi.sort_order ASC");
		return $query->rows;

	}
}
