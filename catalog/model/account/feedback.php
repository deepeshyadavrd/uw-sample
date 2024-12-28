<?php
class ModelAccountFeedback extends Model {
	public function addFeedback($customer_id, $data) {
        $advice = implode(",",$data["advice"]);
		$this->db->query("INSERT INTO " . DB_PREFIX . "feedback SET customer_id = '" . (int)$customer_id . "', reaction = '" . $this->db->escape($data['reaction']) . "', advice = '" . $this->db->escape($advice) . "', user_comment = '" . $this->db->escape($data['usercomment']). "', date_added = NOW()");

		$feedback_id = $this->db->getLastId();


		return $feedback_id;
	}

	public function getFeedback($customer_id) {
		

		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "feedback WHERE customer_id = " .$customer_id );

			$feedback = $query->rows;

			//$this->cache->set('country.catalog', $country_data);
		

		return $feedback;
	}
}