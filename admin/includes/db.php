<?php
$servername = "localhost";     // Use your DB server name or IP
$username   = "root";          // Replace with your DB username
$password   = "";              // Replace with your DB password
$database   = "uwindows";      // Replace with your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
