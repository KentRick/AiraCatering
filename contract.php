<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['users'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit(); // Stop further execution
}

include 'db_connect.php';

// Fetch event packages from the database
$sql = "SELECT title, description FROM event_packages";
$result = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contract</title>

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
  <link href="css/style.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/ionicons.min.css">
</head>

<body>

  <!-- Navbar start -->
  <?php include 'header.php';?>
  <!-- Navbar End -->

  <style>
    /* Unique styles to avoid conflict with style.css */
    .custom-body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .custom-form-container {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 500px;
      margin-top: 20px;
      margin-bottom: 20px;
    }

    .custom-h1 {
      font-size: 2.5rem;
      color: #333;
      align-items: center;
      margin-top: 30px;
    }

    .custom-form {
      display: flex;
      flex-direction: column;
    }

    .custom-label {
      margin: 10px 0 5px;
      font-weight: bold;
    }

    .custom-input,
    .custom-select {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      margin-bottom: 15px;
      font-size: 16px;
    }

    .custom-button {
      background-color: #275437;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }

    .custom-button:hover {
      background-color: #fff;
      color: #000;
    }

    .custom-hidden {
      display: none;
    }

    .grayed-out {
      background-color: #e9ecef;
      /* Light gray background */
      color: #6c757d;
      /* Gray text color */
    }
  </style>

  <!-- Event Details -->
  <h1 class="text-primary fw-bold mb-0 text-center custom-h1" style="font-size: calc(1.375rem + 1.5vw);">
    Con<span class="text-dark">tract</span>
  </h1>
  <div class="try" style="justify-content: center; display:flex;">
    <div class="custom-form-container">
      <h1>Event Details</h1>
      <form action="menu.php" method="post" class="custom-form">
        <label for="event-packages" class="custom-label">Event Packages:</label>
        <select id="event-packages" name="event-packages" class="custom-select" required>
          <option value="">Select an event type</option>
          <option value="Custom" data-description="Custom event package" data-guests="N/A">Custom</option>
          <?php
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              // Assuming description contains the number of guests and event type
              preg_match('/\d+/', $row['description'], $matches);
              $numberOfGuests = isset($matches[0]) ? $matches[0] : 'N/A';
              echo '<option value="' . htmlspecialchars($row['title']) . '" 
                    data-description="' . htmlspecialchars($row['description']) . '" 
                    data-guests="' . $numberOfGuests . '" 
                    data-event-type="' . htmlspecialchars($row['event_type']) . '">' .
                htmlspecialchars($row['title']) .
                '</option>';
            }
          } else {
            echo '<option value="">No packages available</option>';
          }
          ?>
        </select>

        <label for="number-of-guests" class="custom-label">Number Of Guests:</label>
        <input type="number" id="number-of-guests" name="number-of-guests" class="custom-input" min="1" required disabled>

        <!-- Event Type Input -->
        <div id="event-type-container" class="custom-hidden">
          <label for="event-type" class="custom-label">Type of Event:</label>
          <input type="text" id="event-type" name="event-type" class="custom-input" required placeholder="Specify event type">
        </div>

        <div id="other-event-container" class="custom-hidden">
          <label for="other-event" class="custom-label">Specify Event:</label>
          <input type="text" id="other-event" name="other-event" class="custom-input">
        </div>

        <label for="motif" class="custom-label">Motif:</label>
        <input type="text" id="motif" name="motif" class="custom-input" required>

        <label for="time" class="custom-label">Time:</label>
        <input type="time" id="time" name="time" class="custom-input" required>

        <button type="submit" class="custom-button">Submit</button>
      </form>
    </div>
  </div>
  <!-- Event Details End -->

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
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="js/main.js"></script>

  <script>
    // JavaScript to handle package selection and input enabling
    document.getElementById('event-packages').addEventListener('change', function () {
      var selectedOption = this.options[this.selectedIndex];
      var numberOfGuestsInput = document.getElementById('number-of-guests');
      var eventTypeContainer = document.getElementById('event-type-container');
      var eventTypeInput = document.getElementById('event-type');
      var otherEventContainer = document.getElementById('other-event-container');

      // Populate number of guests based on selected package
      numberOfGuestsInput.value = selectedOption.getAttribute('data-guests');

      // Check if the selected package is "Custom"
      if (selectedOption.value === "Custom") {
        numberOfGuestsInput.disabled = false; // Enable the number of guests input
        eventTypeContainer.classList.remove('custom-hidden'); // Show event type container
        eventTypeInput.disabled = false; // Enable the event type input
        otherEventContainer.classList.add('custom-hidden'); // Hide other event input
        eventTypeInput.value = ''; // Clear the input for custom
      } else {
        numberOfGuestsInput.disabled = true; // Disable the number of guests input
        eventTypeContainer.classList.add('custom-hidden'); // Hide event type container
        eventTypeInput.disabled = true; // Disable the event type input
        eventTypeInput.value = ''; // Clear the input when not custom
        otherEventContainer.classList.add('custom-hidden'); // Hide other event input
      }
    });
  </script>
</body>

</html>
