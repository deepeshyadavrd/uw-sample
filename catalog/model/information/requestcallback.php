<?php
class ModelInformationRequestcallback extends Model {
    public function addRequestcallback($data) {

		date_default_timezone_set("Asia/Kolkata");
		$date = date("Y-m-d H:i:s");
	
		// Support both city and state from different frontend forms
		$location = '';
	
		if (isset($data['city']) && trim($data['city']) != '') {
			$location = $data['city'];
		} elseif (isset($data['state'])) {
			$location = $data['state'];
		}
	
		// Support both telephone and mobile
		$mobile = isset($data['telephone']) ? $data['telephone'] : ($data['mobile'] ?? '');
	
		$name = $data['name'] ?? '';
		$email = $data['email'] ?? '';
		$message = $data['message'] ?? '';
	
		$furniture_type = $data['furniture_type'] ?? '';
		$budget = $data['budget'] ?? '';
		$purchase_timeline = $data['purchase_timeline'] ?? '';
		$contact_time = $data['contact_time'] ?? '';
	
		$this->db->query("INSERT INTO " . DB_PREFIX . "request_callback SET
			name = '" . $this->db->escape($name) . "',
			email = '" . $this->db->escape($email) . "',
			mobile = '" . $this->db->escape($mobile) . "',
			state = '" . $this->db->escape($location) . "',
			message = '" . $this->db->escape($message) . "',
			furniture_type = '" . $this->db->escape($furniture_type) . "',
			budget = '" . $this->db->escape($budget) . "',
			purchase_timeline = '" . $this->db->escape($purchase_timeline) . "',
			contact_time = '" . $this->db->escape($contact_time) . "',
			date_added = '" . $date . "'
		");
	
		return $this->db->getLastId();
	}
	public function addPriceRequest($data) {
		date_default_timezone_set("Asia/Kolkata");
		$date =   date("y-m-d H:i:s");
		$this->db->query("INSERT INTO " . DB_PREFIX . "request_callback SET name = '" . $this->db->escape($data['c_name']) . "', email = '" . $this->db->escape($data['c_email']) . "', mobile = '" . $this->db->escape($data['c_mobile']) . "',state = '" . $this->db->escape($data['c_state']) . "', message = 'Price For -" . $this->db->escape($data['product']) . "', date_added = '" . $date . "'");

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
	public function addFranchiseRequest($data) {
		// print_r($data);
		$this->db->query("INSERT INTO " . DB_PREFIX . "franchise_request SET name = '" . $this->db->escape($data['fullname']) . "', email = '" . $this->db->escape($data['email']) . "', mobile = '" . $this->db->escape($data['mobile']) . "', city = '" . $this->db->escape($data['city']) . "', franchisee = '" . $this->db->escape($data['franchisee']) . "', retail_space = '" . $this->db->escape($data['retail_space']) . "', amount = '" . $this->db->escape($data['investamt']) . "', msg = '" . $this->db->escape($data['msg']) . "', date_added = NOW(), status = 1");

		$rc_id = $this->db->getLastId();
		
		return $rc_id;
	}
	public function getFranchiseByEmailOrMobile($email, $mobile) {
    	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "franchise_request WHERE email = '" . $this->db->escape($email) . "' OR mobile = '" . $this->db->escape($mobile) . "'");

    	return $query->row; // will return false if not found
	}
}