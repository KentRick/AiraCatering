<?php
// File: menu.php
include 'db_connect.php'; // Include the database connection file

// Fetch menu data

$sql = "SELECT category, item FROM menu ORDER BY category";
$result = $conn->query($sql);

// Prepare menu data
$menu = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $menu[$row['category']][] = $row['item'];
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu</title>

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
            <!-- Removed links here -->
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
  <!-- Navbar End -->


  <!-- MENUS -->
  <style>
    /* Add space above the footer */
    #menu-grid {
      text-align: center;
      /* Center-align the contents */
      margin-bottom: 40px;
      /* Adjust this value to control the space above the footer */
    }

    /* Other existing styles */
    #menu-grid .page-title {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
      /* Space below the title */
    }

    #menu-grid .grid-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      /* 3 equal columns */
      grid-template-rows: repeat(3, auto);
      /* Automatic row height based on content */
      gap: 10px;
      /* Space between grid items */
      width: 100%;
      max-width: 800px;
      margin: 0 auto;
      /* Center the grid container */
    }

    #menu-grid .grid-item {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      background-color: #fff;
      border: 1px solid #ddd;
      padding: 20px;
      font-size: 16px;
      text-align: center;
      color: #333;
      overflow: hidden;
      /* Hide overflow if necessary */
    }

    #menu-grid .category-title {
      margin-bottom: 10px;
      /* Space between title and checkboxes */
      font-weight: bold;
    }

    #menu-grid .checkbox-container {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    #menu-grid .checkbox-container label {
      margin-bottom: 5px;
      /* Space between checkboxes */
    }
  </style>

  <div id="menu-grid">
    <h1 class="text-primary fw-bold mb-5 text-center">
      Me<span class="text-dark">nu</span>
    </h1>
    <div class="grid-container">
      <?php foreach ($menu as $category => $items): ?>
        <div class="grid-item">
          <div class="category-title"><?php echo htmlspecialchars($category); ?></div>
          <div class="checkbox-container">
            <?php foreach ($items as $item): ?>
              <label><input type="checkbox"> <?php echo htmlspecialchars($item); ?></label>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <!-- END MENU -->

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
  <a href="#" class="btn btn-md-square btn-primary rounded-circle back-to-top">
    <i class="fa fa-arrow-up"></i>
  </a>

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