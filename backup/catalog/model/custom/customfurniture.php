<?php
class ModelCustomCustomfurniture extends Model {
	public function addCustomRequest( $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "custom_furniture_request SET name = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', mobile = '" . $this->db->escape($data['mobile']) . "', pincode = '" . $this->db->escape($data['pincode']) . "', city = '" . $this->db->escape($data['city']) . "', message = '" . $this->db->escape($data['message']) . "', date_added = NOW()");

		$cfr_id = $this->db->getLastId();
		return $cfr_id;
    }
	public function addToWhatsappList( $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "whatsapp_list SET name = '" . $this->db->escape($data['name']) . "', whatsapp_number = '" . $this->db->escape($data['telephone']) . "', city = '" . $this->db->escape($data['city']) . "', date_added = NOW()");

		$walist_id = $this->db->getLastId();
		return $walist_id;
    }
}