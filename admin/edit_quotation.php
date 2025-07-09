

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/db.php'; ?>


<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('CGST_RATE', 0.09);
define('SGST_RATE', 0.09);

?>
<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>



<?php
$glass_result = $conn->query("SELECT * FROM glasses ORDER BY name ASC");

// Get Quotation ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    die('Quotation ID not specified.');
}


// Fetch existing quotation
$stmt = $conn->prepare("SELECT * FROM quotations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die('Quotation not found.');
} 
$row = $result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $client_id = $_POST['client_name'];
  $client_phone_id = $_POST['client_phone'];
  $doc_no = $_POST['doc_no'];
  $doc_date = $_POST['doc_date'];
  $due_date = $_POST['due_date'];
  $payment_terms = $_POST['payment_terms'];
  $shipping_charges = floatval($_POST['shipping_value'] ?? 0);
  $discount_percent = floatval($_POST['discount_value'] ?? 0);
  $client_name_text = $_POST['client_name_text'] ?? '';
  $client_phone_text = $_POST['client_phone_text'] ?? '';
  $notes = $_POST['notes'] ?? '';
  $id = $_GET['id'];


  $client_name = $client_name_text;
  $client_phone = $client_phone_text;

  $items = json_decode($_POST['items_json'] ?? '[]', true);
  $items_json = json_encode($items);

  $subtotal = $cgst = $sgst = $igst = 0.0;
  foreach ($items as $item) {
      $subtotal += $item['total'] / (1 + $item['tax']/100);
      $cgst     += ($item['total'] / (1 + $item['tax']/100)) * CGST_RATE;
      $sgst     += ($item['total'] / (1 + $item['tax']/100)) * SGST_RATE;
  }
  $grand_total = array_sum(array_column($items, 'total')) + $shipping_charges;
  $grand_total -= $subtotal * ($discount_percent/100);

  $stmt = $conn->prepare("UPDATE quotations SET
  client_id=?,
  client_name=?,
  client_phone_id=?,
  client_phone=?,
  doc_no=?,
  doc_date=?,
  due_date=?,
  payment_terms=?,
  shipping_charges=?,
  discount_percent=?,
  items_json=?,
  subtotal=?,
  cgst=?,
  sgst=?,
  igst=?,
  grand_total=?,
  notes=?
WHERE id=?");

$stmt->bind_param(
  "ssssssssdsddddsi",    
  $client_id,
  $client_name,
  $client_phone_id,
  $client_phone,
  $doc_no,
  $doc_date,
  $due_date,
  $payment_terms,
  $shipping_charges,
  $discount_percent,
  $items_json,
  $subtotal,
  $cgst,
  $sgst,
  $igst,
  $grand_total,
  $notes,
  $id
);




  if ($stmt->execute()) {
      header('Location: quotations.php?updated=1'); exit;
  } else {
      echo '<div class="alert alert-danger">Error: '.$stmt->error.'</div>';
  }
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_name'];
    $client_phone_id = $_POST['client_phone'];
    $doc_no = $_POST['doc_no'];
    $doc_date = $_POST['doc_date'];
    $due_date = $_POST['due_date'];
    $payment_terms = $_POST['payment_terms'];
    $shipping_charges = floatval($_POST['shipping_value'] ?? 0);
    $discount_percent = floatval($_POST['discount_value'] ?? 0);
    $client_name_text = $_POST['client_name_text'] ?? '';
    $client_phone_text = $_POST['client_phone_text'] ?? '';
    $notes = $_POST['notes'] ?? '';

    $client_name = $client_name_text;
    $client_phone = $client_phone_text;

    // First decode items_json
    $items = json_decode($_POST['items_json'] ?? '[]', true);

    // Now encode it for storage
    $items_json = json_encode($items);

    $subtotal = $cgst = $sgst = $igst = 0.0;
    foreach ($items as $item) {
        $subtotal += $item['total'] / (1 + $item['tax']/100);
        $cgst     += ($item['total'] / (1 + $item['tax']/100)) * CGST_RATE;
        $sgst     += ($item['total'] / (1 + $item['tax']/100)) * SGST_RATE;
    }
    $grand_total = array_sum(array_column($items, 'total')) + $shipping_charges;
    $grand_total -= $subtotal * ($discount_percent/100);
   
    $created_by = $_SESSION['role'];
  

    $stmt = $conn->prepare("INSERT INTO quotations
    (client_id, client_name, client_phone_id, client_phone, doc_no, doc_date, due_date, payment_terms,
     shipping_charges, discount_percent, items_json, subtotal, cgst, sgst, igst, grand_total, created_by, notes)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    
    $stmt->bind_param(
      "ssssssssddsddddss",    
      $client_id,
      $client_name,
      $client_phone_id,
      $client_phone,
      $doc_no,
      $doc_date,
      $due_date,
      $payment_terms,
      $shipping_charges,
      $discount_percent,
      $items_json,
      $subtotal,
      $cgst,
      $sgst,
      $igst,
      $grand_total,
      $created_by,
      $notes
    );
    
  
  

    if ($stmt->execute()) {
        header('Location: quotation.php?inserted=1'); exit;
    } else {
        echo '<div class="alert alert-danger">Error: '.$stmt->error.'</div>';
    }
}

?>



<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="main-content">
  <div class="container py-4">
    <div class="bg-white shadow-sm rounded p-4">
      <h4 class="mb-1">New Quotation</h4>
      <p class="text-muted mb-4">Add / Create a New Quotation</p>

      <form id="quotation_form" method="POST" action="">

        <!-- Quotation Info -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <!-- Add these hidden fields inside your <form> -->
<input type="hidden" name="client_name_text" id="client_name_text">
<input type="hidden" name="client_phone_text" id="client_phone_text">
            <label class="form-label">Client Name *</label>
            <select name="client_name" id="client_name" class="form-select" required readonly></select>
            </div>
          <input type="hidden" name="items_json" id="items_json">
          <div class="col-md-3">
            <label class="form-label">Phone Number *</label>
            <select name="client_phone" id="client_phone" class="form-select" required readonly ></select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Doc No *</label>
            <input type="text" name="doc_no" class="form-control" value="<?php echo htmlspecialchars($row['doc_no'] ?? ''); ?>" readonly>

          </div>
          <div class="col-md-2">
            <label class="form-label">Doc Date *</label>
            <input type="date" name="doc_date" class="form-control" value="<?php echo htmlspecialchars($row['doc_date'] ?? date('Y-m-d')); ?>"
            required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="<?php echo htmlspecialchars($row['due_date'] ?? ''); ?>"
            >
          </div>
          <div class="col-md-3">
            <label class="form-label">SO No</label>
            <input type="text" name="so_no" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">SO Date</label>
            <input type="date" name="so_date" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Payment Terms</label>
            <select name="payment_terms" class="form-select">
  <option value="On Receipt" <?php echo ($row['payment_terms'] ?? '') === 'On Receipt' ? 'selected' : ''; ?>>On Receipt</option>
  <option value="Net 15" <?php echo ($row['payment_terms'] ?? '') === 'Net 15' ? 'selected' : ''; ?>>Net 15</option>
  <option value="Net 30" <?php echo ($row['payment_terms'] ?? '') === 'Net 30' ? 'selected' : ''; ?>>Net 30</option>
</select>

          </div>
        </div>


        <h5 class="mb-3">Add Particular</h5>
        <div class="row g-2 mb-4">
          <div class="col-md-3">
            <label class="form-label">Item Name *</label>
            <select id="item_name" class="form-select" r></select>
          </div>
         
          <div class="col-md-2">
            <label class="form-label">Location *</label>
            <input  id="location"  class="form-control" >
          </div>
        <div class="col-md-2">
  <label class="form-label">Glass Type *</label>
  <select id="glass_type" class="form-select">
    <option value="">Select Glass Type</option>
    <?php while($glass = $glass_result->fetch_assoc()): ?>
      <option value="<?= htmlspecialchars($glass['name']); ?>">
        <?= htmlspecialchars($glass['name']); ?>
      </option>
    <?php endwhile; ?>
  </select>
</div>

          <div class="col-md-2">
            <label class="form-label">Width *</label>
            <input type="number" id="item_width" step="0.01" class="form-control" >
          </div>
          <div class="col-md-2">
            <label class="form-label">Height *</label>
            <input type="number" id="item_height" step="0.01" class="form-control">
          </div>
          <div class="col-md-2">
            <label class="form-label">In Sqft *</label>
            <input type="text" id="item_in_sqft" class="form-control" readonly>
          </div>
          <div class="col-md-2">
            <label class="form-label">Qty *</label>
            <input type="number" id="item_qty" class="form-control" >
          </div>
          <div class="col-md-2">
            <label class="form-label">Total Sqft *</label>
            <input type="text" id="item_total_sqft" class="form-control" readonly>
          </div>
          <div class="col-md-2">
            <label class="form-label">Price *</label>
            <input type="text" id="item_price" class="form-control" >
          </div>
          <div class="col-md-2">
            <label class="form-label">Tax (%)</label>
            <input type="text" id="item_tax" class="form-control" readonly>
          </div>
          <div class="col-md-2">
            <label class="form-label">Total</label>
            <input type="text" id="item_total" class="form-control" readonly>
          </div>
          <div class="col-md-1 d-flex align-items-end">
            <button type="button" id="add_particular" class="btn btn-primary w-100">Add</button>
          </div>
        </div>

          <!-- Particular Table -->
          <table class="table table-bordered mt-4" id="particular_table">
          <thead>
            <tr>
              <th>SNo</th>
              <th>Item Name</th>
              <th>Location</th>
              <th>Glass Type</th>
              <th>Width</th>
              <th>Height</th>
              <th>In Sqft</th>
              <th>Qty</th>
              <th>Total Sqft</th>
              <th>Price (₹)</th>
              <th>Tax (%)</th>
              <th>Total (₹)</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>

          <?php
if (!empty($row['items_json'])):
  $items = json_decode($row['items_json'], true);
  $sno = 1;
  foreach ($items as $item):
?>
<tr data-item-name="<?php echo htmlspecialchars($item['item_name']); ?>">
  <td><?php echo $sno++; ?></td>
  <td><?php echo htmlspecialchars($item['item_name']); ?></td>
  <td><?php echo htmlspecialchars($item['location']); ?></td>
  <td><?php echo htmlspecialchars($item['glass_type']); ?></td>
  <td><?php echo htmlspecialchars($item['width']); ?></td>
  <td><?php echo htmlspecialchars($item['height']); ?></td>
  <td><?php echo htmlspecialchars($item['sqft']); ?></td>
  <td><?php echo htmlspecialchars($item['qty']); ?></td>
  <td><?php echo htmlspecialchars($item['total_sqft']); ?></td>
  <td><?php echo htmlspecialchars($item['price']); ?></td>
  <td><?php echo htmlspecialchars($item['tax']); ?></td>
  <td><?php echo htmlspecialchars($item['total']); ?></td>
  <td><button type="button" class="btn btn-sm btn-danger remove-row">Delete</button></td>
</tr>
<?php
  endforeach;
endif;
?>

          </tbody>
        </table>

        <!-- Additions -->
        <div class="row g-2 mb-4">
          <div class="col-md-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="add_shipping">
              <label class="form-check-label" for="add_shipping">Add Shipping Charges</label>
              <input type="text" id="shipping_value" name="shipping_value" value="<?php echo htmlspecialchars($row['shipping_charges'] ?? ''); ?>">
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="add_discount">
              <label class="form-check-label" for="add_discount">Add Discount (%)</label>
            </div>
            <input type="text" id="discount_value" name="discount_value" value="<?php echo htmlspecialchars($row['discount_percent'] ?? ''); ?>"></div>
        </div>

        <div class="mb-3">
  <label class="form-label">Notes</label>
  <textarea name="notes" class="form-control"><?php echo htmlspecialchars($row['notes'] ?? ''); ?></textarea>


        <!-- Totals -->
      <div class="row">
          <div class="col-md-6 offset-md-6">
            <table class="table">
              <tr>
                <th>Sub Total ( ₹ )</th>
                <td id="subtotal">0.00</td>
              </tr>
              <tr>
                <th>CGST ( ₹ )</th>
                <td id="cgst">0.00</td>
              </tr>
              <tr>
                <th>SGST ( ₹ )</th>
                <td id="sgst">0.00</td>
              </tr>
              <tr>
                <th>IGST ( ₹ )</th>
                <td id="igst">0.00</td>
              </tr>
              <tr>
                <th>Grand Total ( ₹ )</th>
                <td><strong id="grand_total">0.00</strong></td>
              </tr>
            </table>
          </div>
        </div>

        
        <!-- Submit -->
        <div class="text-end">
          <button type="submit" class="btn btn-success">Update   Quotation</button>
          <a href="quotations.php" class="btn btn-danger">Cancel</a>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
$('#quotation_form').on('submit', function(e){
  e.preventDefault(); // Stop immediate form submit

  const clientName = $('#client_name').select2('data')[0]?.text || '';
  const clientPhone = $('#client_phone').select2('data')[0]?.text || '';
  $('#client_name_text').val(clientName);
  $('#client_phone_text').val(clientPhone);

  const items = [];
$('#particular_table tbody tr').each(function(i){
  const cols = $(this).find('td');
  items.push({
    item_name: $(this).data('item-name') || '',
    location: cols.eq(2).text().trim(),
    glass_type: cols.eq(3).text().trim(),
    width: parseFloat(cols.eq(4).text()) || 0,
    height: parseFloat(cols.eq(5).text()) || 0,
    sqft: parseFloat(cols.eq(6).text()) || 0,
    qty: parseFloat(cols.eq(7).text()) || 0,
    total_sqft: parseFloat(cols.eq(8).text()) || 0,
    price: parseFloat(cols.eq(9).text()) || 0,
    tax: parseFloat(cols.eq(10).text()) || 0,
    total: parseFloat(cols.eq(11).text()) || 0
  });
});

  $('#items_json').val(JSON.stringify(items));

  // Submit the form manually after setting values
  this.submit();
});


//... rest of JS functions unchanged ...
</script>


<script>
  $(document).ready(function () {
  updateSubTotal();
});

          function updateSubTotal() {
            let subtotal = 0;
            let cgst = 0;
            let sgst = 0;
            let grandTotal = 0;

            $('#particular_table tbody tr').each(function () {
              const total = parseFloat($(this).find('td').eq(11).text()) || 0;
              const taxPercent = parseFloat($(this).find('td').eq(10).text()) || 0;

              const baseAmount = total / (1 + (taxPercent / 100));
              const cgstAmount = baseAmount * 0.09;
              const sgstAmount = baseAmount * 0.09;

              subtotal += baseAmount;
              cgst += cgstAmount;
              sgst += sgstAmount;
              grandTotal += total;
            });

            // Shipping
            const shippingChecked = $('#add_shipping').is(':checked');
            const shippingValue = parseFloat($('#shipping_value').val()) || 0;
            if (shippingChecked) {
              grandTotal += shippingValue;
            }

            // Discount as percentage off subtotal
            const discountChecked = $('#add_discount').is(':checked');
            const discountPercent = parseFloat($('#discount_value').val()) || 0;
            let discountAmount = 0;
            if (discountChecked) {
              discountAmount = subtotal * (discountPercent / 100);
              grandTotal -= discountAmount;
            }

            $('#subtotal').text(subtotal.toFixed(2));
            $('#cgst').text(cgst.toFixed(2));
            $('#sgst').text(sgst.toFixed(2));
            $('#grand_total').text(grandTotal.toFixed(2));
          }

          $(document).on('click', '#add_particular', function () {
            setTimeout(updateSubTotal, 100);
          });
          $(document).on('click', '.remove-row', function () {
            setTimeout(updateSubTotal, 100);
          });
          $(document).on('input change', '#shipping_value, #add_shipping, #discount_value, #add_discount', function () {
            updateSubTotal();
          });
        </script>

    


<script>
$(document).ready(function() {
  function initSelect2(selector, url, preselected) {
    $(selector).select2({
      placeholder: 'Search...',
      allowClear: true,
      ajax: {
        url: url,
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return { q: params.term };
        },
        processResults: function (data) {
          return {
            results: data.map(item => ({ id: item.value, text: item.label }))
          };
        },
        cache: true
      },
      width: '100%'
    });

    // Preselect the saved value
    if (preselected && preselected.id) {
      var option = new Option(preselected.text, preselected.id, true, true);
      $(selector).append(option).trigger('change');
    }
  }
   $('#item_name').on('select2:select', function(e) {
    var itemId = e.params.data.id;
    $.ajax({
      url: 'ajax/item_names.php',
      data: { id: itemId },
      dataType: 'json',
      
      success: function(data) {
       
        $('#item_price').val(data.price);
        $('#item_tax').val(data.tax);
      }
    });
  });

  initSelect2('#client_name', 'ajax/client_names.php', existingClientName);
  initSelect2('#client_phone', 'ajax/client_phones.php', existingClientPhone);
  initSelect2('#item_name', 'ajax/item_names.php');
});


 



