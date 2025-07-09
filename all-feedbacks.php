<?php
// Load DB connection
include 'admin/includes/db.php';

// Get all approved feedbacks
$result = $conn->query("SELECT name, message, created_at FROM feedbacks WHERE approved=1 ORDER BY created_at DESC");
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>All Customer Feedbacks - UWindows</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
    }
    .card {
      border: none;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
    }
    .card:hover {
      transform: translateY(-3px);
    }
    .feedback-icon {
      width: 40px;
      height: 40px;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">All Customer Feedbacks</h2>
      <a href="index.php#feedback" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Home
      </a>
    </div>
    <?php if (!empty($feedbacks)): ?>
      <div class="row g-4">
        <?php foreach($feedbacks as $fb): ?>
          <div class="col-md-4">
            <div class="card h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                <img src="https://www.w3schools.com/howto/img_avatar.png" alt="profile" width="40" height="40" class="rounded-circle border">
                <div class="ms-3">
                    <h6 class="mb-0"><?php echo htmlspecialchars($fb['name']); ?></h6>
                    <small class="text-muted"><?php echo date("d M Y", strtotime($fb['created_at'])); ?></small>
                  </div>
                </div>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($fb['message'])); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info">
        No feedbacks yet. Be the first to share your experience!
      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
