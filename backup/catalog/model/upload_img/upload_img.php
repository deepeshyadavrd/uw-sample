<?php
class ModelUploadImgUploadImg extends Model {
    public function getImg(){

         $query= $this->db->query("SELECT * FROM `oc_custom_product`");
        //  print_r($query->rows);

       if ($query->num_rows) {
			return $query->rows;
		} else {
			return 0;
		}

    }
    

      public function product_name($id) {
        $query = $this->db->query("SELECT DISTINCT name FROM " . DB_PREFIX . "product_description WHERE product_id = " . (int)$id );
        // print_r($query);

       if ($query->num_rows) {
         return $query->row['name'];
     } else {
   return 0;
 }

 }


}

	