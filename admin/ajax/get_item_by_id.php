<?php
require '../includes/db.php';

$id = intval($_GET['id'] ?? 0);

if ($id) {
    $stmt = $conn->prepare("SELECT price, tax, hardwares FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($price, $tax, $hardwares);
    if ($stmt->fetch()) {
        echo json_encode([
            'price' => $price,
            'tax' => $tax,
            'hardwares' => $hardwares
        ]);
    } else {
        echo json_encode([]);
    }
    $stmt->close();
} else {
    echo json_encode([]);
}
?>
