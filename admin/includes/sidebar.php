<!-- includes/sidebar.php -->

<!-- Mobile Top Bar -->
<div class="topbar d-md-none">
  <h4 class="m-0">UWindows</h4>
  <button onclick="toggleSidebar()">☰</button>
</div>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
  <div class="d-flex justify-content-between align-items-center d-md-none mb-3">
    <h4 class="m-0">UWindows</h4>
    <button class="btn btn-outline-light btn-sm" onclick="toggleSidebar()">✕</button>
  </div>

  <h4 class="text-center d-none d-md-block">UWindows</h4>
  <hr class="border-light">

  <a href="index.php">🏠 Dashboard</a>
<a href="customers.php">🧑‍💼 Customers</a>
<a href="customer_transactions.php">🧑‍💼 Customers Transactions</a>
<a href="quotation.php">📝 Quotation</a>
<a href="invoices.php">💵 Invoices</a>
<a href="salesitem.php">📦 Items</a>
<a href="glass_list.php">🧊 Glass</a>
<!-- <a href="reports.php">📊 Reports</a> -->
<a href="employeedetails.php">👔 Employees</a>
<a href="view_messages.php">✉️ Contact</a>
<a href="manage_vacancies.php">💼 Career</a>
<a href="feedback.php">✉️ Feedback</a>

 
 
  <hr class="border-light">
  <a href="logout.php" style="color: #dc3545;">🔒 Log Out</a>
</div>

<script>
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
  }
</script>
