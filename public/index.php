\<?php
require_once '../classes/db.php';

$db = new Database();
$conn = $db->getConnection();

// Получаем все продукты из базы данных
$stmt = $conn->query("SELECT * FROM products ORDER BY id ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="../style/style.css"> <!-- Подключаем стили -->
</head>
<body>

<header>
    Product List
</header>

<!-- Кнопки добавления и массового удаления -->
<div class="actions">
    <a href="../classes/add_product.php"><button>Add New Product</button></a>
    <button id="delete-product-btn" form="delete-form">Mass Delete</button>
</div>

<!-- Форма для массового удаления -->
<form method="POST" action="../classes/delete_product.php" id="delete-form">
    <main class="main">
        <?php
        // Выводим каждый продукт
        foreach ($products as $product) {
            $attribute = '';
            // Определяем атрибут в зависимости от типа продукта
            switch ($product['type']) {
                case 'DVD':
                    $attribute = "Size: {$product['size_mb']} MB"; // Размер DVD
                    break;
                case 'Book':
                    $attribute = "Weight: {$product['weight_kg']} KG"; // Вес книги
                    break;
                case 'Furniture':
                    $attribute = "Dimension: {$product['height_cm']}x{$product['width_cm']}x{$product['length_cm']}"; // Размер мебели
                    break;
            }
            // Выводим данные продукта без ID
            echo "<div class='product-card'>
                    <input type='checkbox' class='delete-checkbox' name='delete_ids[]' value='{$product['id']}'>
                    <h3>{$product['name']}</h3>
                    <div class='price'>{$product['price']} $</div>
                    <div class='attribute'>{$attribute}</div>
                  </div>";
        }
        ?>
    </main>
</form>

<footer>
    Scandiweb Test Assignment
</footer>

</body>
</html>

