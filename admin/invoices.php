<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/db.php'; ?>
<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>

<div class="main-content">
  <div class="container-fluid py-4">
    <div class="bg-white shadow-sm rounded p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="mb-0">Invoices</h4>
          <p class="text-muted">All Invoices</p>
        </div>
        <!-- <a href="add_quotation.php" class="btn btn-primary">New</a> -->
      </div>
      <form method="GET" class="mb-3">
  <div class="input-group" style="max-width:300px;">
    <input 
      type="text" 
      id="clientSearch" 
      name="search" 
      value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
      class="form-control" 
      placeholder="Search by Client Name">
    <button class="btn btn-primary" type="submit">Search</button>
    <a href="invoices.php" class="btn btn-secondary">Reset</a>
  </div>
</form>

      <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped text-nowrap">
          <thead class="table-light">
            <tr>
              <th>SNO.</th>
              <th>Issue Date</th>
              <th>Doc.No</th>
              <th>Client Name</th>
              <!-- <th>Tax ( ₹ )</th> -->
              <!-- <th>Taxable ( ₹ )</th> -->
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
           $searchTerm = '';
           if (!empty($_GET['search'])) {
             $searchTerm = $conn->real_escape_string($_GET['search']);
             $sql = "SELECT * FROM quotations WHERE client_name LIKE '%$searchTerm%' ORDER BY id DESC";
           } else {
             $sql = "SELECT * FROM quotations ORDER BY id DESC";
           }
           
            $result = $conn->query($sql);
            $sn = 1;
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $sn++ . "</td>";
                echo "<td>" . htmlspecialchars($row['doc_date']) . "</td>";
                echo "<td># Quotation - " . htmlspecialchars($row['doc_no']) . "</td>";
                echo "<td>" . htmlspecialchars($row['client_name']) . "</td>";
                // echo "<td>" . number_format($row['tax'], 2) . "</td>";
                // echo "<td>" . number_format($row['taxable'], 2) . "</td>";
                echo "<td>" . number_format($row['grand_total'], 2) . "</td>";
                // echo "<td><span class='badge bg-success'>" . htmlspecialchars($row['status']) . "</span></td>";
               
                // echo "<td>" . htmlspecialchars($row['so_no']) . "</td>";
                // echo "<td>" . htmlspecialchars($row['so_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['client_phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gstin']) . "</td>";
                echo "<td>" . htmlspecialchars($row['billing_state']) . "</td>";
                echo "<td>" . htmlspecialchars($row['shipping_state']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_by']) . "</td>";
                echo "<td class='text-nowrap'>
                <a href='print_quotation_invoice.php?id=" . $row['id'] . "' target='_blank' class='btn  btn-info me-1' title='View / Print'>Print
   <i class='fas fa-print'></i>
 </a>
               </td>";  
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='16' class='text-center'>No quotations found.</td></tr>";
            }
            ?>

<!-- <a href='edit_quotation.php?id=" . $row['id'] . "' class='btn  btn-warning me-1' title='Edit'>Edit<i class='fas fa-edit'></i></a> -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('clientSearch');
  searchInput.addEventListener('keyup', function () {
    const filter = searchInput.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
      const clientCell = row.querySelector('td:nth-child(4)');
      if (clientCell) {
        const text = clientCell.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      }
    });
  });
});
</script>


</body>
</html>
