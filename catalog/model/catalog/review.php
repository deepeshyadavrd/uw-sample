<?php
class ModelCatalogReview extends Model {
	public function addReview($product_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', image = '" . $data['image'] . "', date_added = NOW()");

		$review_id = $this->db->getLastId();

		if (in_array('review', (array)$this->config->get('config_mail_alert'))) {
			$this->load->language('mail/review');
			$this->load->model('catalog/product');
			
			$product_info = $this->model_catalog_product->getProduct($product_id);

			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$message  = $this->language->get('text_waiting') . "\n";
			$message .= sprintf($this->language->get('text_product'), html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_reviewer'), html_entity_decode($data['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_rating'), $data['rating']) . "\n";
			$message .= $this->language->get('text_review') . "\n";
			$message .= html_entity_decode($data['text'], ENT_QUOTES, 'UTF-8') . "\n\n";

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();

			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_mail_alert_email'));

			foreach ($emails as $email) {
				if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
		return $review_id;
	}

	public function getReviewsByProductId($product_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT r.review_id, r.customer_id, r.author, r.rating, r.text,r.image as r_image, p.product_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getReviews( $data = array(), $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}
		$sql = "SELECT r.review_id, r.author, r.rating, r.text, r.city, p.product_id, pd.name, pd.main_category, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		if(!empty($data['city'])) {
			$sql .= " AND city='". $data['city']."'";
		}else{
			$sql .= "";
		}
		if(!empty($data['date'])) {
			if($data['date'] == '30days'){
			$sql .= " AND r.date_added BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()";
			}elseif($data['date'] == '3months'){
				$sql .= "AND r.date_added BETWEEN NOW() - INTERVAL 3 MONTH AND NOW()";
			}else{
				$sql .= " AND YEAR(r.date_added) = '". $data['date'] ."'";
			}
		}else{
			$sql .= "";
		}
		if(!empty($data['category'])) {
			$sql .= " AND r.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category where category_id=".$data['category'] . ")";
		}else{
			$sql .= "";
		}
		$sql .=  " ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit;
		//echo $sql;
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalReviewsByProductId($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}

	public function getTotalReviews($data = array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		if(!empty($data['city'])) {
			$sql .= " AND city='". $data['city'] ."'";
		}else{
			$sql .= "";
		}
		if(!empty($data['date'])) {
			if($data['date'] == '30days'){
			$sql .= " AND r.date_added BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()";
			}elseif($data['date'] == '3months'){
				$sql .= "AND r.date_added BETWEEN NOW() - INTERVAL 3 MONTH AND NOW()";
			}else{
				$sql .= " AND YEAR(r.date_added) = '". $data['date'] ."'";
			}
		}else{
			$sql .= "";
		}
		if(!empty($data['category'])) {
			$sql .= " AND r.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category where category_id=".$data['category'] . ")";
		}else{
			$sql .= "";
		}
		
//echo $sql;
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getReviewsByCategoryId($categoryid){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "review WHERE product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category where category_id=".$categoryid . ")");

		return $query->rows;
	}
	
	public function getCity(){
		$query = $this->db->query("SELECT DISTINCT(city) FROM " . DB_PREFIX . "review ORDER BY city ASC");

		return $query->rows;
	}

	public function getYears(){
		$query = $this->db->query("SELECT DISTINCT(YEAR(date_added)) as year FROM " . DB_PREFIX . "review");

		return $query->rows;
	}

	public function customerStories(){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "review WHERE rating>=4 limit 10");

		return $query->rows;
	}

	public function getReviewImages($reviewid){
		$query = $this->db->query("SELECT review_image FROM " . DB_PREFIX . "review_images WHERE review_id=". $reviewid);

		return $query->rows;
	}
}