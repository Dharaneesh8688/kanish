<?php
session_start();
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>  

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/db.php'; ?>


<div class="main-content">
  <div class="container-fluid">
  <?php if (isset($_GET['deleted'])): ?>
  <div class="alert alert-success">Customer deleted successfully.</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] == 'delete_failed'): ?>
  <div class="alert alert-danger">Failed to delete customer.</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] == 'invalid_id'): ?>
  <div class="alert alert-warning">Invalid customer ID.</div>
<?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Customer List</h2>
      <a href="add_customer.php" class="btn btn-primary btn-sm">➕ Add New Customer</a>
    </div>

    <div class="table-responsive shadow-sm border rounded p-3 bg-white">
      <table class="table table-bordered table-hover table-striped">
        <thead class="table-dark text-nowrap">
          <tr>
            <th>S.No</th>
            <th>Client / Company</th>
            <th>Email ID</th>
            <th>Phone Number</th>
            <th>Balance (₹)</th>
            <th>Available Credit (₹)</th>
           
            <th>GSTIN</th>
            <th>Billing State</th>
            <th>Billing City & Pincode</th>
            <th>Billing Address</th>
            <th>Shipping State</th>
            <th>Shipping City & Pincode</th>
            <th>Shipping Address</th>
            <th>Created By</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="text-nowrap">
          <?php
          $sql = "SELECT * FROM customers ORDER BY id DESC";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            $sn = 1;
            while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $sn++ . "</td>";
              echo "<td>" . htmlspecialchars($row['company_name']) . "</td>";
              echo "<td>" . htmlspecialchars($row['email']) . "</td>";
              echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
              echo "<td>0</td>";
              echo "<td>0</td>";
           
              echo "<td>" . htmlspecialchars($row['gstin']) . "</td>";
              echo "<td>" . htmlspecialchars($row['billing_state']) . "</td>";
              echo "<td>" . htmlspecialchars($row['billing_city']) . " - " . htmlspecialchars($row['billing_pincode']) . "</td>";
              echo "<td>" . htmlspecialchars($row['billing_address']) . "</td>";
              echo "<td>" . htmlspecialchars($row['shipping_state']) . "</td>";
              echo "<td>" . htmlspecialchars($row['shipping_city']) . " - " . htmlspecialchars($row['shipping_pincode']) . "</td>";
              echo "<td>" . htmlspecialchars($row['shipping_address']) . "</td>";
              echo "<td>" . htmlspecialchars($row['created_by']) . "</td>";
             echo "<td>
        <a href='edit_customer.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning'>Edit</a>
        <a href='delete_customer.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this customer?');\">Delete</a>
      </td>";

              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='16' class='text-center'>No customers found.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
