<?php
// Database configuration
include 'db_connect.php';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add this code before closing your database connection
$result = $conn->query("SELECT COUNT(*) as total FROM users");
$row = $result->fetch_assoc();
$userCount = $row['total'];


// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservation Report</title>

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap" rel="stylesheet" />

  <!-- Icon Font Stylesheet -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

  <!-- Libraries Stylesheet -->
  <link href="lib/animate/animate.min.css" rel="stylesheet" />
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet" />
  <link href="lib/owlcarousel/owl.carousel.min.css" rel="stylesheet" />

  <!-- Customized Bootstrap Stylesheet -->
  <link href="css/bootstrap.min.css" rel="stylesheet" />

  <!-- Template Stylesheet -->
  <link href="css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/ionicons.min.css">
  
  <style>
    .card {
      background-color: #C0C0C0; /* Light gray background */
      border: 1px solid #ced4da; /* Border color */
      color: black;
    }
    .card-title {
      color: black; /* Dark text color for contrast */
    }
    .card-text {
      font-size: 2rem; /* Larger text for numbers */
    }
  </style>
</head>
<body>

  <div class="wrapper d-flex align-items-stretch">
    <?php include 'admin/includes/sidebar.php'; ?>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5 pt-5">
      <h2 class="mb-4">Reservation Report</h2>
      <p>This page will provide reports on reservations.</p>

      <div class="row">
        <div class="col-md-3 mb-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Number of Users</h5>
              <p class="card-text" id="userCount"><?php echo $userCount; ?></p> <!-- Replace with dynamic data -->
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Approved Reservations</h5>
              <p class="card-text" id="approvedCount">26</p> <!-- Replace with dynamic data -->
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Pending Reservations</h5>
              <p class="card-text" id="pendingCount">12</p> <!-- Replace with dynamic data -->
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Cancelled Reservations</h5>
              <p class="card-text" id="cancelledCount">2</p> <!-- Replace with dynamic data -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

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
