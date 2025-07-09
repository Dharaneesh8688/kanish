
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

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if ($name !== '') {
        $stmt = $conn->prepare("INSERT INTO glasses (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        $stmt->execute();
        $stmt->close();

        // Redirect to the list page after successful insert
        header("Location: glass_list.php");
        exit;
    } else {
        $message = "Name is required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Add New Glass Type</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="row">
      <div class="col-md-3">
        <?php 
        // Include the sidebar
        include 'includes/sidebar.php';
        ?>

      </div>
      <div class="col-md-9">
        <div class="container">
          <!-- Main content will go here -->
            <h1 class="h3 mb-4">➕ Add New Glass Type</h1>

    <?php if ($message): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Glass Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control"></textarea>
      </div>
      <button type="submit" class="btn btn-success">Add Glass</button>
      <a href="glass_list.php" class="btn btn-secondary">Back to List</a>
    </form>
        </div>
    </div>
   
  </div>
</body>
</html>
<?php
$conn->close();
?>
