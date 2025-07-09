<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/db.php'; ?>

<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit;
}

$employee_name = $_SESSION['employee_name'];
?>

<div class="main-content">
  <div class="container-fluid py-4">
    <div class="bg-white shadow-sm rounded p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="mb-0">Quotation</h4>
          <p class="text-muted">All Quotations Created by You</p>
        </div>
        <a href="add_quotation.php" class="btn btn-primary">New</a>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped text-nowrap">
          <thead class="table-light">
            <tr>
              <th>SNO.</th>
              <th>Issue Date</th>
              <th>Doc.No</th>
              <th>Client Name</th>
              <!-- <th>Tax ( ₹ )</th>
              <th>Taxable ( ₹ )</th> -->
              <th>Amount ( ₹ )</th>
              <!-- <th>Status</th> -->
           
              <!-- <th>SO No</th>
              <th>SO Date</th> -->
              <th>Client Phone No</th>
              <th>GSTIN</th>
              <th>Billing State</th>
              <th>Shipping State</th>
              <th>Created By</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Filter quotations by created_by
  $stmt = $conn->prepare("
  SELECT 
    q.*, 
    c.company_name, 
    c.phone AS customer_phone, 
    c.gstin AS customer_gstin,
    c.billing_state AS customer_billing_state,
    c.billing_address AS customer_billing_address,
    c.shipping_state AS customer_shipping_state
  FROM quotations q
  LEFT JOIN customers c ON q.client_id = c.id
  WHERE q.created_by = ?
  ORDER BY q.id DESC
");

$stmt->bind_param("s", $employee_name);
$stmt->execute();
$result = $stmt->get_result();


          while ($row = $result->fetch_assoc()) {
  echo "<tr>";
  echo "<td>" . $sn++ . "</td>";
  echo "<td>" . htmlspecialchars($row['doc_date']) . "</td>";
  echo "<td># Quotation - " . htmlspecialchars($row['doc_no']) . "</td>";

  // Client Name: prefer customer table
  if (!empty($row['company_name'])) {
    echo "<td>" . htmlspecialchars($row['company_name']) . "</td>";
  } else {
    echo "<td>" . htmlspecialchars($row['client_name']) . "</td>";
  }

  echo "<td>" . number_format($row['grand_total'], 2) . "</td>";

  // Phone: prefer customer table
  if (!empty($row['customer_phone'])) {
    echo "<td>" . htmlspecialchars($row['customer_phone']) . "</td>";
  } else {
    echo "<td>" . htmlspecialchars($row['client_phone']) . "</td>";
  }

  // GSTIN: prefer customer table
  if (!empty($row['customer_gstin'])) {
    echo "<td>" . htmlspecialchars($row['customer_gstin']) . "</td>";
  } else {
    echo "<td>" . htmlspecialchars($row['gstin']) . "</td>";
  }

  // Billing State: prefer customer table
  if (!empty($row['customer_billing_state'])) {
    echo "<td>" . htmlspecialchars($row['customer_billing_state']) . "</td>";
  } else {
    echo "<td>" . htmlspecialchars($row['billing_state']) . "</td>";
  }

 if (!empty($row['customer_shipping_state'])) {
    echo "<td>" . htmlspecialchars($row['customer_shipping_state']) . "</td>";
} else {
    echo "<td>" . htmlspecialchars($row['shipping_state']) . "</td>";
}


  echo "<td>" . htmlspecialchars($row['created_by']) . "</td>";

  echo "<td class='text-nowrap'>
                  <a href='view_quotation.php?id=" . $row['id'] . "' target='_blank' class='btn btn-info btn-sm me-1' title='View / Print'>
                    <i class='fas fa-print'></i>
                  </a>
                  <a href='edit_quotation.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm me-1' title='Edit'>
                    <i class='fas fa-edit'></i>
                  </a>
                  <a href='delete_quotation.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' title='Delete' onclick=\"return confirm('Delete this quotation?');\">
                    <i class='fas fa-trash'></i>
                  </a>
                </td>";
  echo "</tr>";
}

            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
