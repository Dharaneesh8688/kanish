<?php
include '../includes/db.php';
$q = $_GET['q'] ?? '';
$sql = "SELECT id, phone FROM customers WHERE phone LIKE ? LIMIT 20";
$stmt = $conn->prepare($sql);
$search = "%$q%";
$stmt->bind_param('s', $search);
$stmt->execute();
$result = $stmt->get_result();
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = ['value' => $row['id'], 'label' => $row['phone']];
}
echo json_encode($data);
?>