</script>
<script>
  var existingClientName = {
    id: "<?php echo htmlspecialchars($row['client_id'] ?? ''); ?>",
    text: "<?php echo htmlspecialchars($row['client_name'] ?? ''); ?>"
  };
  var existingClientPhone = {
    id: "<?php echo htmlspecialchars($row['client_phone_id'] ?? ''); ?>",
    text: "<?php echo htmlspecialchars($row['client_phone'] ?? ''); ?>"
  };
</script>




<script>
function calculateArea() {
    const width_mm = parseFloat($('#item_width').val()) || 0;
    const height_mm = parseFloat($('#item_height').val()) || 0;

    // Convert mm² to sq ft
    const sqft = (width_mm * height_mm) / 92903.04;
    $('#item_in_sqft').val(sqft.toFixed(2));

    const qty = parseFloat($('#item_qty').val()) || 0;
    const totalSqft = sqft * qty;
    $('#item_total_sqft').val(totalSqft.toFixed(2));

    const price = parseFloat($('#item_price').val()) || 0;
    const taxPercent = parseFloat($('#item_tax').val()) || 0;
    const baseTotal = totalSqft * price;
    const taxAmount = baseTotal * (taxPercent / 100);
    const grandTotal = baseTotal + taxAmount;

    $('#item_total').val(grandTotal.toFixed(2));
}



  $(document).ready(function () {
    $('#item_width, #item_height, #item_qty').on('input', calculateArea);
  });

  
