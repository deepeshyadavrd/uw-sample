<?php
class ModelDesignHomeSection extends Model {
	public function addHomepageSection($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "home_section SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "'");

		$home_section_id = $this->db->getLastId();

		if (isset($data['home_section_image'])) {
			foreach ($data['home_section_image'] as $language_id => $value) {
				foreach ($value as $home_section_image) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "home_section_image SET home_section_id = '" . (int)$home_section_id . "', language_id = '" . (int)$language_id . "', title = '" .  $this->db->escape($home_section_image['title']) . "', link = '" .  $this->db->escape($home_section_image['link']) . "', image = '" .  $this->db->escape($home_section_image['image']) . "', price = '" .  $this->db->escape($home_section_image['price']) . "', sort_order = '" .  (int)$home_section_image['sort_order'] . "'");
				}
			}
		}

		return $home_section_id;
	}

	public function editHomeSection($home_section_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "home_section SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "' WHERE home_section_id = '" . (int)$home_section_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "home_section_image WHERE home_section_id = '" . (int)$home_section_id . "'");

		if (isset($data['home_section_image'])) {
			foreach ($data['home_section_image'] as $language_id => $value) {
				foreach ($value as $home_section_image) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "home_section_image SET home_section_id = '" . (int)$home_section_id . "', language_id = '" . (int)$language_id . "', title = '" .  $this->db->escape($home_section_image['title']) . "', link = '" .  $this->db->escape($home_section_image['link']) . "', image = '" .  $this->db->escape($home_section_image['image']) . "', price = '" .  $this->db->escape($home_section_image['price']) . "', sort_order = '" . (int)$home_section_image['sort_order'] . "'");
				}
			}
		}
	}

	public function deleteHomeSection($home_section_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "home_section WHERE home_section_id = '" . (int)$home_section_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "home_section_image WHERE home_section_id = '" . (int)$home_section_id . "'");
	}

	public function getHomeSection($home_section_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "home_section WHERE home_section_id = '" . (int)$home_section_id . "'");

		return $query->row;
	}

	public function getHomeSections($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "home_section";

		$sort_data = array(
			'name',
			'status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function getHomeSectionImages($home_section_id) {
		$home_section_image_data = array();

		$home_section_image_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "home_section_image WHERE home_section_id = '" . (int)$home_section_id . "' ORDER BY sort_order ASC");

		foreach ($home_section_image_query->rows as $home_section_image) {
			$home_section_image_data[$home_section_image['language_id']][] = array(
				'title'      => $home_section_image['title'],
				'link'       => $home_section_image['link'],
				'image'      => $home_section_image['image'],
				'price'		 => $home_section_image['price'],
				'sort_order' => $home_section_image['sort_order']
			);
		}

		return $home_section_image_data;
	}

	public function getTotalHomepageSections() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "home_section");

		return $query->row['total'];
	}
}
