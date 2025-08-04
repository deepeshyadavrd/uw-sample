<?php
class ModelInformationRequestcallback extends Model {
    public function addRequestcallback($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "request_callback SET name = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', mobile = '" . $this->db->escape($data['mobile']) . "', message = '" . $this->db->escape($data['message']) . "', date_added = NOW()");

		$rc_id = $this->db->getLastId();
		
		return $rc_id;
	}
	public function addFrachiseRequest($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "request_franchise SET name = '" . $this->db->escape($data['fullname']) . "', email = '" . $this->db->escape($data['email']) . "', mobile = '" . $this->db->escape($data['mobile']) . "', city = '" . $this->db->escape($data['city']) . "', franchisee = '" . $this->db->escape($data['franchisee']) . "', retail_space = '" . $this->db->escape($data['retail_space']) . "', amount = '" . $this->db->escape($data['investamt']) . "', message = '" . $this->db->escape($data['msg']) . "', date_added = NOW(), status = 1");

		$rc_id = $this->db->getLastId();
		
		return $rc_id;
	}
}