
<?php include 'includes/header.php'; ?>

<?php include 'includes/db.php'; ?>
<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>
<?php
// Load DB connection


// Delete feedback
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM feedbacks WHERE id=$id");
    header("Location: feedback.php");
    exit;
}

// Fetch all feedbacks
$result = $conn->query("SELECT * FROM feedbacks ORDER BY created_at DESC");
$feedbacks = [];
if ($result) {
    while($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Feedback - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3  p-0">
      <?php include 'includes/sidebar.php'; ?>
    </div>

    <!-- Main content -->
    <div class="col-md-9  py-4">
      <div class="container">
        <h2 class="mb-4">Customer Feedback Management</h2>
  <?php if (empty($feedbacks)): ?>
    <div class="alert alert-info">No feedbacks found.</div>
  <?php else: ?>
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Message</th>
          <th>Approved</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($feedbacks as $fb): ?>
          <tr>
            <td><?php echo $fb['id']; ?></td>
            <td><?php echo htmlspecialchars($fb['name']); ?></td>
            <td><?php echo htmlspecialchars($fb['message']); ?></td>
            <td>
              <?php echo $fb['approved'] ? '<span class="badge bg-success">Approved</span>' : '<span class="badge bg-secondary">Pending</span>'; ?>
            </td>
            <td><?php echo date("d M Y", strtotime($fb['created_at'])); ?></td>
            <td>
              <a href="?delete=<?php echo $fb['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this feedback?');">
                <i class="bi bi-trash"></i> Delete
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
      </div> <!-- .container -->
    </div> <!-- .col -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->
</body>
</html>

</body>
</html>
