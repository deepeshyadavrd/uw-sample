<?php
class ModelCatalogCollection extends Model {
	public function addcollection($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "collection SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$collection_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "collection SET image = '" . $this->db->escape($data['image']) . "' WHERE collection_id = '" . (int)$collection_id . "'");
		}

		foreach ($data['collection_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "collection_description SET collection_id = '" . (int)$collection_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		// $level = 0;

		// $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "collection_path` WHERE collection_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		// foreach ($query->rows as $result) {
		// 	$this->db->query("INSERT INTO `" . DB_PREFIX . "collection_path` SET `collection_id` = '" . (int)$collection_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

		// 	$level++;
		// }

		// $this->db->query("INSERT INTO `" . DB_PREFIX . "collection_path` SET `collection_id` = '" . (int)$collection_id . "', `path_id` = '" . (int)$collection_id . "', `level` = '" . (int)$level . "'");

		if (isset($data['collection_filter'])) {
			foreach ($data['collection_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "collection_filter SET collection_id = '" . (int)$collection_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		// if (isset($data['collection_store'])) {
		// 	foreach ($data['collection_store'] as $store_id) {
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "collection_to_store SET collection_id = '" . (int)$collection_id . "', store_id = '" . (int)$store_id . "'");
		// 	}
		// }
		
		if (isset($data['collection_seo_url'])) {
			foreach ($data['collection_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'collection_id=" . (int)$collection_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
		
		// Set which layout to use with this collection
		// if (isset($data['collection_layout'])) {
		// 	foreach ($data['collection_layout'] as $store_id => $layout_id) {
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "collection_to_layout SET collection_id = '" . (int)$collection_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
		// 	}
		// }

		$this->cache->delete('collection');

		return $collection_id;
	}

	public function editcollection($collection_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "collection SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE collection_id = '" . (int)$collection_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "collection SET image = '" . $this->db->escape($data['image']) . "' WHERE collection_id = '" . (int)$collection_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "collection_description WHERE collection_id = '" . (int)$collection_id . "'");

		foreach ($data['collection_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "collection_description SET collection_id = '" . (int)$collection_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', long_description = '" . $this->db->escape($value['long_description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		// $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "collection_path` WHERE path_id = '" . (int)$collection_id . "' ORDER BY level ASC");

		// if ($query->rows) {
		// 	foreach ($query->rows as $collection_path) {
		// 		// Delete the path below the current one
		// 		$this->db->query("DELETE FROM `" . DB_PREFIX . "collection_path` WHERE collection_id = '" . (int)$collection_path['collection_id'] . "' AND level < '" . (int)$collection_path['level'] . "'");

		// 		$path = array();

		// 		// Get the nodes new parents
		// 		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "collection_path` WHERE collection_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

		// 		foreach ($query->rows as $result) {
		// 			$path[] = $result['path_id'];
		// 		}

		// 		// Get whats left of the nodes current path
		// 		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "collection_path` WHERE collection_id = '" . (int)$collection_path['collection_id'] . "' ORDER BY level ASC");

		// 		foreach ($query->rows as $result) {
		// 			$path[] = $result['path_id'];
		// 		}

		// 		// Combine the paths with a new level
		// 		$level = 0;

		// 		foreach ($path as $path_id) {
		// 			$this->db->query("REPLACE INTO `" . DB_PREFIX . "collection_path` SET collection_id = '" . (int)$collection_path['collection_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

		// 			$level++;
		// 		}
		// 	}
		// } else {
		// 	// Delete the path below the current one
		// 	$this->db->query("DELETE FROM `" . DB_PREFIX . "collection_path` WHERE collection_id = '" . (int)$collection_id . "'");

		// 	// Fix for records with no paths
		// 	$level = 0;

		// 	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "collection_path` WHERE collection_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

		// 	foreach ($query->rows as $result) {
		// 		$this->db->query("INSERT INTO `" . DB_PREFIX . "collection_path` SET collection_id = '" . (int)$collection_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

		// 		$level++;
		// 	}

		// 	$this->db->query("REPLACE INTO `" . DB_PREFIX . "collection_path` SET collection_id = '" . (int)$collection_id . "', `path_id` = '" . (int)$collection_id . "', level = '" . (int)$level . "'");
		// }

		$this->db->query("DELETE FROM " . DB_PREFIX . "collection_filter WHERE collection_id = '" . (int)$collection_id . "'");

		if (isset($data['collection_filter'])) {
			foreach ($data['collection_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "collection_filter SET collection_id = '" . (int)$collection_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "collection_to_store WHERE collection_id = '" . (int)$collection_id . "'");

		// if (isset($data['collection_store'])) {
		// 	foreach ($data['collection_store'] as $store_id) {
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "collection_to_store SET collection_id = '" . (int)$collection_id . "', store_id = '" . (int)$store_id . "'");
		// 	}
		// }

		// SEO URL
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'collection_id=" . (int)$collection_id . "'");

		if (isset($data['collection_seo_url'])) {
			foreach ($data['collection_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'collection_id=" . (int)$collection_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
		
		// $this->db->query("DELETE FROM " . DB_PREFIX . "collection_to_layout WHERE collection_id = '" . (int)$collection_id . "'");

		// if (isset($data['collection_layout'])) {
		// 	foreach ($data['collection_layout'] as $store_id => $layout_id) {
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "collection_to_layout SET collection_id = '" . (int)$collection_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
		// 	}
		// }

		$this->cache->delete('collection');
	}

	public function deletecollection($collection_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "collection_path WHERE collection_id = '" . (int)$collection_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection_path WHERE path_id = '" . (int)$collection_id . "'");

		foreach ($query->rows as $result) {
			$this->deletecollection($result['collection_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "collection WHERE collection_id = '" . (int)$collection_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "collection_description WHERE collection_id = '" . (int)$collection_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "collection_filter WHERE collection_id = '" . (int)$collection_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "collection_to_store WHERE collection_id = '" . (int)$collection_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "collection_to_layout WHERE collection_id = '" . (int)$collection_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_collection WHERE collection_id = '" . (int)$collection_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'collection_id=" . (int)$collection_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "coupon_collection WHERE collection_id = '" . (int)$collection_id . "'");

		$this->cache->delete('collection');
	}

	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection WHERE parent_id = '" . (int)$parent_id . "'");

		// foreach ($query->rows as $collection) {
		// 	// Delete the path below the current one
		// 	$this->db->query("DELETE FROM `" . DB_PREFIX . "collection_path` WHERE collection_id = '" . (int)$collection['collection_id'] . "'");

		// 	// Fix for records with no paths
		// 	$level = 0;

		// 	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "collection_path` WHERE collection_id = '" . (int)$parent_id . "' ORDER BY level ASC");

		// 	foreach ($query->rows as $result) {
		// 		$this->db->query("INSERT INTO `" . DB_PREFIX . "collection_path` SET collection_id = '" . (int)$collection['collection_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

		// 		$level++;
		// 	}

		// 	$this->db->query("REPLACE INTO `" . DB_PREFIX . "collection_path` SET collection_id = '" . (int)$collection['collection_id'] . "', `path_id` = '" . (int)$collection['collection_id'] . "', level = '" . (int)$level . "'");

		// 	$this->repairCategories($collection['collection_id']);
		// }
	}

	public function getcollection($collection_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "collection c LEFT JOIN " . DB_PREFIX . "collection_description cd2 ON (c.collection_id = cd2.collection_id) WHERE c.collection_id = '" . (int)$collection_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}

	public function getCollections($data = array()) {
		$sql = "SELECT  c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "collection c1 LEFT JOIN " . DB_PREFIX . "collection_description cd2 ON (c1.collection_id = cd2.collection_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.collection_id";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getcollectionDescriptions($collection_id) {
		$collection_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection_description WHERE collection_id = '" . (int)$collection_id . "'");

		foreach ($query->rows as $result) {
			$collection_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'description'      => $result['description']
			);
		}

		return $collection_description_data;
	}
	
	// public function getcollectionPath($collection_id) {
	// 	$query = $this->db->query("SELECT collection_id, path_id, level FROM " . DB_PREFIX . "collection_path WHERE collection_id = '" . (int)$collection_id . "'");

	// 	return $query->rows;
	// }
	
	public function getcollectionFilters($collection_id) {
		$collection_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection_filter WHERE collection_id = '" . (int)$collection_id . "'");

		foreach ($query->rows as $result) {
			$collection_filter_data[] = $result['filter_id'];
		}

		return $collection_filter_data;
	}

	// public function getcollectionStores($collection_id) {
	// 	$collection_store_data = array();

	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection_to_store WHERE collection_id = '" . (int)$collection_id . "'");

	// 	foreach ($query->rows as $result) {
	// 		$collection_store_data[] = $result['store_id'];
	// 	}

	// 	return $collection_store_data;
	// }
	
	public function getcollectionSeoUrls($collection_id) {
		$collection_seo_url_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'collection_id=" . (int)$collection_id . "'");

		foreach ($query->rows as $result) {
			$collection_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $collection_seo_url_data;
	}
	
	// public function getcollectionLayouts($collection_id) {
	// 	$collection_layout_data = array();

	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection_to_layout WHERE collection_id = '" . (int)$collection_id . "'");

	// 	foreach ($query->rows as $result) {
	// 		$collection_layout_data[$result['store_id']] = $result['layout_id'];
	// 	}

	// 	return $collection_layout_data;
	// }

	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "collection");

		return $query->row['total'];
	}
	
	// public function getTotalCategoriesByLayoutId($layout_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "collection_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

	// 	return $query->row['total'];
	// }	
}