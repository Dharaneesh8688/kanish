<?php
include 'includes/header.php';

include 'includes/db.php';

// Fetch employees
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
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar column -->
    <div class="col-md-3  p-0">
      <?php include 'includes/sidebar.php'; ?>
    </div>

    <!-- Main content column -->
    <div class="col-md-9  mt-4">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h3>All Employees</h3>
          <a href="employee.php" class="btn btn-success">➕ Add New Employee</a>
        </div>

        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>#ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Role</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = mysqli_fetch_assoc($employees)): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                  <a href="employee.php?edit_id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="process_employee.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete this employee?')" class="btn btn-sm btn-danger">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div> <!-- .container -->
    </div> <!-- .col -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->

