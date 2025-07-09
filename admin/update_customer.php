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
  $id = intval($_POST['id']);

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

  $sql = "UPDATE customers SET 
            company_name = ?, phone = ?, email = ?, gstin = ?, pan = ?, website = ?,
            billing_address = ?, billing_country = ?, billing_state = ?, billing_city = ?, billing_pincode = ?,
            shipping_address = ?, shipping_country = ?, shipping_state = ?, shipping_city = ?, shipping_pincode = ?,
            contact_person_name = ?, contact_person_phone = ?, notes = ?
          WHERE id = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssssssssssssssssi",
    $company_name, $phone, $email, $gstin, $pan, $website,
    $billing_address, $billing_country, $billing_state, $billing_city, $billing_pincode,
    $shipping_address, $shipping_country, $shipping_state, $shipping_city, $shipping_pincode,
    $contact_person, $contact_phone, $notes, $id);

  if ($stmt->execute()) {
    header("Location: customers.php?status=updated");
    exit;
  } else {
    echo '<div class="alert alert-danger m-4">Failed to update customer.</div>';
  }
} else {
  echo '<div class="alert alert-danger m-4">Invalid request method.</div>';
}
