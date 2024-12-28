<?php
class ModelInformationRequestcallback extends Model {
    public function addRequestcallback($data) {
		date_default_timezone_set("Asia/Kolkata");
		$date =   date("y-m-d H:i:s");
		$this->db->query("INSERT INTO " . DB_PREFIX . "request_callback SET name = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', mobile = '" . $this->db->escape($data['mobile']) . "',state = '" . $this->db->escape($data['state']) . "', message = '" . $this->db->escape($data['message']) . "', date_added = '" . $date . "'");

		$rc_id = $this->db->getLastId();
		return $rc_id;

	}

	public function addCatalogrequest($data) {
		date_default_timezone_set("Asia/Kolkata");
		$date =   date("y-m-d H:i:s");
		$this->db->query("INSERT INTO " . DB_PREFIX . "catalog_request SET name = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', mobile = '" . $this->db->escape($data['mobile']) . "', message = '" . $this->db->escape($data['message']) . "', date_added = '" . $date . "'");

	}
	public function addCareer( $data) {
		date_default_timezone_set("Asia/Kolkata");
		$date =   date("y-m-d H:i:s");
		$this->db->query("INSERT INTO " . DB_PREFIX . "career SET name = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', mobile = '" . $this->db->escape($data['mobile']) . "', message = '" . $this->db->escape($data['message']) . "', date_added = '" . $date . "'");

		$crer_id = $this->db->getLastId();
		return $crer_id;
    }
}