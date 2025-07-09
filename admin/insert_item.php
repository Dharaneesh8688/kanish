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

<?php


include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize input
  $item_name = trim($_POST['item_name']);
  $quantity = intval($_POST['quantity']);
  $unit = trim($_POST['unit']);
  $tax = trim($_POST['tax']);
  $hsn_code = trim($_POST['hsn_code']);
  $barcode = trim($_POST['barcode']);
  $sales_price = floatval($_POST['sales_price']);
  $sales_currency = trim($_POST['sales_currency']);
  $sales_cess_percent = floatval($_POST['sales_cess_percent']);
  $sales_cess_additional = floatval($_POST['sales_cess_additional']);
  $purchase_price = floatval($_POST['purchase_price']);
  $purchase_currency = trim($_POST['purchase_currency']);
  $purchase_cess_percent = floatval($_POST['purchase_cess_percent']);
  $purchase_cess_additional = floatval($_POST['purchase_cess_additional']);
  $description = trim($_POST['description']);
  $hardwares = trim($_POST['hardwares']);


  // Checkbox logic
  $use_as_purchase = isset($_POST['use_as_purchase']);
  $same_as_sales = isset($_POST['same_as_sales']);

  if ($use_as_purchase || $same_as_sales) {
    $purchase_price = $sales_price;
    $purchase_currency = $sales_currency;
    $purchase_cess_percent = $sales_cess_percent;
    $purchase_cess_additional = $sales_cess_additional;
  }

  // SQL Insert
  $stmt = $conn->prepare("INSERT INTO items (
  item_name, quantity, unit, tax, hsn_code, barcode, hardwares,
  sales_price, sales_currency, sales_cess_percent, sales_cess_additional,
  purchase_price, purchase_currency, purchase_cess_percent, purchase_cess_additional,
  description
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

  if ($stmt === false) {
    die("MySQL prepare error: " . $conn->error);
  }

$stmt->bind_param(
  "sissssssdsssdsss",
  $item_name,               // s
  $quantity,                // i
  $unit,                    // s
  $tax,                     // s
  $hsn_code,                // s
  $barcode,                 // s
  $hardwares,               // s
  $sales_price,             // d
  $sales_currency,          // s
  $sales_cess_percent,      // s 
  $sales_cess_additional,   // s
  $purchase_price,          // d
  $purchase_currency,       // s
  $purchase_cess_percent,   // s
  $purchase_cess_additional,// s
  $description              // s
);


  if ($stmt->execute()) {
    header("Location: salesitem.php?success=1");
    exit;
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
} else {
  echo "Invalid request.";
}
?>


<div class="main-content">
  <div class="container-fluid py-4">
    <div class="bg-white shadow-sm rounded p-4">
      <h4 class="mb-1">New Items</h4>
      <p class="text-muted mb-4">Add / Create a New Item</p>

      <form method="POST" action="insert_item.php">
        <!-- Basic Info -->
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label">Item Name *</label>
            <input type="text" name="item_name" class="form-control" placeholder="Enter Item Name" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Quantity *</label>
            <input type="number" name="quantity" class="form-control" value="0" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Unit *</label>
            <select name="unit" class="form-select" required>
  <option value="">---------------- Select Unit ----------------</option>
  <option value="Nos">Nos</option>
 
  <option value="Kilogram">Kilogram</option>
  <option value="Sqft">Square Feet</option>
  <option value="Pieces">Pieces</option>
  <option value="Bundles">Bundles</option>
  <option value="Box">Box</option>
  <option value="Meter">Meter</option>
</select>

          </div>
          <div class="col-md-3">
            <label class="form-label">Tax *</label>
            <select name="tax" class="form-select" required>
              <option value="0%">0%</option>
              <option value="5%">5%</option>
              <option value="12%">12%</option>
              <option value="18%">18%</option>
              <option value="28%">28%</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">HSN Code</label>
            <input type="text" name="hsn_code" class="form-control" placeholder="Enter HSN Code">
          </div>
          <div class="col-md-4">
            <label class="form-label">Barcode</label>
            <input type="text" name="barcode" class="form-control" placeholder="Enter Barcode Value">
          </div>
          <div class="col-md-4">
  <label class="form-label">Hardwares</label>
  <input type="text" name="hardwares" class="form-control" placeholder="Enter Hardware Info">
</div>

        </div>

        <!-- Sales & Purchase Info -->
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="border rounded p-3">
              <h6>Sales Info</h6>
              <div class="row g-2">
                <div class="col-md-6">
                  <label class="form-label">Unit Price *</label>
                  <input type="text" name="sales_price" class="form-control" placeholder="Enter Unit Price" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Currency</label>
                  <select name="sales_currency" class="form-select">
                    <option>INR</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">CESS % *</label>
                  <input type="text" name="sales_cess_percent" class="form-control" value="0">
                </div>
                <div class="col-md-6">
                  <label class="form-label">+ CESS *</label>
                  <input type="text" name="sales_cess_additional" class="form-control" value="0">
                </div>
              </div>
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="use_as_purchase" name="use_as_purchase">
                <label class="form-check-label" for="use_as_purchase">
                  Use as Purchase Info
                </label>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="border rounded p-3">
              <h6>Purchase Info</h6>
              <div class="row g-2">
                <div class="col-md-6">
                  <label class="form-label">Unit Price *</label>
                  <input type="text" name="purchase_price" class="form-control" placeholder="Enter Unit Price" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Currency</label>
                  <select name="purchase_currency" class="form-select">
                    <option>INR</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">CESS % *</label>
                  <input type="text" name="purchase_cess_percent" class="form-control" value="0">
                </div>
                <div class="col-md-6">
                  <label class="form-label">+ CESS *</label>
                  <input type="text" name="purchase_cess_additional" class="form-control" value="0">
                </div>
              </div>
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="same_as_sales" name="same_as_sales">
                <label class="form-check-label" for="same_as_sales">
                  Same as Sales Info
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Notes -->
        <div class="mb-4">
          <label class="form-label">Notes</label>
          <textarea name="description" class="form-control" rows="4" placeholder="Enter Notes Here ...."></textarea>
        </div>

        <!-- Submit -->
        <div class="text-end">
          <button type="submit" class="btn btn-success">Save</button>
          <a href="all_items.php" class="btn btn-danger">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function copySalesToPurchase() {
  document.querySelector('[name="purchase_price"]').value = document.querySelector('[name="sales_price"]').value;
  document.querySelector('[name="purchase_currency"]').value = document.querySelector('[name="sales_currency"]').value;
  document.querySelector('[name="purchase_cess_percent"]').value = document.querySelector('[name="sales_cess_percent"]').value;
  document.querySelector('[name="purchase_cess_additional"]').value = document.querySelector('[name="sales_cess_additional"]').value;
}

document.getElementById('use_as_purchase').addEventListener('change', function() {
  if (this.checked) {
    copySalesToPurchase();
  }
});

document.getElementById('same_as_sales').addEventListener('change', function() {
  if (this.checked) {
    copySalesToPurchase();
    document.getElementById('use_as_purchase').checked = true;
  }
});
</script>


</body>
</html>
