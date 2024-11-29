<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    if (!empty($_POST['delete_ids'])) {
        $ids = implode(',', array_map('intval', $_POST['delete_ids']));
        $conn->exec("DELETE FROM products WHERE id IN ($ids)");
    }

    header('Location: ../public/index.php');
    exit;
}
?>
