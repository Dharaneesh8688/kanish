<?php
session_start();
include 'includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM employees WHERE email='$email'");
    $employee = mysqli_fetch_assoc($query);

    if ($employee && password_verify($password, $employee['password'])) {
        $_SESSION['employee_id'] = $employee['id'];
        $_SESSION['employee_name'] = $employee['name'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Employee Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(to right, #6a11cb, #2575fc);
      min-height: 100vh;
    }
    .login-card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
      margin: 1rem;
    }
    .login-card-header {
      background: linear-gradient(to right, #6a11cb, #2575fc);
      color: #fff;
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
      padding: 1.25rem;
      text-align: center;
    }
    .login-card-body {
      padding: 2rem;
    }
    .form-control {
      border-radius: 0.5rem;
    }
    .btn-primary {
      border-radius: 0.5rem;
      font-weight: 600;
    }
  </style>
</head>
<body>
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
  <div class="col-12 col-sm-8 col-md-6 col-lg-4">
    <div class="card login-card">
      <div class="login-card-header">
        <h4>👤 Employee Login</h4>
      </div>
      <div class="login-card-body">
        <?php if ($error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" novalidate id="employeeLoginForm">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
            <div class="invalid-feedback">Please enter your email.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
            <div class="invalid-feedback">Please enter your password.</div>
          </div>
          <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('employeeLoginForm').addEventListener('submit', function(event) {
  if (!this.checkValidity()) {
    event.preventDefault();
    event.stopPropagation();
  }
  this.classList.add('was-validated');
});
</script>
</body>
</html>
