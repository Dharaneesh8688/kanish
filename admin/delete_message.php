<?php
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: view_messages.php");
        exit();
    } else {
        echo "Error deleting message.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
