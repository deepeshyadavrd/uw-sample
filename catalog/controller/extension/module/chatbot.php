<?php
class ControllerExtensionModuleChatbot extends Controller {

    public function index() {

        $message = strtolower($this->request->post['message']);

        // 🔹 INTENT DETECTION
        $intent = "general";

        if (preg_match('/\d+/', $message) || strpos($message, 'under') !== false || strpos($message, 'price') !== false) {
            $intent = "product_search";
        } 
        elseif (
            strpos($message, 'which') !== false ||
            strpos($message, 'better') !== false ||
            strpos($message, 'vs') !== false ||
            strpos($message, 'difference') !== false ||
            strpos($message, 'compare') !== false
        ) {
            $intent = "information";
        }

        // 🔹 CATEGORY DETECTION
        $categories = ['sofa','bed','table','chair','dining','cabinet','tv unit'];
        $category = '';

        foreach ($categories as $cat) {
            if (strpos($message, $cat) !== false) {
                $category = $cat;
                break;
            }
        }

        // 🔹 PRICE DETECTION
        preg_match('/\d+/', $message, $matches);
        $budget = isset($matches[0]) ? (int)$matches[0] : 0;

        // ===========================
        // 🛋️ PRODUCT SEARCH
        // ===========================
        if ($intent == "product_search" && $category) {

            $sql = "SELECT p.product_id, pd.name, p.price, p.image 
                    FROM " . DB_PREFIX . "product p
                    LEFT JOIN " . DB_PREFIX . "product_description pd 
                    ON p.product_id = pd.product_id
                    WHERE pd.name LIKE '%" . $this->db->escape($category) . "%'";

            if ($budget > 0) {
                $sql .= " AND p.price <= " . (int)$budget;
            }

            $sql .= " LIMIT 5";

            $query = $this->db->query($sql);

            $products = [];

            foreach ($query->rows as $row) {
                $products[] = [
                    "name" => $row['name'],
                    "price" => $row['price'],
                    "image" => "image/" . $row['image'],
                    "link" => $this->url->link('product/product', 'product_id=' . $row['product_id'])
                ];
            }

            echo json_encode([
                "type" => "products",
                "products" => $products
            ]);
            return;
        }

        // ===========================
        // 🧠 INFORMATION → DEEPSEEK
        // ===========================
        if ($intent == "information") {

            $api_key = $_ENV['DS_API_KEY'];

            $data = [
                "model" => "deepseek-chat",
                "messages" => [
                    [
                        "role" => "system",
                        "content" => "You are a furniture expert for Urbanwood. Answer briefly in bullet points comparing materials, durability, price and usage."
                    ],
                    [
                        "role" => "user",
                        "content" => $message
                    ]
                ]
            ];

            $ch = curl_init("https://api.deepseek.com/v1/chat/completions");

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Authorization: Bearer " . $api_key
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            $reply = $result['choices'][0]['message']['content'] ?? "Sorry, try again.";

            echo json_encode([
                "type" => "text",
                "reply" => $reply
            ]);
            return;
        }

        // ===========================
        // 💬 DEFAULT RESPONSE
        // ===========================
        echo json_encode([
            "type" => "text",
            "reply" => "Try asking like 'sofa under 20000' or 'sheesham vs mango wood'."
        ]);
    }
}