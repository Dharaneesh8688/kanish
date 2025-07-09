<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us | U Windows - UPVC Windows & Doors in Erode</title>
  <meta name="description" content="Learn about UWindows - Erode's leading UPVC windows and doors manufacturer, known for quality, innovation, and customer trust.">
  <link rel="icon" type="image/png" href="asset/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.7;
    }
    .about-hero {
      background: url('https://images.unsplash.com/photo-1604147706288-b76e174dbbc2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80') center center/cover no-repeat;
      color: white;
      padding: 120px 0;
      text-align: center;
      position: relative;
    }
    .about-hero::after {
      content: "";
      position: absolute;
      inset: 0;
      background-color: rgba(0,0,0,0.6);
    }
    .about-hero .container {
      position: relative;
      z-index: 1;
    }
    .mission-section {
      background-color: #f8f9fa;
      padding: 60px 0;
    }
    .values-section {
      padding: 60px 0;
    }
    footer {
      background-color: #222;
      color: #ccc;
      padding: 20px 0;
      text-align: center;
    }
    .whatsapp-float {
  position: fixed;
  bottom: 90px; /* above navbar */
  right: 20px;
  background-color: #25d366;
  color: white;
  border-radius: 50%;
  padding: 14px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3);
  z-index: 999;
  text-align: center;
  transition: background-color 0.3s ease;
}
.whatsapp-float:hover {
  background-color: #20b958;
  color: white;
  text-decoration: none;
}

    
.nav-link.text-white i {
  color: white !important;
}
  </style>
</head>
<body>

  <!-- Navbar -->
  <?php include 'bottom-navbar.php'; ?>

  

  <!-- Hero Section -->
  <section class="about-hero">
    <div class="container">
      <h1 class="display-4">About U Windows</h1>
      <p class="lead">Your trusted partner in modern UPVC windows and doors</p>
    </div>
  </section>

  <!-- Mission Section -->
  <section class="mission-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
          <img src="asset/logo.png" alt="About U Windows" class="img-fluid rounded shadow-sm">
        </div>
        
        <div class="col-md-6">
          <h2>Our Mission</h2>
          <p>
            At U Windows, our mission is to redefine living and working spaces with innovative, energy-efficient UPVC windows and doors. We are committed to delivering exceptional quality and craftsmanship to every customer, ensuring each installation not only looks stunning but also performs flawlessly for decades.
          </p>
          <p>
            Based in Erode, we take pride in supporting local communities and contributing to a more sustainable, comfortable future.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Values Section -->
  <section class="values-section">
    <div class="container">
      <h2 class="text-center mb-5">Why Choose U Windows?</h2>
      <div class="row text-center">
        <div class="col-md-4 mb-4">
          <h5>Quality You Can Trust</h5>
          <p>We use premium-grade UPVC materials and strict quality control to ensure superior durability, thermal insulation, and security.</p>
        </div>
        <div class="col-md-4 mb-4">
          <h5>Customized Solutions</h5>
          <p>From modern villas to commercial spaces, we design and install windows and doors tailored to your vision and functional needs.</p>
        </div>
        <div class="col-md-4 mb-4">
          <h5>Excellent Service</h5>
          <p>Our experienced team is here to guide you every step of the way—from consultation to installation and after-sales support.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
 
 <?php include 'sections/footer.php'; ?>
<!-- Floating WhatsApp Button -->
<a href="https://wa.me/919688022233" target="_blank" class="whatsapp-float">
  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="white" class="bi bi-whatsapp" viewBox="0 0 16 16">
    <path d="M13.601 2.326A7.82 7.82 0 0 0 8.011.02a7.89 7.89 0 0 0-6.63 11.946L.03 16l4.156-1.342A7.89 7.89 0 0 0 8.011 16h.003a7.86 7.86 0 0 0 5.587-2.328 7.823 7.823 0 0 0 0-11.346zm-5.59 12.05h-.002a6.54 6.54 0 0 1-3.356-.949l-.24-.144-2.467.797.815-2.405-.156-.247a6.556 6.556 0 0 1 5.406-10.15 6.5 6.5 0 0 1 4.608 11.108 6.523 6.523 0 0 1-4.608 1.99zm3.615-4.89c-.197-.098-1.168-.578-1.35-.644-.18-.067-.31-.098-.44.098-.131.197-.506.644-.62.777-.114.131-.23.148-.426.05-.197-.098-.831-.306-1.58-.976-.584-.522-.977-1.168-1.09-1.365-.114-.197-.012-.303.086-.4.088-.087.197-.23.295-.344.098-.115.131-.197.197-.328.066-.131.033-.246-.017-.344-.05-.098-.44-1.062-.603-1.454-.159-.382-.321-.33-.44-.336l-.374-.007a.718.718 0 0 0-.523.246c-.18.197-.69.678-.69 1.654s.707 1.918.805 2.05c.098.131 1.391 2.12 3.376 2.972.472.203.84.323 1.127.414.473.151.903.13 1.244.08.38-.056 1.168-.477 1.334-.938.164-.459.164-.853.115-.938-.05-.084-.18-.131-.377-.23z"/>
  </svg>
</a>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
