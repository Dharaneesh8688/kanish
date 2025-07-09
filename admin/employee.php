<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/db.php';

$employees = mysqli_query($conn, "SELECT * FROM employees");

$editMode = false;
$editData = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $result = mysqli_query($conn, "SELECT * FROM employees WHERE id=$id");
    $editData = mysqli_fetch_assoc($result);
    $editMode = true;
}
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

?>

<div class="container mt-4">
    <h3><?= $editMode ? 'Edit Employee' : 'Add New Employee' ?></h3>
    <form action="process_employee.php" method="POST">
        <?php if ($editMode): ?>
            <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <?php endif; ?>
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required class="form-control" value="<?= $editData['name'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required class="form-control" value="<?= $editData['email'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" required class="form-control" value="<?= $editData['phone'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label>Role</label>
            <input type="text" name="role" required class="form-control" value="<?= $editData['role'] ?? '' ?>">
        </div>

        <?php if (!$editMode): ?>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required class="form-control">
        </div>
        <?php endif; ?>

        <button type="submit" name="<?= $editMode ? 'update' : 'add' ?>" class="btn btn-primary">
            <?= $editMode ? 'Update Employee' : 'Add Employee' ?>
        </button>
    </form>

    <hr>
    
</div>
