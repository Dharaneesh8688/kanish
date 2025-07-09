<?php
header('Content-Type: application/json');
include 'admin/includes/db.php';

// Sanitize input
$name = trim($_POST['name'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Please fill all fields']);
    exit;
}

// Insert feedback
$stmt = $conn->prepare("INSERT INTO feedbacks (name, message) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save feedback']);
}

$stmt->close();
?>
