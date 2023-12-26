<?php
class ModelExtensionModuleImportProducts extends Model {
    public function importProducts($data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product");

        foreach ($data['products'] as $product) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product SET 
                model = '" . $this->db->escape($product['model']) . "',
                price = '" . (float)$product['price'] . "',
                quantity = '" . (int)$product['quantity'] . "'
            ");

            $product_id = $this->db->getLastId();

            $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
                product_id = '" . (int)$product_id . "',
                name = '" . $this->db->escape($product['name']) . "',
                description = '" . $this->db->escape($product['description']) . "'
            ");
        }
    }
}
?>