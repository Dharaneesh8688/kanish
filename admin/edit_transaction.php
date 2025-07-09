<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
  header("Location: admin_login.php");
  exit;
}
include 'includes/header.php';

include 'includes/db.php';

$customerId = intval($_GET['customer_id'] ?? 0);
if ($customerId <= 0) {
  die('Invalid customer ID.');
}

// Fetch customer
$cq = $conn->query("SELECT * FROM customers WHERE id=$customerId");
$customer = $cq->fetch_assoc();
if (!$customer) {
  die('Customer not found.');
}

// Fetch last transaction
$tq = $conn->query("SELECT * FROM customer_transactions WHERE customer_id=$customerId ORDER BY id DESC LIMIT 1");
$transaction = $tq->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $advance = floatval($_POST['advance_paid']);
  $status = $_POST['status'];

  // Insert new record
  $stmt = $conn->prepare("INSERT INTO customer_transactions (customer_id, advance_paid, status) VALUES (?, ?, ?)");
  $stmt->bind_param("ids", $customerId, $advance, $status);
  $stmt->execute();

  header("Location: customer_transactions.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Customer Transaction</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  
   
<div class="container py-4 ">
  <div class="row">
    <div class="col-md-3">
       <?php include 'includes/sidebar.php'; ?>
    </div>
    <div class="col-md-6">

  <h3>Edit Transaction for <?= htmlspecialchars($customer['company_name']); ?></h3>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Advance Paid (₹)</label>
      <input type="number" step="0.01" name="advance_paid" class="form-control" value="<?= $transaction ? htmlspecialchars($transaction['advance_paid']) : '0.00'; ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Status</label>
      <select name="status" class="form-select">
        <option value="Pending" <?= $transaction && $transaction['status']=='Pending'?'selected':''; ?>>Pending</option>
        <option value="Partially Paid" <?= $transaction && $transaction['status']=='Partially Paid'?'selected':''; ?>>Partially Paid</option>
        <option value="Paid" <?= $transaction && $transaction['status']=='Paid'?'selected':''; ?>>Paid</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Save Transaction</button>
    <a href="customer_transactions.php" class="btn btn-secondary">Cancel</a>
  </form>
    </div>
  </div>

</div>
</body>
</html>
