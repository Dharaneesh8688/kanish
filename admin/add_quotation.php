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


<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/db.php'; ?>

<?php
$glass_result = $conn->query("SELECT * FROM glasses ORDER BY name ASC");


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
    $job_card_no = $_POST['job_card_no'] ?? '';
$glass_no = $_POST['glass_no'] ?? '';


    $client_name = $client_name_text;
    $client_phone = $client_phone_text;

    // First decode items_json
    $items = json_decode($_POST['items_json'] ?? '[]', true);

    // Now encode it for storage
    $items_json = json_encode($items);
$subtotal = $cgst = $sgst = $igst = 0.0;

foreach ($items as $item) {
    $baseAmount = $item['total'] / (1 + $item['tax']/100);
    $subtotal += $baseAmount;

    if ($item['tax'] > 0) {
        $cgst += $baseAmount * CGST_RATE;
        $sgst += $baseAmount * SGST_RATE;
    }
}

    $grand_total = array_sum(array_column($items, 'total')) + $shipping_charges;
    $grand_total -= $subtotal * ($discount_percent/100);
   
    $created_by = $_SESSION['role'];
  

    $stmt = $conn->prepare("INSERT INTO quotations
(client_id, client_name, client_phone, doc_no, doc_date, due_date, payment_terms,
 shipping_charges, discount_percent, items_json, subtotal, cgst, sgst, igst, grand_total, created_by, notes, job_card_no, glass_no)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

 $stmt->bind_param("sssssssddsdddddssss",
  $client_id,
  $client_name,
 
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
  $notes,
  $job_card_no,
  $glass_no
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

      <form id="quotation_form" method="POST" action="add_quotation.php">
        <!-- Quotation Info -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <!-- Add these hidden fields inside your <form> -->
<input type="hidden" name="client_name_text" id="client_name_text">
<input type="hidden" name="client_phone_text" id="client_phone_text">
            <label class="form-label">Client Name *</label>
            <select name="client_name" id="client_name" class="form-select" required></select>
          </div>
          <input type="hidden" name="items_json" id="items_json">
         <div class="col-md-3">
  <label class="form-label">Phone Number *</label>
<input type="text" name="client_phone" id="client_phone" class="form-control" readonly>


</div>

          <div class="col-md-2">
            <label class="form-label">Doc No *</label>
            <?php
$doc_result = $conn->query("SELECT MAX(doc_no) AS max_doc FROM quotations");
$doc_row = $doc_result->fetch_assoc();
$next_doc_no = $doc_row['max_doc'] + 1;
?>
<input type="text" name="doc_no" class="form-control" value="<?php echo $next_doc_no; ?>" readonly>

          </div>
          <div class="col-md-2">
            <label class="form-label">Doc Date *</label>
            <input type="date" name="doc_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
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
  <label class="form-label">Job Card No</label>
  <input type="text" name="job_card_no" class="form-control">
</div>
<div class="col-md-3">
  <label class="form-label">Glass No</label>
  <input type="text" name="glass_no" class="form-control">
</div>

          <div class="col-md-3">
            <label class="form-label">Payment Terms</label>
            <select name="payment_terms" class="form-select">
              <option value="On Receipt">On Receipt</option>
              <option value="Net 15">Net 15</option>
              <option value="Net 30">Net 30</option>
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
  <label class="form-label">Width</label>
  <div class="input-group">
    <input type="number" id="item_width_mm" step="0.01" class="form-control" placeholder="mm">
    <span class="input-group-text">mm</span>
  </div>
  <div class="input-group mt-1">
    <input type="number" id="item_width_ft" step="0.01" class="form-control" placeholder="ft">
    <span class="input-group-text">ft</span>
  </div>
</div>
<div class="col-md-2">
  <label class="form-label">Height</label>
  <div class="input-group">
    <input type="number" id="item_height_mm" step="0.01" class="form-control" placeholder="mm">
    <span class="input-group-text">mm</span>
  </div>
  <div class="input-group mt-1">
    <input type="number" id="item_height_ft" step="0.01" class="form-control" placeholder="ft">
    <span class="input-group-text">ft</span>
  </div>
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
  <label class="form-label">Hardwares</label>
  <input type="text" id="item_hardwares" class="form-control" readonly>
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
              <th>Hardwares</th>
              <th>Total (₹)</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

        <!-- Additions -->
        <div class="row g-2 mb-4">
          <div class="col-md-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="add_shipping">
              <label class="form-check-label" for="add_shipping">Add Shipping Charges</label>
              <input type="text" class="form-control mt-1" id="shipping_value" name="shipping_value" placeholder="0">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="add_discount">
              <label class="form-check-label" for="add_discount">Add Discount (%)</label>
            </div>
           <input type="text" class="form-control" id="discount_value" name="discount_value" placeholder="0">
          </div>
        </div>

        <div class="mb-3">
  <label class="form-label">Notes</label>
  <textarea name="notes" class="form-control" rows="5">• GST 18% extra
• Advance 25%
• Before delivery 25%
• After completing 25%
• Transport charges extra</textarea>
</div>

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
          <button type="submit" class="btn btn-success">Save Quotation</button>
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
  const clientPhone = $('#client_phone').val(); // ✅ Fixed

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
      hardware: cols.eq(11).text().trim() || '',
      total: parseFloat(cols.eq(12).text()) || 0
    });
  });

  $('#items_json').val(JSON.stringify(items));

  setTimeout(() => this.submit(), 0); // ✅ Better way to trigger submit
});


//... rest of JS functions unchanged ...
</script>


