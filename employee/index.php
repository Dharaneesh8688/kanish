<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit;
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/db.php';

// Fetch employee details
$id = $_SESSION['employee_id'];
$result = mysqli_query($conn, "SELECT * FROM employees WHERE id=$id");
$employee = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fa;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        .profile-card {
            border: none;
            border-radius: .75rem;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .profile-card:hover {
            transform: translateY(-3px);
        }
        .profile-header {
            background: linear-gradient(135deg, #007bff, #6610f2);
            color: white;
            padding: 2rem 1rem;
            text-align: center;
        }
        .profile-avatar {
            width: 90px;
            height: 90px;
            background: #fff;
            color: #6610f2;
            font-weight: bold;
            font-size: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            
            justify-content: center;
            margin: -45px auto 0 auto;
            box-shadow: 0 0 0 4px #fff;
        }
        .quick-links a {
            display: inline-block;
            margin: .5rem;
            padding: .6rem 1rem;
            font-weight: 500;
            text-decoration: none;
            color: #fff;
            border-radius: .5rem;
            background: #007bff;
            transition: background 0.3s;
        }
        .quick-links a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card profile-card">
                <div class="profile-header">
                    <h3 class="mb-1">Welcome, <?= htmlspecialchars($employee['name']) ?></h3>
                    <p class="mb-0">Employee Dashboard</p>
                </div>
                <div class="profile-avatar mt-4 ">
                    <?php
                    // Show initials
                    $initials = strtoupper(substr($employee['name'],0,1));
                    echo htmlspecialchars($initials);
                    ?>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title mb-3 mt-3">Your Details</h5>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <ul class="list-group list-group-flush text-start">
                                <li class="list-group-item"><strong>Name:</strong> <?= htmlspecialchars($employee['name']) ?></li>
                                <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($employee['email']) ?></li>
                                <li class="list-group-item"><strong>Phone:</strong> <?= htmlspecialchars($employee['phone']) ?></li>
                                <li class="list-group-item"><strong>Role:</strong> <?= htmlspecialchars($employee['role']) ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="quick-links mt-4">
                        <a href="invoices.php">🧾 My Invoices</a>
                        <a href="employee_logout.php">🚪 Logout</a>
                    </div>
                </div>
                <div class="card-footer text-center text-muted">
                    &copy; <?= date("Y") ?> Uwindows
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