</script>
<script>

let serialNo = 1;
$('#add_particular').on('click', function () {
  const name = $('#item_name').select2('data')[0]?.text || '';
  const location = $('#location').val().trim();
  const glassType = $('#glass_type').val().trim();
  const width = $('#item_width').val();
  const height = $('#item_height').val();
  const sqft = $('#item_in_sqft').val();
  const qty = $('#item_qty').val();
  const totalSqft = $('#item_total_sqft').val();
  const price = $('#item_price').val();
  const tax = $('#item_tax').val();
  const total = $('#item_total').val();

  if (!name || !width || !height || !qty) {
    alert('Please fill in all required fields');
    return;
  }

  const newRow = `
   <tr data-item-name="${name}">
    <td>${serialNo++}</td>
    <td>${name}</td>
    <td>${location}</td>
    <td>${glassType}</td>
    <td>${width}</td>
    <td>${height}</td>
    <td>${sqft}</td>
    <td>${qty}</td>
    <td>${totalSqft}</td>
    <td>${price}</td>
    <td>${tax}</td>
    <td>${total}</td>
    <td><button type="button" class="btn btn-sm btn-danger remove-row">Delete</button></td>
  </tr>
  `;
  $('#particular_table tbody').append(newRow);

  // Clear fields
  $('#item_name').val(null).trigger('change');
  $('#location, #glass_type, #item_width, #item_height, #item_qty, #item_in_sqft, #item_total_sqft, #item_price, #item_tax, #item_total').val('');
});


            $(document).on('click', '.remove-row', function () {
              $(this).closest('tr').remove();
            });
         

</script>

</body>
</html>