<?php
class ModelCheckoutCampaign extends Model {
	public function getMarketingByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "campaign WHERE utm_source = '" . $this->db->escape($code) . "'");

		return $query->row;
	}
}