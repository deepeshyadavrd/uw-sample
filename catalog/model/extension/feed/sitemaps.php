<?php
class ModelExtensionFeedSitemaps extends Model {
    public function getProducts() {

        $query = $this->db->query(" SELECT p.product_id, p.image, p.date_modified, pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.name not like '%custom%' AND pd.name not like '%test%' AND p.status = 1 AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ");

        return $query->rows;
    }

    public function getCategories() {

        $query = $this->db->query(" SELECT category_id FROM " . DB_PREFIX . "category WHERE status = 1 ");

        return $query->rows;
    }

    public function getInformations() {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) LEFT JOIN " . DB_PREFIX . "information_to_store i2s ON (i.information_id = i2s.information_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND i2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND i.status = '1' ORDER BY i.sort_order, LCASE(id.title) ASC");

        return $query->rows;
    }
}
?>
