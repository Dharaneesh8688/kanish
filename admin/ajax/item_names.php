<?php
include '../includes/db.php';

$id = $_GET['id'] ?? 0;
$sql = "SELECT sales_price, tax, hardwares FROM items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

echo json_encode([
  'price' => $item['sales_price'] ?? 0,
  'tax' => $item['tax'] ?? 0,
  'hardwares' => $item['hardwares'] ?? 0
]);
?>

