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
// Fetch clients count
$client_count = 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM customers");
if ($result) {
  $row = $result->fetch_assoc();
  $client_count = $row['total'];
}

// Fetch items count
$item_count = 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM items");
if ($result) {
  $row = $result->fetch_assoc();
  $item_count = $row['total'];
}

// Fetch quotations count
$quotation_count = 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM quotations");
if ($result) {
  $row = $result->fetch_assoc();
  $quotation_count = $row['total'];
}

// (Optional) Fetch vendors count
$vendor_count = 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM vendors");
if ($result) {
  $row = $result->fetch_assoc();
  $vendor_count = $row['total'];
}
?>



<div class="main-content">
  <div class="container-fluid py-4" style="background-color: #f6f8ff; min-height: 100vh;">

    <!-- Top Right Date -->
    <div class="d-flex justify-content-end mb-3">
      <div class="dropdown">
        <button class="btn btn-light rounded shadow-sm px-3 py-2" data-bs-toggle="dropdown">
          <i class="bi bi-calendar3 me-2"></i> Today ( <?php echo date("d M Y"); ?> )
        </button>
      </div>
    </div>
    <?php
date_default_timezone_set('Asia/Kolkata'); // change if you're in another region
?>
 <!-- Welcome Section -->
<div class="row g-4 mb-4">
  <div class="col-md-7">
    <div class="bg-white rounded shadow-sm position-relative overflow-hidden" 
         style="background: url('asset/images/bg.jpg') no-repeat center center / cover; min-height: 500px;">
      
      <div class="position-relative p-4" style="z-index: 2;">
        <h5 class="fw-semibold">Hi!..</h5>
        <h2 class="text-muted fw-100">U Windows</h2>
        <div class="mt-5">
          <div><i class="bi bi-brightness-high me-1"></i> <?php echo date("h:i A"); ?></div>
          <div><?php echo date("d-m-Y"); ?></div>
        </div>
      </div>

      <!-- Optional: Background overlay for contrast -->
      <div class="position-absolute top-0 start-0 w-100 h-100" 
           style="background: rgba(255,255,255,0.3); z-index: 1;"></div>
    </div>
  </div>

      <!-- Summary Cards -->
      <div class="col-md-5">
        <div class="row g-3">
  <div class="col-6">
    <div class="p-3 text-white rounded shadow-sm" style="background-color: #7aa7ff;">
      <div class="small">Clients</div>
      <div class="fs-4 fw-bold"><?php echo $client_count; ?></div>
      <div class="small">Total Clients</div>
    </div>
  </div>
  <div class="col-6">
    <div class="p-3 text-white rounded shadow-sm" style="background-color: #3c3f9e;">
      <div class="small">Items</div>
      <div class="fs-4 fw-bold"><?php echo $item_count; ?></div>
      <div class="small">Total Items</div>
    </div>
  </div>
  <div class="col-6">
    <div class="p-3 text-white rounded shadow-sm" style="background-color: #8477ea;">
      <div class="small">Quotation</div>
      <div class="fs-4 fw-bold"><?php echo $quotation_count; ?></div>
      <div class="small">Total Quotations</div>
    </div>
  </div>
  <div class="col-6">
    <div class="p-3 text-white rounded shadow-sm" style="background-color: #f26d6d;">
      <div class="small">Vendors</div>
      <div class="fs-4 fw-bold"><?php echo $vendor_count; ?></div>
      <div class="small">Total Vendors</div>
    </div>
  </div>
</div>

      </div>
    </div>

  </div>
</div>

</body>
</html>
