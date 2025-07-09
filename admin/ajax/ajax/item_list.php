<?php
include '../includes/db.php';

$q = isset($_GET['q']) ? $_GET['q'] : '';

$sql = "SELECT id, name FROM items WHERE name LIKE CONCAT('%', ?, '%') ORDER BY name ASC LIMIT 20";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $q);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'value' => $row['id'],
        'label' => $row['name']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
