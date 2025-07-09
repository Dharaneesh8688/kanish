<?php
include 'includes/db.php';

// Add employee
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO employees (name, email, phone, role, password)
              VALUES ('$name', '$email', '$phone', '$role', '$password')";
    mysqli_query($conn, $query);
    header("Location: employeedetails.php");
    exit;
}

// Update employee
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $query = "UPDATE employees SET name='$name', email='$email', phone='$phone', role='$role' WHERE id=$id";
    mysqli_query($conn, $query);
    header("Location: employeedetails.php");
    exit;
}

// Delete employee
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM employees WHERE id=$id");
    header("Location: employeedetails.php");
    exit;
}
