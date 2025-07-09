<?php
include 'includes/db.php';

// Validate the ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    die('Invalid Quotation ID.');
}

// OPTIONAL: You could add authentication checks here

// Prepare and delete the record
$stmt = $conn->prepare("DELETE FROM quotations WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Redirect back to the quotations list
    header("Location: quotation.php");
    exit;
} else {
    echo "Error deleting quotation.";
}
?>
<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit;
}
?>