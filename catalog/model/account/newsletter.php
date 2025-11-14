<?php
class ModelAccountNewsletter extends Model {
    public function addToNewsletter($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET  email = '" . $this->db->escape($data['email']) . "', date_added = NOW()");

	}
    public function checkExisting($data) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsletter WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($data['email'])) . "'");

		return $query->row['total'];
	}
}