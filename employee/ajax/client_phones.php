<?php
session_start();
include '../includes/db.php';

$created_by = $_SESSION['employee_name'] ?? '';
$q = $_GET['q'] ?? '';

if (empty($created_by)) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id, phone FROM customers WHERE created_by = ? AND phone LIKE ? LIMIT 20";
$stmt = $conn->prepare($sql);

$search = "%$q%";
$stmt->bind_param('ss', $created_by, $search);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = ['value' => $row['id'], 'label' => $row['phone']];
}
echo json_encode($data);
?>
