<?php
class ModelToolUpload extends Model {
	public function addUpload($name, $filename) {
		$code = sha1(uniqid(mt_rand(), true));

		$this->db->query("INSERT INTO `" . DB_PREFIX . "upload` SET `name` = '" . $this->db->escape($name) . "', `filename` = '" . $this->db->escape($filename) . "', `code` = '" . $this->db->escape($code) . "', `date_added` = NOW()");

		return $code;
	}

	public function getUploadByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}
	public function addHeadshot($customerid, $filepath){
		//echo $customerid;
		//echo $filepath;

		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `profile_image`='". $filepath ."' WHERE customer_id=". $customerid);
	}

	public function addReviewImages($reviewid, $filepath){
		//echo $customerid;
		//echo $filepath;

		$this->db->query("INSERT INTO `" . DB_PREFIX . "review_images`(`review_id`, `review_image`) VALUES (". $reviewid .",'". $filepath . "')");
	}
	public function addCustomImages($cfrid, $filepath){
		//echo $customerid;
		//echo $filepath;

		$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_furniture_request_images`(`cfr_id`, `custom_furniture_image`) VALUES (". $cfrid .",'". $filepath . "')");
	}
}