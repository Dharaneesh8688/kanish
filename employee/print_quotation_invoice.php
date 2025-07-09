<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit;
}

include 'includes/db.php';

// Validate ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    die('Invalid Invoice ID');
}

// Fetch invoice
$sql = "SELECT * FROM quotations WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die('Invoice not found');
}
$row = $result->fetch_assoc();
$items = json_decode($row['items_json'], true);

// Number to words
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

$grandTotalInWords = numberToWords($row['grand_total']);

// Customer details
$customer = null;
if (!empty($row['client_id'])) {
    $stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
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
<title><?= htmlspecialchars($customer['company_name']); ?> Invoice #<?= htmlspecialchars($row['doc_no']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: #f8f9fa;
  font-size: 11px;
}
.invoice-box {
  background: #fff;
  padding: 15px;
  border: 1px solid #ddd;
}
.invoice-title {
  font-size: 18px;
  font-weight: 600;
}
.logo {
  max-height: 50px;
}
.table th,
.table td {
  vertical-align: middle;
  padding: 3px;
  font-size: 10px;
}
.table th {
  font-weight: 600;
}
.signature {
  height: 60px;
}
.row, .mb-4 {
  margin-bottom: 0.5rem !important;
}

/* Keep same style for print */
@page {
  size: A4;
  margin: 0;
}

@media print {
  html, body {
    width: 210mm;
    height: 297mm;
    margin: 0;
    padding: 25px;
    background: #fff;
    font-size: 10px;
  }
  .no-print {
    display: none;
  }
  .container {
    width: 100%;
    max-width: none;
    margin: 0;
    padding: 0;
  }
  .invoice-box {
    border: none;
    padding: 0;
    margin: 0;
    width: 100%;
  }
  .row {
    display: flex;
    flex-wrap: nowrap;
  }
  .col-md-6 {
    width: 50% !important;
    flex: 0 0 auto;
  }
  .table th,
  .table td {
    border: 1px solid #000;
    font-size: 9px;
    padding: 2px;
  }
  .table thead th {
    background-color: #eee !important;
  }
}

</style>
</head>
<body class="py-4">
<div class="container">
  <div class="invoice-box">
    <div class="logo-container mb-2">
      <img src="../asset/images/logo.png" alt="Logo" class="mb-2 logo"><br>
    </div>
    <div class="row mb-2">
      <div class="col-md-6">
      </div>
      <div class="col-md-6 text-end">
        <div class="invoice-title">TAX INVOICE</div>
        <small>Date: <?= htmlspecialchars($row['doc_date']); ?></small><br>
        <small>Invoice #: <?= htmlspecialchars($row['id']); ?></small><br>
        <small>Job Card No#: <?= htmlspecialchars($row['job_card_no']); ?></small><br>
        <small>Glass No #: <?= htmlspecialchars($row['glass_no']); ?></small>
      </div>
    </div>
    <div class="row mb-2">
      <div class="col-md-6">
        <div class="border p-2">
          <strong>From</strong><br>
          <strong>U Windows</strong><br>
          G-57, Iraniyan Street, Chennimalai Rd, Rangampalayam,<br>
          Erode, Tamil Nadu - 638009<br>
          Phone: +91-9688022233<br>
          Email: uwindowserode@gmail.com<br>
          GSTIN: 33GBJPS7887H1ZS
        </div>
      </div>
      <div class="col-md-6">
        <div class="border p-2">
          <strong>To</strong><br>
          <?php if ($customer): ?>
            <strong><?= htmlspecialchars($customer['company_name']); ?></strong><br>
            <?= nl2br(htmlspecialchars($customer['billing_address'])); ?><br>
            <?= htmlspecialchars($customer['billing_city'] . ', ' . $customer['billing_state']); ?><br>
            GSTIN: <?= htmlspecialchars($customer['gstin']); ?><br>
            Phone: <?= htmlspecialchars($customer['phone']); ?>
          <?php else: ?>
            <strong><?= htmlspecialchars($row['client_name']); ?></strong><br>
            Phone: <?= htmlspecialchars($row['client_phone']); ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="table-responsive mb-2">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Description</th>
            <th>Location</th>
            <th>Glass Type</th>
            <th>Size (W x H)</th>
            <th>In Sqft</th>
            <th>Qty</th>
            <th>Total Sqft</th>
            <th>Rate</th>
            <th>Tax (%)</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($items as $item): ?>
          <tr>
            <td><?= $i++; ?></td>
            <td><?= htmlspecialchars($item['item_name']); ?></td>
            <td><?= htmlspecialchars($item['location'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($item['glass_type'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($item['width'].' x '.$item['height']); ?></td>
            <td><?= htmlspecialchars($item['sqft']); ?></td>
            <td><?= htmlspecialchars($item['qty']); ?></td>
            <td><?= htmlspecialchars($item['total_sqft']); ?></td>
            <td><?= number_format($item['price'],2); ?></td>
            <td><?= htmlspecialchars($item['tax']); ?></td>
            <td><?= number_format($item['total'],2); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="row justify-content-end">
      <div class="col-md-6 ms-auto" style="max-width: 300px;">
        <table class="table">
          <tr><th class="text-end">Sub Total:</th><td class="text-end"><?= number_format($row['subtotal'],2); ?></td></tr>
          <tr><th class="text-end">CGST:</th><td class="text-end"><?= number_format($row['cgst'],2); ?></td></tr>
          <tr><th class="text-end">SGST:</th><td class="text-end"><?= number_format($row['sgst'],2); ?></td></tr>
          <tr><th class="text-end">Shipping Charges:</th><td class="text-end"><?= number_format($row['shipping_charges'],2); ?></td></tr>
          <tr><th class="text-end">Discount (%):</th><td class="text-end"><?= number_format($row['discount_percent'],2); ?></td></tr>
          <tr class="table-light"><th class="text-end">Grand Total:</th><td class="text-end fw-bold"><?= number_format($row['grand_total'],2); ?></td></tr>
        </table>
        <p><strong>Amount in Words:</strong> <?= $grandTotalInWords; ?></p>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-6">
        <p><strong>Authorized Signature:</strong></p>
        <div class="border signature"></div>
      </div>
      <div class="col-6 text-end">
        <small>This is a computer-generated invoice.</small>
      </div>
    </div>
    <div class="text-end mt-2 no-print">
      <button onclick="window.print()" class="btn btn-primary btn-sm">Print / Save PDF</button>
      <a href="quotations.php" class="btn btn-secondary btn-sm">Back</a>
    </div>
  </div>
</div>
</body>
</html>
