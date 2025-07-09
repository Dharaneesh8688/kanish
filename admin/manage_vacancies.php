<?php include 'includes/header.php'; ?>

<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
  header("Location: admin_login.php");
  exit;
}
include 'includes/db.php';

// Handle delete
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $conn->query("DELETE FROM vacancies WHERE id = $id");
  header("Location: manage_vacancies.php");
  exit;
}

$result = $conn->query("SELECT * FROM vacancies ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Vacancies</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar column -->
    <div class="col-md-3 col-lg-2 p-0">
      <?php include 'includes/sidebar.php'; ?>
    </div>

    <!-- Main content column -->
    <div class="col-md-9 col-lg-10 py-4">
      <div class="container">
        <h2 class="mb-4">Manage Vacancies</h2>
        <a href="add_vacancy.php" class="btn btn-success mb-3">Add New Vacancy</a>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Title</th>
              <th>Location</th>
              <th>Qualification</th>
              <th>Experience</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['title']) ?></td>
                  <td><?= htmlspecialchars($row['location']) ?></td>
                  <td><?= htmlspecialchars($row['qualification']) ?></td>
                  <td><?= htmlspecialchars($row['experience']) ?></td>
                  <td><?= htmlspecialchars($row['created_at']) ?></td>
                  <td>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this vacancy?')" class="btn btn-sm btn-danger">Delete</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="6">No vacancies posted.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</body>
</html>
<?php $conn->close(); ?>
