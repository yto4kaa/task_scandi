<?php
require_once 'Product.php';

class Furniture extends Product {
    private $height_cm;
    private $width_cm;
    private $length_cm;

    public function __construct($name, $sku, $price, $height_cm, $width_cm, $length_cm) {
        parent::__construct($name, $sku, $price);
        $this->height_cm = $height_cm;
        $this->width_cm = $width_cm;
        $this->length_cm = $length_cm;
    }

    public function saveToDatabase($conn) {
        $stmt = $conn->prepare("INSERT INTO products (name, sku, price, type, height_cm, width_cm, length_cm) VALUES (?, ?, ?, 'Furniture', ?, ?, ?)");
        $stmt->execute([$this->name, $this->sku, $this->price, $this->height_cm, $this->width_cm, $this->length_cm]);
    }

    public function getDetails() {
        return "Dimension: {$this->height_cm}x{$this->width_cm}x{$this->length_cm}";
    }
}
?>
