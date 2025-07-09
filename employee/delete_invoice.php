<?php
// delete_invoice.php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Optional: you can also validate whether this ID exists first if you want

    $stmt = $conn->prepare("DELETE FROM add_invoice WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: invoices.php?deleted=1");
        exit();
    } else {
        echo "Error deleting invoice: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
