<?php
include 'session.php';
?>

<?php
  include 'calendar.php';
?>
  
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catering Packages</title>

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap"
    rel="stylesheet" />

  <!-- Icon Font Stylesheet -->
  <link
    rel="stylesheet"
    href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
    rel="stylesheet" />

  <!-- Libraries Stylesheet -->
  <link href="lib/animate/animate.min.css" rel="stylesheet" />
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet" />
  <link href="lib/owlcarousel/owl.carousel.min.css" rel="stylesheet" />

  <!-- Customized Bootstrap Stylesheet -->
  <link href="css/bootstrap.min.css" rel="stylesheet" />

  <!-- Template Stylesheet -->
  <link href="css/style.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/ionicons.min.css">
</head>

<body>

  <!-- Navbar start -->
  <div class="container-fluid nav-bar">
    <div class="container">
      <nav class="navbar navbar-light navbar-expand-lg py-4">
        <a href="index.php" class="navbar-brand">
          <h1 class="text-primary fw-bold mb-0">
            Aira<span class="text-dark">Catering</span>
          </h1>
        </a>
        <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
          <span class="fa fa-bars text-primary"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <div class="navbar-nav mx-auto">
            <a href="index.php" class="nav-item nav-link active">Home</a>
            <a href="catering services.php" class="nav-item nav-link">Catering Services</a>
            <a href="catering packages.php" class="nav-item nav-link">Catering Packages</a>
            <a href="#about" class="nav-item nav-link">About Us</a>
            <!-- Updated Button to Open Modal -->
            <a href="#" class="btn btn-primary py-2 px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#exampleModal">Reservation Calendar</a>
          </div>

          <ul class="navbar-nav ms-auto mb-2 mb-lg-0 profile-menu">
            <?php if ($username !== 'Guest'): ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="profile-pic">
                    <img src="https://placehold.co/35" alt="Profile Picture">
                  </div>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" id="username"> <i class="fas fa-user fa-fw"></i> <?php echo $username; ?></a></li>
                  <li><a class="dropdown-item" href="#"> <i class="fas fa-sliders-h fa-fw"></i> Account</a></li>
                  <li><a class="dropdown-item" href="#"> <i class="fas fa-cog fa-fw"></i> Settings</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-fw"></i> Log Out</a></li>
                </ul>
              </li>
            <?php endif; ?>
          </ul>

        </div>
      </nav>
    </div>
  </div>

  <!-- Include the Modal HTML Here -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Reservation Calendar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <input type="month" id="month-picker" class="form-control" value="2024-09" min="" />
          </div>
          <div id="calendar"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Navbar End -->


  <!--Packages-->
  <div id="pack-slider">
    <h1 class="text-primary fw-bold mb-0 text-center" id="Event-Packages-Name">
      Event<span class="text-dark">Packages</span>
    </h1>
    <div class="pack-slide-container">
      <span class="slider-span" id="slider-span1"></span>
      <span class="slider-span" id="slider-span2"></span>
      <span class="slider-span" id="slider-span3"></span>

      <div class="image-slider">
        <div class="slides-div" id="slide-1">
          <img src="img/no-pictures.png" alt="" class="img" id="img1">
          <a href="#" class="button" id="button-1" onclick="showSlide(1)"></a>
        </div>
        <div class="slides-div" id="slide-2">
          <img src="img/no-pictures.png" alt="" class="img" id="img2">
          <a href="#" class="button" id="button-2" onclick="showSlide(2)"></a>
        </div>
        <div class="slides-div" id="slide-3">
          <img src="img/no-pictures.png" alt="" class="img" id="img3">
          <a href="#" class="button" id="button-3" onclick="showSlide(3)"></a>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer Start -->

  <footer class="container-fluid footer-07">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12 text-center">
          <h2 class="footer-heading">
            <a href="#" class="logo">AiraCatering</a>
          </h2>
          <p class="menu">
            <a href="index.php">Home</a>
            <a href="#">Catering Services</a>
            <a href="catering packages.php">Catering Packages</a>
            <a href="index.php#about">About Us</a>
            <a href="#">Contact</a>
          </p>
          <ul class="ftco-footer-social p-0">
            <li class="ftco-animate">
              <a
                href="#"
                data-toggle="tooltip"
                data-placement="top"
                title="Facebook"><span class="ion-logo-facebook"></span></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- Footer End -->

  <!-- Copyright Start -->
  <div class="container-fluid copyright bg-dark py-4">
    <div class="container">
      <div class="row">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
          <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>AiraCatering</a>, All right reserved.</span>
        </div>
      </div>
    </div>
  </div>
  <!-- Copyright End -->



  <!-- Back to Top -->
  <a href="#" class="btn btn-md-square btn-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

  <!-- JavaScript Libraries -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/waypoints/waypoints.min.js"></script>
  <script src="lib/counterup/counterup.min.js"></script>
  <script src="lib/lightbox/js/lightbox.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>

  <!-- Template Javascript -->
  <script src="js/main.js"></script>
</body>

</html>