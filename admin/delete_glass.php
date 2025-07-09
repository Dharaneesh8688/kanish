<?php
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Delete the record
    $stmt = $conn->prepare("DELETE FROM glasses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to the list
header("Location: glass_list.php");
exit;
?>
