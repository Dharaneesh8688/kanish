<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/db.php'; ?> <!-- Ensure DB connection -->
<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>

<div class="main-content">
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">All Sales Items</h2>
      <a href="insert_item.php" class="btn btn-primary btn-sm">➕ Add Item</a>
    </div>

    <div class="table-responsive shadow-sm border rounded p-3 bg-white">
      <table class="table table-bordered table-hover table-striped">
        <thead class="table-dark text-nowrap">
          <tr>
            <th>S.No</th>
            <th>Item Name</th>
            <th>Unit</th>
            <th>HSN</th>
            <th>Stock Available</th>
            <th>Sales Price (₹)</th>
            <th>Purchase Price (₹)</th>
            <th>Tax</th>
            <th>Sales CESS</th>
            <th>Purchase CESS</th>
            <th>Barcode</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="text-nowrap">
          <?php
          $sql = "SELECT * FROM items ORDER BY id DESC";
          $result = $conn->query($sql);
          $sn = 1;

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $sn++ . "</td>";
              echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
              echo "<td>" . htmlspecialchars($row['unit']) . "</td>";
              echo "<td>" . htmlspecialchars($row['hsn_code']) . "</td>";
              echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
              echo "<td>₹" . htmlspecialchars($row['sales_price']) . "</td>";
              echo "<td>₹" . htmlspecialchars($row['purchase_price']) . "</td>";
              echo "<td>" . htmlspecialchars($row['tax']) . "</td>";
              echo "<td>" . htmlspecialchars($row['sales_cess_percent']) . "%</td>";
              echo "<td>" . htmlspecialchars($row['purchase_cess_percent']) . "%</td>";
              echo "<td>" . htmlspecialchars($row['barcode']) . "</td>";
              echo "<td>" . htmlspecialchars($row['description']) . "</td>";
              echo "<td>
                     
                      <a href='delete_item.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this item?');\">Delete</a>
                    </td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='13' class='text-center'>No items found.</td></tr>";
          }
          ?>
           <!-- <a href='edit_item.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning'>Edit</a> -->
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
