<?php
include 'admin/includes/db.php';

$result = $conn->query("SELECT * FROM vacancies ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Careers | Join Our Team</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include 'bottom-navbar.php'; ?>
<div class="container my-5">
  <h2 class="mb-4">Current Openings</h2>
  <?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="card mb-4">
        <div class="card-body">
          <h4 class="card-title"><?= htmlspecialchars($row['title']) ?></h4>
          <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($row['location']) ?></h6>
          <p><strong>Qualification:</strong> <?= htmlspecialchars($row['qualification']) ?></p>
          <p><strong>Experience:</strong> <?= htmlspecialchars($row['experience']) ?></p>
          <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
          <a href="mailto:hr@yourdomain.com?subject=Application: <?= urlencode($row['title']) ?>" class="btn btn-primary">Apply Now</a>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No openings available at the moment.</p>
  <?php endif; ?>
</div>
<?php include 'sections/footer.php'; ?>
</body>
</html>
<?php $conn->close(); ?>
