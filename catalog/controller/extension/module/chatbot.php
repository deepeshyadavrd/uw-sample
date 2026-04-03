<?php

class ControllerExtensionModuleChatbot extends Controller {
    
    public function index() {

        $intent = $this->request->post['intent'];
        $category = $this->request->post['category'];
        $budget = $this->request->post['budget'];

        // 🟢 PRODUCT SEARCH
        if ($intent == "product_search" && $category) {

            $sql = "SELECT p.product_id, pd.name, p.price, p.image 
                    FROM " . DB_PREFIX . "product p
                    LEFT JOIN " . DB_PREFIX . "product_description pd 
                    ON p.product_id = pd.product_id
                    WHERE pd.name LIKE '%" . $this->db->escape($category) . "%'";

            if ($budget) {
                $sql .= " AND p.price <= " . (int)$budget;
            }

            $sql .= " LIMIT 5";

            $query = $this->db->query($sql);

            $products = [];

            foreach ($query->rows as $row) {
                $products[] = [
                    "name" => $row['name'],
                    "price" => $row['price'],
                    "image" => "image/" . $row['image']
                ];
            }

            echo json_encode([
                "type" => "products",
                "products" => $products
            ]);
        }

        // 🟡 INFORMATION RESPONSE (NO AI YET)
        else {

            $reply = "I can help you choose furniture. Try asking like 'sofa under 20000' or 'best wood for bed'.";

            if (strpos($this->request->post['message'], "wood") !== false) {
                $reply = "Sheesham wood is strong and long-lasting. Mango wood is more affordable.";
            }

            echo json_encode([
                "type" => "text",
                "reply" => $reply
            ]);
        }
    }
}