<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'includes/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'admin';
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid credentials.";
            }
        } else {
            $error = "Invalid credentials.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login</title>
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
        <h4>🔒 Admin Login</h4>
      </div>
      <div class="login-card-body">
        <?php if ($error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form id="adminLoginForm" method="POST" novalidate>
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
            <div class="invalid-feedback">Please enter username.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
            <div class="invalid-feedback">Please enter password.</div>
          </div>
          <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('adminLoginForm').addEventListener('submit', function(event) {
  if (!this.checkValidity()) {
    event.preventDefault();
    event.stopPropagation();
  }
  this.classList.add('was-validated');
});
</script>
</body>
</html>
