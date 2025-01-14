<?php
class ModelInformationRequestcallback extends Model {
    public function addRequestcallback($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "request_callback SET name = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', mobile = '" . $this->db->escape($data['mobile']) . "', message = '" . $this->db->escape($data['message']) . "', date_added = NOW()");

		$rc_id = $this->db->getLastId();
		
		return $rc_id;
	}
}