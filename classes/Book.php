<?php
require_once 'Product.php';

class Book extends Product {
    private $weight_kg;

    public function __construct($name, $sku, $price, $weight_kg) {
        parent::__construct($name, $sku, $price);
        $this->weight_kg = $weight_kg;
    }

    public function saveToDatabase($conn) {
        $stmt = $conn->prepare("INSERT INTO products (name, sku, price, type, weight_kg) VALUES (?, ?, ?, 'Book', ?)");
        $stmt->execute([$this->name, $this->sku, $this->price, $this->weight_kg]);
    }

    public function getDetails() {
        return "Weight: {$this->weight_kg} KG";
    }
}
?>
