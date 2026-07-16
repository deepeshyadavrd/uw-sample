<?php
class ModelExtensionFeedSitemaps extends Model {
    public function getProducts() {

        $query = $this->db->query(" SELECT product_id, date_modified FROM " . DB_PREFIX . "product WHERE status = 1 ");

        return $query->rows;
    }

    public function getCategories() {

        $query = $this->db->query(" SELECT category_id FROM " . DB_PREFIX . "category WHERE status = 1 ");

        return $query->rows;
    }

    public function getInformations() {

        $query = $this->db->query(" SELECT information_id FROM " . DB_PREFIX . "information WHERE status = 1 ");

        return $query->rows;
    }
}
?>
