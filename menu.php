<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}
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

  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      background-color: #f7f7f7;
    }

    #menu-grid {
      text-align: center;
      margin: 40px auto;
      max-width: 1200px;
    }

    #menu-grid h1 {
      font-size: 36px;
      color: #333;
      margin-bottom: 30px;
    }

    .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .grid-item {
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 20px;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .grid-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .category-title {
      font-weight: bold;
      font-size: 18px;
      color: #275437;
      margin-bottom: 15px;
    }

    .checkbox-container {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .checkbox-container label {
      margin: 5px 0;
      font-size: 16px;
      color: #555;
    }

    input[type="checkbox"] {
      margin-right: 10px;
    }
  </style>

</head>

<body>

    <!-- Navbar start -->
    <?php include 'header.php';?>
  <!-- Navbar End -->


  <!-- MENUS -->
  <div id="menu-grid">
    <h1 class="text-primary fw-bold mb-0 text-center" id="Event-Packages-Name">
      ME<span class="text-dark">NU</span>
    </h1>
    <form action="submit_menu.php" method="post"> <!-- Change the action URL as needed -->
      <div class="grid-container">
        <?php foreach ($menu as $category => $items): ?>
          <div class="grid-item">
            <div class="category-title"><?php echo htmlspecialchars($category); ?></div>
            <div class="checkbox-container">
              <?php foreach ($items as $item): ?>
                <label>
                  <input type="checkbox" name="menu_items[]" value="<?php echo htmlspecialchars($item); ?>"> <?php echo htmlspecialchars($item); ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="text-end mt-4">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
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