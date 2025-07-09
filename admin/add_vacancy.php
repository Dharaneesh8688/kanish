<?php include 'includes/header.php'; ?>

<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
  header("Location: admin_login.php");
  exit;
}
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $location = trim($_POST['location']);
  $description = trim($_POST['description']);
  $qualification = trim($_POST['qualification']);
  $experience = trim($_POST['experience']);

  $stmt = $conn->prepare("INSERT INTO vacancies (title, location, description, qualification, experience) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $title, $location, $description, $qualification, $experience);
  $stmt->execute();
  $stmt->close();

  header("Location: manage_vacancies.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Vacancy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
         <?php include 'includes/sidebar.php'; ?>
<div class="container my-5">
  <h2 class="mb-4">Add New Vacancy</h2>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Job Title</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Location</label>
      <input type="text" name="location" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Qualification</label>
      <input type="text" name="qualification" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Experience</label>
      <input type="text" name="experience" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Job Description</label>
      <textarea name="description" class="form-control" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Add Vacancy</button>
    <a href="manage_vacancies.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
