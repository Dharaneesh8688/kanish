<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
  header("Location: admin_login.php");
  exit;
}
include 'includes/header.php';

include 'includes/db.php';

// Fetch all customers
$customers = $conn->query("SELECT * FROM customers ORDER BY company_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Transactions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 p-0">
      <?php include 'includes/sidebar.php'; ?>
    </div>
    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 py-4">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h2 class="mb-0">Customer Transactions</h2>
          <input id="searchInput" type="text" class="form-control w-25" placeholder="Search Customer...">
        </div>

        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Customer</th>
              <th>Quotation Doc No</th>
              <th>Advance Paid (₹)</th>
              <th>Total Quotation (₹)</th>
              <th>Amount Due (₹)</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sn = 1;
            while($c = $customers->fetch_assoc()):
              $customerId = $c['id'];

              // Fetch all quotations for this customer
              $q = $conn->query("SELECT id, grand_total FROM quotations WHERE client_id = $customerId ORDER BY id ASC");

              if ($q && $q->num_rows > 0):
                while($rowQ = $q->fetch_assoc()):
                  $quotationId = $rowQ['id'];
                  $grandTotal = $rowQ['grand_total'];

                  // Get latest transaction for this customer (or per quotation if you have quotation_id)
                  $tq = $conn->query("SELECT * FROM customer_transactions WHERE customer_id=$customerId ORDER BY id DESC LIMIT 1");
                  $transaction = $tq->fetch_assoc();

                  $advancePaid = $transaction ? $transaction['advance_paid'] : 0;
                  $status = $transaction ? $transaction['status'] : 'Pending';
                  $due = $grandTotal - $advancePaid;
            ?>
            <tr>
              <td><?= $sn++; ?></td>
              <td><?= htmlspecialchars($c['company_name']); ?></td>
              <td>#<?= htmlspecialchars($quotationId); ?></td>
              <td><?= number_format($advancePaid, 2); ?></td>
              <td><?= number_format($grandTotal, 2); ?></td>
              <td><?= number_format($due, 2); ?></td>
              <td>
                <span class="badge 
                  <?= 
                    $status=='Paid' ? 'bg-success' :
                    ($status=='Partially Paid' ? 'bg-warning' : 'bg-danger')
                  ?>">
                  <?= htmlspecialchars($status); ?>
                </span>
              </td>
              <td>
                <a href="edit_transaction.php?customer_id=<?= $customerId; ?>&quotation_id=<?= $quotationId; ?>" class="btn btn-sm btn-primary">Edit</a>
              </td>
            </tr>
            <?php
                endwhile;
              else:
            ?>
            <tr>
              <td><?= $sn++; ?></td>
              <td><?= htmlspecialchars($c['company_name']); ?></td>
              <td colspan="6" class="text-center">No quotations found.</td>
            </tr>
            <?php
              endif;
            endwhile;
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
// Live search by customer name
document.getElementById('searchInput').addEventListener('input', function() {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll('table tbody tr');
  rows.forEach(row => {
    const customerName = row.cells[1].textContent.toLowerCase();
    if (customerName.includes(filter)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
});
</script>

</body>
</html>
<?php $conn->close(); ?>
