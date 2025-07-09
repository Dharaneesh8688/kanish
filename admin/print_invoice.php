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
if (!$id) die('Invalid Invoice ID');

// Fetch invoice
$sql = "SELECT * FROM add_invoice WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die('Invoice not found');
$row = $result->fetch_assoc();

// Decode items
$items = json_decode($row['items_json'], true);

// Function to convert number to words
function numberToWords($number) {
  $ones = array(
      '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six',
      'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve',
      'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen',
      'Eighteen', 'Nineteen'
  );

  $tens = array(
      '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty',
      'Sixty', 'Seventy', 'Eighty', 'Ninety'
  );

  $digits = array('', 'Thousand', 'Lakh', 'Crore');

  $number = round($number, 2);
  $no = floor($number);
  $decimal = round(($number - $no) * 100);
  $str = array();
  $i = 0;

  if ($no == 0) {
      $str[] = 'Zero';
  }

  while ($no > 0) {
      $divider = ($i == 0) ? 1000 : 100;
      $number_chunk = $no % $divider;
      $no = floor($no / $divider);

      if ($number_chunk) {
          $plural = ($i && $number_chunk > 9) ? '' : null;
          $hundred = ($i == 1 && !empty($str)) ? ' and ' : null;

          if ($number_chunk < 20) {
              $chunk_text = $ones[$number_chunk];
          } else {
              $chunk_text = $tens[floor($number_chunk / 10)] . ' ' . $ones[$number_chunk % 10];
          }

          $str[] = $chunk_text . ' ' . $digits[$i] . $plural . $hundred;
      }
      $i++;
  }

  $result = implode(' ', array_reverse($str));
  $points = '';
  if ($decimal > 0) {
      if ($decimal < 20) {
          $points = " and " . $ones[$decimal] . " Paise";
      } else {
          $points = " and " . $tens[floor($decimal / 10)] . ' ' . $ones[$decimal % 10] . " Paise";
      }
  }

  return ucfirst(trim($result)) . " Rupees" . $points . " Only";
}


// Convert grand total to words
$grandTotalInWords = numberToWords($row['grand_total']);

// Fetch customer
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
  <title>Invoice #<?= htmlspecialchars($row['doc_no']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @media print {
      body { margin:0; }
      .no-print { display:none; }
      .invoice-box { box-shadow:none; border:none; }
      .table th, .table td { border:1px solid #000; font-size:11px; padding:4px; }
      .table thead th { background-color:#eee !important; }
    }
    body { background:#f8f9fa; }
    .invoice-box { background:#fff; padding:20px; border:1px solid #ddd; }
    .invoice-title { font-size:24px; font-weight:600; }
    .logo { max-height:60px; }
    .table th, .table td { vertical-align:middle; }
    .signature { height:80px; }
  </style>
</head>
<body class="py-4">
  <div class="container invoice-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <img src="logo.png" alt="Logo" class="logo mb-2"><br>
        <strong>UWindows</strong><br>
        G-57, Iraniyan Street, Chennimalai Rd, Erode, Tamil Nadu - 638009<br>
        GSTIN: 33GBJPS7887H1ZS<br>
        Phone: +91-9688022233<br>
        Email: uwindowserode@gmail.com
      </div>
      <div class="text-end">
        <div class="invoice-title">TAX INVOICE</div>
        <small>Date: <?= htmlspecialchars($row['doc_date']); ?></small><br>
        <small>Invoice #: <?= htmlspecialchars($row['id']); ?></small>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-6">
        <h6>Billed To:</h6>
        <div class="border p-2">
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

    <div class="table-responsive mb-4">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Description</th>
            <th>HSN/SAC</th>
            <th>Size (W x H)</th>
            <th>Sqft</th>
            <th>Qty</th>
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
            <td><?= htmlspecialchars($item['hsn'] ?? '-'); ?></td>
            <td><?= htmlspecialchars($item['width'].' x '.$item['height']); ?></td>
            <td><?= htmlspecialchars($item['sqft']); ?></td>
            <td><?= htmlspecialchars($item['qty']); ?></td>
            <td><?= number_format($item['price'],2); ?></td>
            <td><?= htmlspecialchars($item['tax']); ?></td>
            <td><?= number_format($item['total'],2); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="row justify-content-end">
      <div class="col-md-6">
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


    <div class="row mt-4">
      <div class="col-6">
        <p><strong>Authorized Signature:</strong></p>
        <div class="border signature"></div>
      </div>
      <div class="col-6 text-end">
        <small>This is a computer-generated invoice.</small>
      </div>
    </div>

    <div class="text-end mt-3 no-print">
      <button onclick="window.print()" class="btn btn-primary">Print / Save PDF</button>
      <a href="invoices.php" class="btn btn-secondary">Back</a>
    </div>
  </div>
</body>
</html>
