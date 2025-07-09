<?php
// delete_item.php
include 'includes/db.php';

// Validate ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    die('Invalid Item ID.');
}

// Optional: Confirm record exists
$stmt = $conn->prepare("SELECT id FROM items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die('Item not found.');
}

// Delete
$stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    // Redirect back with success message
    header("Location: salesitem.php?deleted=1");
    exit;
} else {
    die('Error deleting item.');
}
?>
<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit;
}
?>