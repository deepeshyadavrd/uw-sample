<?php
class ModelTestAbc extends Model {
	public function getProductByCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p2c.category_id = '" . (int)$category_id . "'");
        $implode = array();
        $results = $query->rows;
        while ($ra = $results->fetch_assoc()) {
			$implode[] = $ra;
		}

		return $implode;
	}
}
?>