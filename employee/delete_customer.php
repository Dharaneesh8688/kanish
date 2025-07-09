<?php
include 'includes/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: customers.php?deleted=1");
        exit();
    } else {
        // Redirect with error
        header("Location: customers.php?error=delete_failed");
        exit();
    }
} else {
    header("Location: customers.php?error=invalid_id");
    exit();
}
?>
<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit;
}
?>