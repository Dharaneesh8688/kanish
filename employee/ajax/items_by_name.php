<?php
include '../includes/db.php';
$q = $_GET['q'] ?? '';
$sql = "SELECT id, item_name FROM items WHERE item_name LIKE ? LIMIT 20";
$stmt = $conn->prepare($sql);
$search = "%$q%";
$stmt->bind_param('s', $search);
$stmt->execute();
$result = $stmt->get_result();
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = ['value' => $row['id'], 'label' => $row['item_name']];
}
echo json_encode($data);
?>
    