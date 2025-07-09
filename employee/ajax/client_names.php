<?php
include '../includes/db.php';

$q = $_GET['q'] ?? '';

$sql = "SELECT id, company_name, phone FROM customers WHERE company_name LIKE ? LIMIT 20";
$stmt = $conn->prepare($sql);
$search = "%$q%";
$stmt->bind_param('s', $search);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'text' => $row['company_name'],
        'phone' => $row['phone'] // ✅ include phone for autofill
    ];
}

echo json_encode($data);
?>
