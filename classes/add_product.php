<?php
require_once 'db.php';
require_once 'DVD.php';
require_once 'Furniture.php';
require_once 'Book.php';

$db = new Database();
$conn = $db->getConnection();

$error = ''; // Переменная для хранения сообщений об ошибках

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = $_POST['name'] ?? '';
        $sku = $_POST['sku'] ?? '';
        $price = $_POST['price'] ?? '';
        $type = $_POST['type'] ?? '';

        // Проверка на обязательные поля
        if (empty($name) || empty($sku) || empty($price) || empty($type)) {
            throw new Exception('All fields are required.');
        }

        // Проверка цены
        if (!is_numeric($price) || $price <= 0) {
            throw new Exception('Price must be a positive number.');
        }

        // Проверка уникальности SKU
        $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE sku = :sku");
        $stmt->execute([':sku' => $sku]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception('The SKU ID must be unique.');
        }

        switch ($type) {
            case 'DVD':
                $size_mb = $_POST['size_mb'] ?? '';
                if (empty($size_mb) || !is_numeric($size_mb) || $size_mb <= 0) {
                    throw new Exception('Size (MB) is required and must be a positive number.');
                }
                $dvd = new DVD($name, $sku, $price, $size_mb);
                $dvd->saveToDatabase($conn);
                break;

            case 'Furniture':
                $height = $_POST['height_cm'] ?? '';
                $width = $_POST['width_cm'] ?? '';
                $length = $_POST['length_cm'] ?? '';

                if (empty($height) || empty($width) || empty($length) || 
                    !is_numeric($height) || !is_numeric($width) || !is_numeric($length) || 
                    $height <= 0 || $width <= 0 || $length <= 0) {
                    throw new Exception('Height, Width, and Length are required and must be positive numbers.');
                }

                $furniture = new Furniture($name, $sku, $price, $height, $width, $length);
                $furniture->saveToDatabase($conn);
                break;

            case 'Book':
                $weight_kg = $_POST['weight_kg'] ?? '';
                if (empty($weight_kg) || !is_numeric($weight_kg) || $weight_kg <= 0) {
                    throw new Exception('Weight (KG) is required and must be a positive number.');
                }
                $book = new Book($name, $sku, $price, $weight_kg);
                $book->saveToDatabase($conn);
                break;

            default:
                throw new Exception('Invalid product type selected.');
        }

        // Если всё прошло успешно, перенаправляем на главную страницу
        header("Location: ../public/index.php");
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage(); // Сохраняем текст ошибки
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="../style/addprod_style.css">
</head>
<body>
<header>
    Add New Product
</header>

<!-- Если есть ошибка, выводим её -->
<?php if (!empty($error)): ?>
    <div class="error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <div>
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
    </div>
    <div>
        <label for="sku">SKU:</label>
        <input type="text" id="sku" name="sku" value="<?= htmlspecialchars($_POST['sku'] ?? '') ?>">
    </div>
    <div>
        <label for="price">Price ($):</label>
        <input type="text" id="price" name="price" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
    </div>
    <div>
        <label for="type">Product Type:</label>
        <select id="type" name="type">
            <option value="">Select Type</option>
            <option value="DVD" <?= (isset($_POST['type']) && $_POST['type'] === 'DVD') ? 'selected' : '' ?>>DVD</option>
            <option value="Furniture" <?= (isset($_POST['type']) && $_POST['type'] === 'Furniture') ? 'selected' : '' ?>>Furniture</option>
            <option value="Book" <?= (isset($_POST['type']) && $_POST['type'] === 'Book') ? 'selected' : '' ?>>Book</option>
        </select>
    </div>

    <!-- Поля, которые появляются в зависимости от типа продукта -->
    <div id="dvd-fields" style="display: none;">
        <label for="size_mb">Size (MB):</label>
        <input type="text" id="size_mb" name="size_mb" value="<?= htmlspecialchars($_POST['size_mb'] ?? '') ?>">
    </div>
    <div id="furniture-fields" style="display: none;">
        <label for="height_cm">Height (CM):</label>
        <input type="text" id="height_cm" name="height_cm" value="<?= htmlspecialchars($_POST['height_cm'] ?? '') ?>">
        <label for="width_cm">Width (CM):</label>
        <input type="text" id="width_cm" name="width_cm" value="<?= htmlspecialchars($_POST['width_cm'] ?? '') ?>">
        <label for="length_cm">Length (CM):</label>
        <input type="text" id="length_cm" name="length_cm" value="<?= htmlspecialchars($_POST['length_cm'] ?? '') ?>">
    </div>
    <div id="book-fields" style="display: none;">
        <label for="weight_kg">Weight (KG):</label>
        <input type="text" id="weight_kg" name="weight_kg" value="<?= htmlspecialchars($_POST['weight_kg'] ?? '') ?>">
    </div>

    <div>
        <button type="submit">Save Product</button>
    </div>
</form>

<script>
    // Скрипт для отображения полей в зависимости от типа
    document.getElementById('type').addEventListener('change', function () {
        document.getElementById('dvd-fields').style.display = this.value === 'DVD' ? 'block' : 'none';
        document.getElementById('furniture-fields').style.display = this.value === 'Furniture' ? 'block' : 'none';
        document.getElementById('book-fields').style.display = this.value === 'Book' ? 'block' : 'none';
    });

    // Автопоказ полей, если была ошибка
    document.getElementById('type').dispatchEvent(new Event('change'));
</script>

</body>
</html>
