<?php
class ModelMarketingCampaign extends Model {
	public function addMarketing($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "campaign SET campaign_name = '" . $this->db->escape($data['campaign_name']) . "', utm_source = '" . $this->db->escape($data['utm_source']) . "', utm_medium = '" . $this->db->escape($data['utm_medium']) . "', utm_term = '" . $this->db->escape($data['utm_term']) . "', date_added = NOW()");

		return $this->db->getLastId();
	}

	public function editMarketing($marketing_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "campaign SET campaign_name = '" . $this->db->escape($data['campaign_name']) . "', utm_source = '" . $this->db->escape($data['utm_source']) . "', utm_medium = '" . $this->db->escape($data['utm_medium']) . "', utm_term = '" . $this->db->escape($data['utm_term']) . "' WHERE campaign_id = '" . (int)$marketing_id . "'");
	}

	public function deleteMarketing($marketing_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "campaign WHERE campaign_id = '" . (int)$marketing_id . "'");
	}

	public function getMarketing($marketing_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "campaign WHERE campaign_id = '" . (int)$marketing_id . "'");
// print_r("SELECT DISTINCT * FROM " . DB_PREFIX . "campaign WHERE campaign_id = '" . (int)$marketing_id . "'");
		return $query->row;
	}

	public function getMarketingByCode($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "campaign WHERE utm_source = '" . $this->db->escape($code) . "'");
// print_r("SELECT DISTINCT * FROM " . DB_PREFIX . "campaign WHERE utm_source = '" . $this->db->escape($code) . "'");
		return $query->row;
	}

	public function getMarketings($data = array()) {
		$implode = array();

		$order_statuses = $this->config->get('config_complete_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
		}

		$sql = "SELECT *, (SELECT sum(total) FROM `" . DB_PREFIX . "order` o WHERE (" . implode(" OR ", $implode) . ") AND o.marketing_id = m.campaign_id) AS orders, (SELECT count(order_id) FROM `". DB_PREFIX ."order` od WHERE od.order_status_id > 0 AND od.marketing_id = m.campaign_id) as total_orders FROM " . DB_PREFIX . "campaign m";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "m.campaign_name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "m.utm_source = '" . $this->db->escape($data['filter_code']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(m.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'm.campaign_name',
			'm.utm_source',
			'm.utm_medium',
			'm.utm_term',
			'm.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY m.campaign_name";
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
				$data['limit'] = 10;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);
// print_r($sql);
		return $query->rows;
	}

	public function getTotalMarketings($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "campaign";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "campaign_name LIKE '" . $this->db->escape($data['filter_name']) . "'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "utm_source = '" . $this->db->escape($data['filter_code']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
// print_r($sql);
		return $query->row['total'];
	}

	public function getReport($data){
		$sql = "SELECT o.date_added as `date`,c.utm_source,o.order_id,o.total as product_price,c.utm_medium, o.total as total_sale  FROM " . DB_PREFIX . "order o LEFT JOIN " . DB_PREFIX . "campaign c on o.marketing_id = c.campaign_id";

		$implode = array();

		if (!empty($data['camp_id'])) {
			$implode[] = "o.marketing_id = '" . $this->db->escape($data['camp_id']) . "'";
		}

		if (!empty($data['start_date'])) {
			$implode[] = "DATE(o.date_added) >= DATE('" . $this->db->escape($data['start_date']) . "')";
		}

		if (!empty($data['end_date'])) {
			$implode[] = "DATE(o.date_added) <= DATE('" . $this->db->escape($data['end_date']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE (o.order_status_id=2 OR o.order_status_id=3 OR o.order_status_id=5) AND " . implode(" AND ", $implode);
		}
		
		// echo $sql;

		$query = $this->db->query($sql);
		if($query->num_rows == 0){
			$arr[] = array(
				'date'=> $data['start_date'],
				'utm_source'=> '',	
				'order_id' => '',	
				'product_price'=> '',
				'utm_medium' =>'',
				'total_sale' => ''
			);
			return $arr;//$query->rows;
		}else{
			return $query->rows;
		}
	}

}