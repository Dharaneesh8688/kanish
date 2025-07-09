<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Our Products | UWindows</title>
  <meta name="description" content="Explore our range of premium UPVC windows and doors.">
  <link rel="icon" type="image/png" href="asset/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.7;
    }
    .product-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    }
    .product-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
    footer {
      background-color: #222;
      color: #ccc;
      padding: 20px 0;
      text-align: center;
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
  </style>
</head>
<body>

<!-- Navbar Include -->
<?php include 'bottom-navbar.php'; ?>

<section class="py-5">
  <div class="container">
    <h1 class="text-center mb-4">Our Products</h1>
    <p class="text-center mb-5">Explore our wide range of UPVC windows and doors, designed to enhance your space.</p>

    <div class="row g-4">
      <!-- Product 1 -->
      <div class="col-md-4">
        <div class="product-card">
          <img src="https://via.placeholder.com/400x300?text=Sliding+Window" alt="Sliding Window">
          <div class="p-3">
            <h5>Sliding Window</h5>
            <p>Smooth-gliding UPVC sliding windows offering excellent ventilation and modern aesthetics.</p>
          </div>
        </div>
      </div>
      <!-- Product 2 -->
      <div class="col-md-4">
        <div class="product-card">
          <img src="https://via.placeholder.com/400x300?text=Casement+Door" alt="Casement Door">
          <div class="p-3">
            <h5>Casement Door</h5>
            <p>Strong and elegant UPVC casement doors designed for security and style.</p>
          </div>
        </div>
      </div>
      <!-- Product 3 -->
      <div class="col-md-4">
        <div class="product-card">
          <img src="https://via.placeholder.com/400x300?text=Fixed+Window" alt="Fixed Window">
          <div class="p-3">
            <h5>Fixed Window</h5>
            <p>Energy-efficient fixed windows that maximize natural light and insulation.</p>
          </div>
        </div>
      </div>
      <!-- Add more products below -->
      <div class="col-md-4">
        <div class="product-card">
          <img src="https://via.placeholder.com/400x300?text=Combination+Window" alt="Combination Window">
          <div class="p-3">
            <h5>Combination Window</h5>
            <p>Flexible designs combining fixed and sliding panels for custom solutions.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-card">
          <img src="https://via.placeholder.com/400x300?text=French+Door" alt="French Door">
          <div class="p-3">
            <h5>French Door</h5>
            <p>Classic French doors crafted with precision and modern UPVC technology.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-card">
          <img src="https://via.placeholder.com/400x300?text=Ventilator" alt="Ventilator">
          <div class="p-3">
            <h5>Ventilator</h5>
            <p>Perfect ventilators for bathrooms and kitchens ensuring optimal airflow.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<a href="https://wa.me/919688022233" target="_blank" class="whatsapp-float">
  <i class="bi bi-whatsapp fs-3"></i>
</a>

<?php include 'sections/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
