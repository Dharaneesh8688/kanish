<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
include 'includes/db.php';
include 'includes/header.php';
$result = $conn->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Contact Messages</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <script>
    function confirmDelete(id) {
      if (confirm("Are you sure you want to delete this message?")) {
        window.location.href = "delete_message.php?id=" + id;
      }
    }
  </script>
  <style>
    /* Desktop: leave margin for sidebar */
    .main-content {
      margin-left: 250px;
      padding: 20px;
    }
    /* Mobile: remove margin */
    @media (max-width: 767.98px) {
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body class="bg-light">
  <?php include 'includes/sidebar.php'; ?>

  <div class="main-content">
    <h2 class="mb-4">Contact Messages</h2>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Message</th>
            <th>Submitted At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                <td>
                  <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $row['id'] ?>)">Delete</button>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center">No messages found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
<?php
$conn->close();
?>
