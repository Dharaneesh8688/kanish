<?php
// Load approved feedbacks
include 'admin/includes/db.php';
$result = $conn->query("SELECT name, message FROM feedbacks WHERE approved=1 ORDER BY created_at DESC LIMIT 5");
$feedbacks = [];
if ($result) {
    while($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>UWindows | Premium UPVC Windows & Doors in Erode</title>
  <meta name="description" content="Transform your home with energy-efficient, premium UPVC windows & doors. Trusted installer in Erode, Tamil Nadu. Get a free quote today.">
  <meta name="keywords" content="UPVC windows, UPVC doors, Erode, Tamil Nadu, energy-efficient windows, window installation">
  <meta name="author" content="UWindows Erode">
  <!-- Open Graph Meta Tags -->
<meta property="og:title" content="UWindows | Premium UPVC Windows & Doors in Erode" />
<meta property="og:description" content="Transform your home with energy-efficient, premium UPVC windows & doors. Trusted installer in Erode, Tamil Nadu. Get a free quote today." />
<meta property="og:image" content="https://www.uwindowserode.com/asset/banner.jpeg" />
<meta property="og:url" content="https://www.uwindowserode.com/" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="UWindows" />

<!-- Twitter Card (optional) -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="UWindows | Premium UPVC Windows & Doors in Erode">
<meta name="twitter:description" content="Transform your home with energy-efficient, premium UPVC windows & doors. Trusted installer in Erode, Tamil Nadu.">
<meta name="twitter:image" content="https://www.uwindowserode.com/asset/banner.jpeg">

  <link rel="icon" type="image/png/jpeg" href="asset/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.7;
     
    }
    .hero {
      background: url('asset/banner.jpeg') center center/cover no-repeat;
      color: white;
      padding: 140px 0;
      text-align: center;
      position: relative;
    }
    .hero::after {
      content: "";
      position: absolute;
      inset: 0;
      background-color: rgba(0,0,0,0.6);
    }
    .hero .container {
      position: relative;
      z-index: 1;
    }
    .badge-highlight {
      background-color: #ffc107;
      color: #000;
      font-weight: 600;
      border-radius: 0.25rem;
      padding: 0.25em 0.5em;
    }
    footer {
      background-color: #222;
      color: #ccc;
      padding: 20px 0;
    }
    .card:hover {
      transform: translateY(-5px);
      transition: transform 0.3s ease;
    }
    .whatsapp-float {
  position: fixed;
  bottom: 90px;
  right: 20px;
  width: 60px;
  height: 60px;
  background-color: #25d366;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3);
  z-index: 999;
  transition: background-color 0.3s ease;
  text-decoration: none;
}

    .whatsapp-float:hover {
      background-color: #20b958;
      text-decoration: none;
    }
.highlight-section {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  background: #f4f3f9;
  padding: 40px;
  align-items: center;
}

.highlight-options {
  flex: 1 1 300px;
}

.highlight-options h2 {
  font-size: 28px;
  margin-bottom: 20px;
}

.highlight-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.highlight-buttons button {
  flex: 1 1 120px;
  background: white;
  border: 1px solid #ccc;
  padding: 15px;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: border 0.3s, box-shadow 0.3s;
}

.highlight-buttons button.active,
.highlight-buttons button:hover {
  border: 2px solid #007bff;
  box-shadow: 0 0 5px rgba(0,0,0,0.2);
}

.highlight-buttons i {
  font-size: 24px;
  margin-bottom: 5px;
}

.explore-btn {
  margin-top: 20px;
  background: #dc3545;
  color: white;
  padding: 10px 20px;
  border: none;
  cursor: pointer;
}

.highlight-image {
  flex: 1 1 400px;
  text-align: center;
}

.highlight-image img {
  max-width: 100%;
  border-radius: 8px;
}


  </style>
</head>
<body>

<?php include 'bottom-navbar.php'; ?>

<!-- Toasts -->
<div class="position-fixed top-0 end-0 p-3" style="z-index:1100">
  <div id="feedbackToast" class="toast text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">Thank you for your feedback!</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
  <div id="contactToast" class="toast text-bg-success border-0 mt-2" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">Thank you! We'll contact you soon.</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<header class="bg-white border-bottom py-3">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <img src="asset/logo.png" alt="UWindows Logo" height="80">
    </div>
    <div>
      <a href="admin" class="btn btn-primary me-2">Admin Login</a>
      <a href="employee" class="btn btn-outline-primary">Employee Login</a>
    </div>
  </div>
</header>


<!-- Hero -->
<section class="hero">
  <div class="container">
    <h1 class="display-5 fw-bold">U Windows</h1><br>
    <h1 class="display-5 fw-bold">Premium UPVC Windows & Doors in Erode</h1>
    <p class="lead">Energy-efficient. Custom-made. Installed with care.</p>
    <span class="badge-highlight">Trusted by 500+ happy customers</span><br>
    <a href="#contact" class="btn btn-warning btn-lg mt-3">Request a Free Quote</a>
  </div>
</section>

<?php include 'sections/about.php'; ?>
<?php include 'sections/portfolio.php'; ?>
<?php include 'sections/highlight.php'; ?>
<?php include 'sections/feedback.php'; ?>
<?php include 'sections/features.php'; ?>
<?php include 'sections/faq.php'; ?>
<?php include 'sections/contact.php'; ?>
<?php include 'sections/footer.php'; ?>

<!-- WhatsApp -->
<a href="https://wa.me/919688022233" target="_blank" class="whatsapp-float">
  <i class="bi bi-whatsapp fs-3"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Contact Form Submission
document.getElementById("contactForm").addEventListener("submit", function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  fetch("save_contact.php", {
    method: "POST",
    body: formData
  })
  .then(response => {
    if(response.ok) {
      form.reset();
      new bootstrap.Toast(document.getElementById("contactToast")).show();
    } else {
      alert("Something went wrong. Please try again.");
    }
  })
  .catch(() => alert("Network error. Please try again."));
});

// Feedback Form Submission
document.getElementById("feedbackFormModal").addEventListener("submit", function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  fetch("save_feedback.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if(data.success) {
      form.reset();
      bootstrap.Modal.getInstance(document.getElementById("feedbackModal")).hide();
      new bootstrap.Toast(document.getElementById("feedbackToast")).show();
    } else {
      alert(data.error || "Something went wrong.");
    }
  })
  .catch(() => alert("Network error. Please try again."));
});


 document.querySelectorAll(".highlight-buttons button").forEach(button => {
    button.addEventListener("click", () => {
      const imageFile = button.getAttribute("data-image");
      document.getElementById("highlight-display").setAttribute("src", imageFile);
    });
  });

</script>

</body>
</html>
