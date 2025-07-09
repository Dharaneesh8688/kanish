

<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>
<?php
include 'includes/header.php';

include 'includes/db.php';

// fetch glass types
$result = $conn->query("SELECT * FROM glasses ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Glass Types Available</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function confirmDelete(name, id) {
      if (confirm("Are you sure you want to delete \"" + name + "\"?")) {
        window.location.href = "delete_glass.php?id=" + id;
      }
    }
  </script>
</head>
<body class="bg-light">
<div class="container-fluid">
  <div class="row min-vh-100">
    
    <!-- Sidebar column -->
    <div class="col-md-3 col-lg-2 bg-white border-end p-0">
      <?php include 'includes/sidebar.php'; ?>
    </div>

    <!-- Main content column -->
    <div class="col-md-9 col-lg-10 py-4">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 class="h3">🧊 Glass Types Available</h1>
          <a href="add_glass.php" class="btn btn-primary">➕ Add New Glass</a>
        </div>

        <?php if ($result->num_rows > 0): ?>
          <div class="list-group">
            <?php while($row = $result->fetch_assoc()): ?>
              <div class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                  <h5 class="mb-1"><?= htmlspecialchars($row['name']); ?></h5>
                  <p class="mb-0"><?= htmlspecialchars($row['description']); ?></p>
                </div>
                <button 
                  class="btn btn-sm btn-danger"
                  onclick="confirmDelete('<?= htmlspecialchars($row['name']); ?>', <?= (int)$row['id']; ?>)">
                  🗑️ Delete
                </button>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <div class="alert alert-info">
            No glass types found. <a href="add_glass.php">Add one now</a>.
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
</body>

</html>

<?php
$conn->close();
?>