<script>
         function updateSubTotal() {
  let subtotal = 0;
  let cgst = 0;
  let sgst = 0;
  let grandTotal = 0;

  $('#particular_table tbody tr').each(function () {
    const total = parseFloat($(this).find('td').eq(12).text()) || 0;
    const taxPercent = parseFloat($(this).find('td').eq(10).text()) || 0;

    const baseAmount = total / (1 + (taxPercent / 100));

    subtotal += baseAmount;
    grandTotal += total;

    // Only add GST if tax is applied
    if (taxPercent > 0) {
      const cgstAmount = baseAmount * 0.09;
      const sgstAmount = baseAmount * 0.09;
      cgst += cgstAmount;
      sgst += sgstAmount;
    }
  });

  const shippingChecked = $('#add_shipping').is(':checked');
  const shippingValue = parseFloat($('#shipping_value').val()) || 0;
  if (shippingChecked) {
    grandTotal += shippingValue;
  }

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
function initSelect2(selector, url) {
  $(selector).select2({
    placeholder: 'Search...',
    allowClear: true,
    ajax: {
      url: url,
      dataType: 'json',
      delay: 250,
      data: function(params) {
        return {
          q: params.term
        };
      },
      processResults: function(data) {
        return {
          results: data.map(item => ({
            id: item.value,
            text: item.label,
            phone: item.phone  // ✅ Add this line to preserve phone
          }))
        };
      },
      cache: true
    },
    width: '100%'
  });
}


  
// DO NOT initialize select2 on #client_phone
initSelect2('#client_name', 'ajax/client_names.php');

$('#client_name').on('select2:select', function(e) {
  const selectedData = e.params.data;
  $('#client_phone').val(selectedData.phone); // ✅ will now work
  $('#client_phone_text').val(selectedData.phone);
  console.log('Selected Client Phone:', selectedData.phone);
});





  
  // initSelect2('#client_phone', 'ajax/client_phones.php');
  initSelect2('#item_name', 'ajax/item_names.php');
  initSelect2('#item_name', 'ajax/items_by_name.php');


  $('#item_name').on('select2:select', function(e) {
  var itemId = e.params.data.id;
  $.ajax({
    url: 'ajax/item_names.php',
    data: { id: itemId },
    dataType: 'json',
    success: function(data) {
      $('#item_price').val(data.price);
      $('#item_tax').val(data.tax);
      $('#item_hardwares').val(data.hardwares); // ✅ this is correct
    }
  });
});

});


</script>




<script>
function calculateArea() {
  let width_ft = 0;
  let height_ft = 0;

  const width_mm = parseFloat($('#item_width_mm').val()) || 0;
  const width_ft_input = parseFloat($('#item_width_ft').val()) || 0;
  const height_mm = parseFloat($('#item_height_mm').val()) || 0;
  const height_ft_input = parseFloat($('#item_height_ft').val()) || 0;

  // Determine which inputs are filled
  if (width_ft_input > 0) {
    width_ft = width_ft_input;
  } else if (width_mm > 0) {
    width_ft = width_mm / 304.8; // 1 ft = 304.8 mm
  }

  if (height_ft_input > 0) {
    height_ft = height_ft_input;
  } else if (height_mm > 0) {
    height_ft = height_mm / 304.8;
  }

  const sqft = width_ft * height_ft;
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
  $('#item_width_mm, #item_width_ft, #item_height_mm, #item_height_ft, #item_qty').on('input', calculateArea);
});


  
</script>
<script>
let serialNo = 1;
$('#add_particular').on('click', function () {
  const name = $('#item_name').select2('data')[0]?.text || '';
  const location = $('#location').val().trim();
  const glassType = $('#glass_type').val().trim();

  const width_mm = parseFloat($('#item_width_mm').val()) || 0;
  const width_ft = parseFloat($('#item_width_ft').val()) || 0;
  const height_mm = parseFloat($('#item_height_mm').val()) || 0;
  const height_ft = parseFloat($('#item_height_ft').val()) || 0;
  const hardware = $('#item_hardwares').val().trim();


  // Decide which values to show
  const width_display = width_mm > 0 ? `${width_mm} mm` : (width_ft > 0 ? `${width_ft} ft` : '');
  const height_display = height_mm > 0 ? `${height_mm} mm` : (height_ft > 0 ? `${height_ft} ft` : '');

  const sqft = $('#item_in_sqft').val();
  const qty = $('#item_qty').val();
  const totalSqft = $('#item_total_sqft').val();
  const price = $('#item_price').val();
  const tax = $('#item_tax').val();
  const total = $('#item_total').val();

  // Validation
  if (!name || (!width_mm && !width_ft) || (!height_mm && !height_ft) || !qty) {
    alert('Please fill in all required fields (width and height in mm or ft)');
    return;
  }

  const newRow = `
   <tr data-item-name="${name}">
    <td>${serialNo++}</td>
    <td>${name}</td>
    <td>${location}</td>
    <td>${glassType}</td>
    <td>${width_display}</td>
    <td>${height_display}</td>
    <td>${sqft}</td>
    <td>${qty}</td>
    <td>${totalSqft}</td>
    <td>${price}</td>
    <td>${tax}</td>
    <td>${hardware}</td>  // Insert as 10th column

    <td>${total}</td>
    <td><button type="button" class="btn btn-sm btn-danger remove-row">Delete</button></td>
  </tr>
  `;
  $('#particular_table tbody').append(newRow);

  // Clear fields
  $('#item_name').val(null).trigger('change');
  $('#location, #glass_type, #item_width_mm, #item_width_ft, #item_height_mm, #item_height_ft, #item_qty, #item_in_sqft, #item_total_sqft, #item_price, #item_tax, #item_total').val('');
});

// Remove row handler
$(document).on('click', '.remove-row', function () {
  $(this).closest('tr').remove();
  updateSubTotal();
});

</script>

</body>
</html>