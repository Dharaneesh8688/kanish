<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/db.php';

if (!isset($_GET['id'])) {
    echo "Item ID is missing.";
    exit;
}

$id = intval($_GET['id']);

// Fetch existing item
$stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    echo "Item not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update logic
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

    $use_as_purchase = isset($_POST['use_as_purchase']);
    $same_as_sales = isset($_POST['same_as_sales']);

    if ($use_as_purchase || $same_as_sales) {
        $purchase_price = $sales_price;
        $purchase_currency = $sales_currency;
        $purchase_cess_percent = $sales_cess_percent;
        $purchase_cess_additional = $sales_cess_additional;
    }

    $updateStmt = $conn->prepare("UPDATE items SET
        item_name = ?, quantity = ?, unit = ?, tax = ?, hsn_code = ?, barcode = ?, hardwares = ?,
        sales_price = ?, sales_currency = ?, sales_cess_percent = ?, sales_cess_additional = ?,
        purchase_price = ?, purchase_currency = ?, purchase_cess_percent = ?, purchase_cess_additional = ?,
        description = ?
        WHERE id = ?");

    $updateStmt->bind_param(
        "sissssssdsssdsssi",
        $item_name, $quantity, $unit, $tax, $hsn_code, $barcode, $hardwares,
        $sales_price, $sales_currency, $sales_cess_percent, $sales_cess_additional,
        $purchase_price, $purchase_currency, $purchase_cess_percent, $purchase_cess_additional,
        $description, $id
    );

    if ($updateStmt->execute()) {
        header("Location: salesitem.php?updated=1");
        exit;
    } else {
        echo "Update error: " . $updateStmt->error;
    }
}
?>

<!-- Form Start (reuse the same form structure) -->
<div class="main-content">
  <div class="container-fluid py-4">
    <div class="bg-white shadow-sm rounded p-4">
      <h4 class="mb-1">Edit Item</h4>
      <form method="POST">
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label">Item Name *</label>
            <input type="text" name="item_name" class="form-control" value="<?= htmlspecialchars($item['item_name']) ?>" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Quantity *</label>
            <input type="number" name="quantity" class="form-control" value="<?= $item['quantity'] ?>" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Unit *</label>
            <select name="unit" class="form-select" required>
              <?php
              $units = ['Nos', 'Kilogram', 'Sqft', 'Pieces', 'Bundles', 'Box', 'Meter'];
              echo '<option value="">-- Select Unit --</option>';
              foreach ($units as $u) {
                echo "<option value='$u'" . ($item['unit'] == $u ? ' selected' : '') . ">$u</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Tax *</label>
            <select name="tax" class="form-select" required>
              <?php
              $taxes = ['0%', '5%', '12%', '18%', '28%'];
              foreach ($taxes as $t) {
                echo "<option value='$t'" . ($item['tax'] == $t ? ' selected' : '') . ">$t</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">HSN Code</label>
            <input type="text" name="hsn_code" class="form-control" value="<?= htmlspecialchars($item['hsn_code']) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Barcode</label>
            <input type="text" name="barcode" class="form-control" value="<?= htmlspecialchars($item['barcode']) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Hardwares</label>
            <input type="text" name="hardwares" class="form-control" value="<?= htmlspecialchars($item['hardwares']) ?>">
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
                  <input type="text" name="sales_price" class="form-control" value="<?= $item['sales_price'] ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Currency</label>
                  <select name="sales_currency" class="form-select">
                    <option <?= $item['sales_currency'] == 'INR' ? 'selected' : '' ?>>INR</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">CESS % *</label>
                  <input type="text" name="sales_cess_percent" class="form-control" value="<?= $item['sales_cess_percent'] ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">+ CESS *</label>
                  <input type="text" name="sales_cess_additional" class="form-control" value="<?= $item['sales_cess_additional'] ?>">
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
                  <input type="text" name="purchase_price" class="form-control" value="<?= $item['purchase_price'] ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Currency</label>
                  <select name="purchase_currency" class="form-select">
                    <option <?= $item['purchase_currency'] == 'INR' ? 'selected' : '' ?>>INR</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">CESS % *</label>
                  <input type="text" name="purchase_cess_percent" class="form-control" value="<?= $item['purchase_cess_percent'] ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">+ CESS *</label>
                  <input type="text" name="purchase_cess_additional" class="form-control" value="<?= $item['purchase_cess_additional'] ?>">
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
          <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($item['description']) ?></textarea>
        </div>

        <!-- Submit -->
        <div class="text-end">
          <button type="submit" class="btn btn-success">Update</button>
          <a href="salesitem.php" class="btn btn-secondary">Cancel</a>
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

document.getElementById('use_as_purchase').addEventListener('change', function () {
  if (this.checked) {
    copySalesToPurchase();
  }
});

document.getElementById('same_as_sales').addEventListener('change', function () {
  if (this.checked) {
    copySalesToPurchase();
    document.getElementById('use_as_purchase').checked = true;
  }
});
</script>

</body>
</html>
