<?php

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: admin_login.php");
  exit();
}

include 'includes/header.php';

include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $company_name = $_POST['company_name'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $gstin = $_POST['gstin'];
  $pan = $_POST['pan'];
  $website = $_POST['website'];

  $billing_address = $_POST['billing_address'];
  $billing_country = $_POST['billing_country'];
  $billing_state = $_POST['billing_state'];
  $billing_city = $_POST['billing_city'];
  $billing_pincode = $_POST['billing_pincode'];

  $shipping_address = $_POST['shipping_address'];
  $shipping_country = $_POST['shipping_country'];
  $shipping_state = $_POST['shipping_state'];
  $shipping_city = $_POST['shipping_city'];
  $shipping_pincode = $_POST['shipping_pincode'];

  $contact_person = $_POST['contact_person'];
  $contact_phone = $_POST['contact_phone'];
  $notes = $_POST['notes'];

  $created_by = $_SESSION['role'];

  $sql = "INSERT INTO customers (
    company_name, phone, email, gstin, pan, website,
    billing_address, billing_country, billing_state, billing_city, billing_pincode,
    shipping_address, shipping_country, shipping_state, shipping_city, shipping_pincode,
    contact_person_name, contact_person_phone, notes, created_by
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param(
    "ssssssssssssssssssss",
    $company_name, $phone, $email, $gstin, $pan, $website,
    $billing_address, $billing_country, $billing_state, $billing_city, $billing_pincode,
    $shipping_address, $shipping_country, $shipping_state, $shipping_city, $shipping_pincode,
    $contact_person, $contact_phone, $notes, $created_by
  );

  if ($stmt->execute()) {
    header("Location: customers.php?success=1");
    exit;
  } else {
    echo '<div class="alert alert-danger m-4">Failed to add customer.</div>';
  }
}
?>

<div class="main-content">
<?php include 'includes/sidebar.php'; ?>

  <div class="container-fluid py-4">
    <div class="bg-white shadow-sm rounded p-4">
      <h4 class="mb-1">New Clients</h4>
      <p class="text-muted mb-4">Add / Create a New Client</p>

      <form method="POST">
        <!-- Basic Info -->
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label">Company/Client Name *</label>
            <input type="text" name="company_name" class="form-control" placeholder="Enter Company Name" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number">
          </div>
          <div class="col-md-4">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter Email">
          </div>
          <div class="col-md-4">
            <label class="form-label">GSTIN</label>
            <input type="text" name="gstin" class="form-control" placeholder="Enter GSTIN">
          </div>
          <div class="col-md-4">
            <label class="form-label">PAN</label>
            <input type="text" name="pan" class="form-control" placeholder="Enter PAN">
          </div>
          <div class="col-md-4">
            <label class="form-label">Website</label>
            <input type="text" name="website" class="form-control" placeholder="Enter Website">
          </div>
        </div>

        <!-- Billing and Shipping Address -->
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="border rounded p-3">
              <h6>Billing Address</h6>
              <div class="mb-2">
                <label class="form-label">Address</label>
                <input type="text" name="billing_address" class="form-control" placeholder="Enter Address">
              </div>
              <div class="row g-2">
                <div class="col-md-6">
                  <label class="form-label">Country</label>
                  <select name="billing_country" class="form-select">
                    <option>India</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">State *</label>
                  <select name="billing_state" class="form-select">
                    <option>Tamil Nadu</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">City</label>
                  <input type="text" name="billing_city" class="form-control" placeholder="Enter City">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Pincode</label>
                  <input type="text" name="billing_pincode" class="form-control" placeholder="Enter Pin Code">
                </div>
              </div>
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="sameAsShipping">
                <label class="form-check-label" for="sameAsShipping">
                  Use as Shipping Address
                </label>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="border rounded p-3">
              <h6>Shipping Address</h6>
              <div class="mb-2">
                <label class="form-label">Address</label>
                <input type="text" name="shipping_address" class="form-control" placeholder="Enter Address">
              </div>
              <div class="row g-2">
                <div class="col-md-6">
                  <label class="form-label">Country</label>
                  <select name="shipping_country" class="form-select">
                    <option>India</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">State *</label>
                  <select name="shipping_state" class="form-select">
                    <option>Tamil Nadu</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">City</label>
                  <input type="text" name="shipping_city" class="form-control" placeholder="Enter City">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Pincode</label>
                  <input type="text" name="shipping_pincode" class="form-control" placeholder="Enter Pin Code">
                </div>
              </div>
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="sameAsBilling">
                <label class="form-check-label" for="sameAsBilling">
                  Use as Billing Address
                </label>
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
                  <input type="text" name="contact_person" class="form-control" placeholder="Enter Contact Person Name">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Phone</label>
                  <input type="text" name="contact_phone" class="form-control" placeholder="Enter Contact Phone">
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="border rounded p-3">
              <h6>Notes</h6>
              <textarea name="notes" class="form-control" placeholder="Enter Notes Here ...." rows="5"></textarea>
            </div>
          </div>
        </div>

        <!-- Submit -->
        <div class="text-end mt-4">
          <button type="submit" class="btn btn-success">Save Customer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Copy Billing -> Shipping
document.getElementById('sameAsShipping').addEventListener('change', function() {
  if (this.checked) {
    document.querySelector('[name="shipping_address"]').value = document.querySelector('[name="billing_address"]').value;
    document.querySelector('[name="shipping_country"]').value = document.querySelector('[name="billing_country"]').value;
    document.querySelector('[name="shipping_state"]').value = document.querySelector('[name="billing_state"]').value;
    document.querySelector('[name="shipping_city"]').value = document.querySelector('[name="billing_city"]').value;
    document.querySelector('[name="shipping_pincode"]').value = document.querySelector('[name="billing_pincode"]').value;
  } else {
    document.querySelector('[name="shipping_address"]').value = '';
    document.querySelector('[name="shipping_country"]').value = 'India';
    document.querySelector('[name="shipping_state"]').value = '';
    document.querySelector('[name="shipping_city"]').value = '';
    document.querySelector('[name="shipping_pincode"]').value = '';
  }
});

// Copy Shipping -> Billing
document.getElementById('sameAsBilling').addEventListener('change', function() {
  if (this.checked) {
    document.querySelector('[name="billing_address"]').value = document.querySelector('[name="shipping_address"]').value;
    document.querySelector('[name="billing_country"]').value = document.querySelector('[name="shipping_country"]').value;
    document.querySelector('[name="billing_state"]').value = document.querySelector('[name="shipping_state"]').value;
    document.querySelector('[name="billing_city"]').value = document.querySelector('[name="shipping_city"]').value;
    document.querySelector('[name="billing_pincode"]').value = document.querySelector('[name="shipping_pincode"]').value;
  } else {
    document.querySelector('[name="billing_address"]').value = '';
    document.querySelector('[name="billing_country"]').value = 'India';
    document.querySelector('[name="billing_state"]').value = '';
    document.querySelector('[name="billing_city"]').value = '';
    document.querySelector('[name="billing_pincode"]').value = '';
  }
});
</script>

</body>
</html>
