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
$edit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($edit_id <= 0) {
  echo '<div class="alert alert-danger m-4">Invalid customer ID.</div>';
  exit;
}

$sql = "SELECT * FROM customers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $edit_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo '<div class="alert alert-warning m-4">Customer not found.</div>';
  exit;
}

$customer = $result->fetch_assoc();
?>

<div class="main-content">
  <div class="container-fluid py-4">
    <div class="bg-white shadow-sm rounded p-4">
      <h4 class="mb-1">Edit Customer: <?php echo htmlspecialchars($customer['company_name']); ?></h4>
      <p class="text-muted mb-4">Update the customer details below for ID #<?php echo $edit_id; ?>.</p>

      <form method="POST" action="update_customer.php">
        <input type="hidden" name="id" value="<?php echo $edit_id; ?>">

        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label">Company Name *</label>
            <input type="text" name="company_name" class="form-control" value="<?php echo htmlspecialchars($customer['company_name']); ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($customer['phone']); ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($customer['email']); ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">GSTIN</label>
            <input type="text" name="gstin" class="form-control" value="<?php echo htmlspecialchars($customer['gstin']); ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">PAN</label>
            <input type="text" name="pan" class="form-control" value="<?php echo htmlspecialchars($customer['pan']); ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Website</label>
            <input type="text" name="website" class="form-control" value="<?php echo htmlspecialchars($customer['website']); ?>">
          </div>
        </div>

        <!-- Billing and Shipping Address -->
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="border rounded p-3">
              <h6>Billing Address</h6>
              <input type="text" name="billing_address" class="form-control mb-2" value="<?php echo htmlspecialchars($customer['billing_address']); ?>">
              <div class="row g-2">
                <div class="col-md-6">
                  <label class="form-label">Country</label>
                  <input type="text" name="billing_country" class="form-control" value="<?php echo htmlspecialchars($customer['billing_country']); ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">State *</label>
                  <input type="text" name="billing_state" class="form-control" value="<?php echo htmlspecialchars($customer['billing_state']); ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">City</label>
                  <input type="text" name="billing_city" class="form-control" value="<?php echo htmlspecialchars($customer['billing_city']); ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Pincode</label>
                  <input type="text" name="billing_pincode" class="form-control" value="<?php echo htmlspecialchars($customer['billing_pincode']); ?>">
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="border rounded p-3">
              <h6>Shipping Address</h6>
              <input type="text" name="shipping_address" class="form-control mb-2" value="<?php echo htmlspecialchars($customer['shipping_address']); ?>">
              <div class="row g-2">
                <div class="col-md-6">
                  <label class="form-label">Country</label>
                  <input type="text" name="shipping_country" class="form-control" value="<?php echo htmlspecialchars($customer['shipping_country']); ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">State *</label>
                  <input type="text" name="shipping_state" class="form-control" value="<?php echo htmlspecialchars($customer['shipping_state']); ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">City</label>
                  <input type="text" name="shipping_city" class="form-control" value="<?php echo htmlspecialchars($customer['shipping_city']); ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Pincode</label>
                  <input type="text" name="shipping_pincode" class="form-control" value="<?php echo htmlspecialchars($customer['shipping_pincode']); ?>">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Reference & Notes -->
        <div class="row g-3">
          <div class="col-md-6">
            <div class="border rounded p-3">
              <h6>Reference</h6>
              <div class="row g-2">
                <div class="col-md-6">
                  <label class="form-label">Contact Person Name</label>
                  <input type="text" name="contact_person" class="form-control" value="<?php echo htmlspecialchars($customer['contact_person_name']); ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Phone</label>
                  <input type="text" name="contact_phone" class="form-control" value="<?php echo htmlspecialchars($customer['contact_person_phone']); ?>">
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="border rounded p-3">
              <h6>Notes</h6>
              <textarea name="notes" class="form-control" rows="5"><?php echo htmlspecialchars($customer['notes']); ?></textarea>
            </div>
          </div>
        </div>

        <!-- Submit -->
        <div class="text-end mt-4">
          <button type="submit" class="btn btn-success">Update Customer</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
