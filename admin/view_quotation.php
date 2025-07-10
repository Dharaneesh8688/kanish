<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>

<?php
include 'includes/db.php';

// Validate ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    die('Invalid Quotation ID');
}

// Fetch quotation
$sql = "SELECT * FROM quotations WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows === 0) {
    die('Quotation not found');
}
$row = $result->fetch_assoc();

// Decode items
$items = json_decode($row['items_json'], true);

// Function to convert number to words
function numberToWords($number) {
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        '0' => '', '1' => 'One', '2' => 'Two',
        '3' => 'Three', '4' => 'Four', '5' => 'Five',
        '6' => 'Six', '7' => 'Seven', '8' => 'Eight',
        '9' => 'Nine', '10' => 'Ten', '11' => 'Eleven',
        '12' => 'Twelve', '13' => 'Thirteen', '14' => 'Fourteen',
        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        '60' => 'Sixty', '70' => 'Seventy', '80' => 'Eighty',
        '90' => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? '' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
        } else $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ? "and " . $words[floor($point / 10) * 10] . " " . $words[$point = $point % 10] . " Paise" : '';
    return ucfirst($result) . "Rupees " . $points . " Only";
}

// Convert grand total
$grandTotalInWords = numberToWords($row['grand_total']);

// Fetch customer details
$customer = null;
if (!empty($row['client_id'])) {
    $stmt = $conn->prepare("SELECT company_name, phone, email, gstin, billing_address, billing_country, billing_state, billing_city, billing_pincode FROM customers WHERE id = ?");
    $stmt->bind_param("i", $row['client_id']);
    $stmt->execute();
    $customerResult = $stmt->get_result();
    if ($customerResult->num_rows > 0) {
        $customer = $customerResult->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($customer['company_name']); ?> #<?php echo htmlspecialchars($row['id']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
@media print {
  html, body {
    margin: 0;
    padding: 0;
    width: 210mm;
    height: 297mm;
    font-size: 10px; /* Reduced from 11px */
    -webkit-print-color-adjust: exact;
  }

  .no-print {
    display: none !important;
  }

  .container {
    width: auto;
    max-width: none;
    padding: 0;
    margin: 0;
    box-shadow: none;
  }

  .table-responsive {
    overflow: visible !important;
  }

  .table {
    width: 100% !important;
    table-layout: fixed;
    word-wrap: break-word;
  }

  .table th, .table td {
    padding: 3px; /* Reduced padding */
    border: 1px solid #000;
    font-size: 10px; /* Reduced font size */
  }

  .table thead th {
    background-color: #f0f0f0 !important;
    -webkit-print-color-adjust: exact;
  }

  h4, h6 {
    font-size: 13px !important;
  }

  .container .p-4 {
    padding: 1rem !important; /* Reduce container padding */
  }
}

/* Totals table on the right side, half width */
.totals-wrapper {
  display: flex;
  justify-content: flex-end;
}

.totals-table {
  width: 50%;
  min-width: 250px;
}

/* For print */
@media print {
  .totals-wrapper {
    display: flex;
    justify-content: flex-end;
  }
  .totals-table {
    width: 50%;
    min-width: 250px;
  }
}


/* Screen styles (keep Bootstrap look) */
.table th, .table td {
  vertical-align: middle;
}
</style>




</head>
<body class="bg-light py-4">
  <div class="container bg-white p-4 shadow-sm">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
      <img src="../asset/logo.png" alt="Logo" class=" mb-2" style="width: 300px; "> <br>
        
      </div>
      <div class="text-end">
        <h4 class="mb-0">Quotation</h4>
        <small>Date: <?php echo htmlspecialchars($row['doc_date']); ?></small><br>
        <small>Quotation #: <?php echo htmlspecialchars($row['id']); ?></small><br>
        <small>Job Card No#: <?php echo htmlspecialchars($row['job_card_no']); ?></small><br>
        <small>Glass No #: <?php echo htmlspecialchars($row['glass_no']); ?></small>
      </div>
    </div>

    <!-- From / To -->
<div class="row mb-4 d-flex flex-nowrap" style="gap:1rem;">
  <div class="col" style="flex: 0 0 50%; max-width:50%;">
    <div class="border p-3 h-100">
      <h6 class="mb-2">From</h6>
      <strong>U windows</strong><br>
      G-57, Iraniyan Street, Chennimalai Rd, Rangampalayam,<br>
      Erode, Tamil Nadu - 638009<br>
      Phone: +91-9688022233<br>
      Email: uwindowserode@gmail.com<br>
      GSTIN: 33GBJPS7887H1ZS
    </div>
  </div>
  <div class="col" style="flex: 0 0 50%; max-width:50%;">
    <div class="border p-3 h-100">
      <h6 class="mb-2">To</h6>
      <?php if ($customer): ?>
        <?php if (!empty($customer['company_name'])): ?>
          <strong><?php echo htmlspecialchars($customer['company_name']); ?></strong><br>
        <?php endif; ?>
        <?php if (!empty($customer['billing_address'])): ?>
          <?php echo nl2br(htmlspecialchars($customer['billing_address'])); ?><br>
        <?php endif; ?>
        <?php
        $city = $customer['billing_city'] ?? '';
        $state = $customer['billing_state'] ?? '';
        $country = $customer['billing_country'] ?? '';
        $pincode = $customer['billing_pincode'] ?? '';
        $fullAddress = trim(
            ($city ? $city . ', ' : '') .
            ($state ? $state . ', ' : '') .
            ($country ? $country : '') .
            ($pincode ? ' - ' . $pincode : '')
        );
        if (!empty($fullAddress)) {
            echo htmlspecialchars($fullAddress) . '<br>';
        }
        ?>
        <?php if (!empty($customer['gstin'])): ?>
          GSTIN: <?php echo htmlspecialchars($customer['gstin']); ?><br>
        <?php endif; ?>
        <?php if (!empty($customer['phone'])): ?>
          Phone: <?php echo htmlspecialchars($customer['phone']); ?><br>
        <?php endif; ?>
        <?php if (!empty($customer['email'])): ?>
          Email: <?php echo htmlspecialchars($customer['email']); ?>
        <?php endif; ?>
      <?php else: ?>
        <strong><?php echo htmlspecialchars($row['client_name']); ?></strong><br>
        Phone: <?php echo htmlspecialchars($row['client_phone']); ?>
      <?php endif; ?>
    </div>
  </div>
</div>

    <!-- Items Table -->
    <div class="table-responsive mb-4">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>SNo.</th>
            <th>Description</th>
            <th>Location</th>
            <th>Glass Type</th>
            <th>Size (W x H)</th>
            <th>In Sqft</th>
            <th>Qty</th>
            <th>Total Sqft</th>
            <th>Rate (₹)</th>
            <th>Tax (%)</th>
            <th>Hardwares</th>
            <th>Total (₹)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          foreach ($items as $item): ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
            <td><?php echo isset($item['location']) ? htmlspecialchars($item['location']) : ''; ?></td>
            <td><?php echo isset($item['glass_type']) ? htmlspecialchars($item['glass_type']) : ''; ?></td>
            <td><?php echo htmlspecialchars($item['width']) . ' x ' . htmlspecialchars($item['height']); ?></td>
            <td><?php echo htmlspecialchars($item['sqft']); ?></td>
            <td><?php echo htmlspecialchars($item['qty']); ?></td>
            <td><?php echo htmlspecialchars($item['total_sqft']); ?></td>
            <td><?php echo htmlspecialchars($item['price']); ?></td>
            <td><?php echo htmlspecialchars($item['tax']); ?></td>
            <td><?php echo htmlspecialchars($item['hardware']); ?></td>
            <td><?php echo htmlspecialchars($item['total']); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Totals -->
    <div class="totals-wrapper mb-3">
  <div class="totals-table">
    <table class="table table-sm">
      <tr>
        <th class="text-end">Sub Total:</th>
        <td class="text-end"><?php echo number_format($row['subtotal'],2); ?></td>
      </tr>
      <tr>
        <th class="text-end">CGST:</th>
        <td class="text-end"><?php echo number_format($row['cgst'],2); ?></td>
      </tr>
      <tr>
        <th class="text-end">SGST:</th>
        <td class="text-end"><?php echo number_format($row['sgst'],2); ?></td>
      </tr>
      <tr>
        <th class="text-end">Shipping Charges:</th>
        <td class="text-end"><?php echo number_format($row['shipping_charges'],2); ?></td>
      </tr>
      <tr>
        <th class="text-end">Discount (%):</th>
        <td class="text-end"><?php echo number_format($row['discount_percent'],2); ?></td>
      </tr>
      <tr>
        <th class="text-end">Grand Total:</th>
        <td class="text-end fw-bold"><?php echo number_format($row['grand_total'],2); ?></td>
      </tr>
    </table>
    <p class="mt-2" style="font-size: 0.85rem;"><strong>Amount in Words:</strong> <?php echo $grandTotalInWords; ?></p>
  </div>
</div>
<h6>Note: <?php echo htmlspecialchars($row['notes']); ?></h6>

    <!-- Print Button -->
    <div class="text-end no-print">
      <a href="#" onclick="window.print()" class="btn btn-success">Print / Save as PDF</a>
      <a href="quotations.php" class="btn btn-secondary">Back</a>
    </div>
  </div>
</body>
</html>
