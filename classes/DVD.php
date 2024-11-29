<?php
require_once 'Product.php';

class DVD extends Product {
    private $size_mb;

    public function __construct($name, $sku, $price, $size_mb) {
        parent::__construct($name, $sku, $price);
        $this->size_mb = $size_mb;
    }

    public function saveToDatabase($conn) {
        $stmt = $conn->prepare("INSERT INTO products (name, sku, price, type, size_mb) VALUES (?, ?, ?, 'DVD', ?)");
        $stmt->execute([$this->name, $this->sku, $this->price, $this->size_mb]);
    }

    public function getDetails() {
        return "Size: {$this->size_mb} MB";
    }
}
?>
