<?php
include 'admin/includes/db.php';


$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$phone = trim($_POST["phone"]);
$message = trim($_POST["message"]);

if ($name && $email && $phone && $message) {
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    if ($stmt->execute()) {
        header("Location: contact_success.html");
        exit();
    } else {
        echo "Error saving message: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Please fill all fields.";
}

$conn->close();
?>
