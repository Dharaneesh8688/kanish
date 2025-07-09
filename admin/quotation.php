<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Quotations</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<div class="main-content">
  <div class="container-fluid py-4">
    <div class="bg-white shadow-sm rounded p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="mb-0">Quotation</h4>
          <p class="text-muted">All Quotations</p>
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
              <th>Tax (₹)</th>
              <th>Taxable (₹)</th>
              <th>Amount (₹)</th>
              <th>SO No</th>
              <th>SO Date</th>
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
            $sql = "
              SELECT 
                q.*,
                c.billing_state,
                c.shipping_state
              FROM 
                quotations q
              LEFT JOIN 
                customers c ON q.client_id = c.id
              ORDER BY 
                q.id DESC
            ";
            $result = $conn->query($sql);
            $sn = 1;

            if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $sn++ . "</td>";
                echo "<td>" . htmlspecialchars($row['doc_date']) . "</td>";
                echo "<td># Quotation - " . htmlspecialchars($row['doc_no']) . "</td>";
                echo "<td>" . htmlspecialchars($row['client_name']) . "</td>";
                echo "<td>" . number_format($row['tax'], 2) . "</td>";
                echo "<td>" . number_format($row['taxable'], 2) . "</td>";
                echo "<td>" . number_format($row['grand_total'], 2) . "</td>";
                echo "<td>" . htmlspecialchars($row['so_no']) . "</td>";
                echo "<td>" . htmlspecialchars($row['so_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['client_phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gstin']) . "</td>";
                echo "<td>" . htmlspecialchars($row['billing_state'] ?? '-') . "</td>";
                echo "<td>" . htmlspecialchars($row['shipping_state'] ?? '-') . "</td>";
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
            } else {
              echo "<tr><td colspan='15' class='text-center'>No quotations found.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</body>
</html>
