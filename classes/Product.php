<?php

class Product {
    protected $name;
    protected $sku;
    protected $price;

    public function __construct($name, $sku, $price) {
        $this->name = $name;
        $this->sku = $sku;
        $this->price = $price;
    }

    public function saveToDatabase($conn) {
        // Реализуется в дочернем классе
    }

    public function getDetails() {
        // Реализуется в дочернем классе
    }
}
?>
