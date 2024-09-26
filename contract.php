<?php
include 'session.php';
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
          <option value="wedding1">Wedding Packages 1</option>
          <option value="wedding2">Wedding Packages 2</option>
          <option value="wedding3">Wedding Packages 3</option>
          <option value="party">Party</option>
          <option value="other">Other</option>
        </select>

        <label for="number-of-guests" class="custom-label">Number Of Guests:</label>
        <input type="number" id="number-of-guests" name="number-of-guests" class="custom-input" min="1" required disabled>


        <div id="event-type-container" class="custom-hidden">
          <label for="event-type" class="custom-label">Type of Event:</label>
          <select id="event-type" name="event-type" class="custom-select" required>
            <option value="N/A" style="display:none;">N/A</option>
            <option value="wedding">Wedding</option>
            <option value="birthday">Birthday</option>
            <option value="conference">Conference</option>
            <option value="party">Party</option>
            <option value="other">Other</option>
          </select>
        </div>



        <!-- Hidden text box for specifying the event when "Other" is selected -->
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
              <a href="#" data-toggle="tooltip" data-placement="top" title="Facebook"><span class="ion-logo-facebook"></span></a>
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
          <span class="text-light">
            <a href="#"><i class="fas fa-copyright text-light me-2"></i>AiraCatering</a>, All rights reserved.
          </span>
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

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const eventPackagesSelect = document.getElementById('event-packages');
      const eventTypeSelect = document.getElementById('event-type');
      const otherEventContainer = document.getElementById('other-event-container');
      const eventTypeContainer = document.getElementById('event-type-container');
      const numberOfGuestsInput = document.getElementById('number-of-guests');
      const form = document.querySelector('form');

      eventPackagesSelect.addEventListener('change', () => {
        if (eventPackagesSelect.value === 'other') {
          otherEventContainer.classList.remove('custom-hidden');
          eventTypeContainer.classList.remove('custom-hidden');
          numberOfGuestsInput.value = '';
          numberOfGuestsInput.disabled = false;
          numberOfGuestsInput.classList.remove('grayed-out');
          numberOfGuestsInput.removeAttribute('readonly'); // Make it editable for 'other'
        } else {
          otherEventContainer.classList.add('custom-hidden');
          eventTypeContainer.classList.add('custom-hidden');

          if (eventPackagesSelect.value) {
            switch (eventPackagesSelect.value) {
              case 'wedding1':
                numberOfGuestsInput.value = 100;
                break;
              case 'wedding2':
                numberOfGuestsInput.value = 150;
                break;
              case 'wedding3':
                numberOfGuestsInput.value = 200;
                break;
              default:
                numberOfGuestsInput.value = '';
            }

            numberOfGuestsInput.disabled = true; // Make it uneditable
            numberOfGuestsInput.classList.remove('grayed-out');
            numberOfGuestsInput.setAttribute('readonly', true); // Set as readonly
          } else {
            numberOfGuestsInput.disabled = false;
            numberOfGuestsInput.classList.remove('grayed-out');
            numberOfGuestsInput.value = '';
            numberOfGuestsInput.removeAttribute('readonly'); // Make it editable again
          }
        }
      });


      eventTypeSelect.addEventListener('change', () => {
        if (eventTypeSelect.value === 'other') {
          otherEventContainer.classList.remove('custom-hidden'); // Show "Specify Event" input
        } else {
          otherEventContainer.classList.add('custom-hidden'); // Hide "Specify Event" input
        }

        // Enable number of guests input if an event package is selected
        if (eventPackagesSelect.value) {
          numberOfGuestsInput.disabled = false;
          numberOfGuestsInput.classList.remove('grayed-out');
        }
      });

      form.addEventListener('submit', (event) => {
        // Check if event type is required and is not selected
        if (eventTypeSelect.required && eventTypeSelect.value === '') {
          eventTypeSelect.focus(); // Focus on the select
          event.preventDefault(); // Prevent form submission
          alert("Please select an event type.");
        }
      });
    });



    form.addEventListener('submit', (event) => {
      // Check if event type is required and is set to "N/A"
      if (eventTypeSelect.required && eventTypeSelect.value === 'N/A') {
        eventTypeSelect.focus(); // Focus on the select
        event.preventDefault(); // Prevent form submission
        alert("Please select a valid event type.");
      }
    });


    /* Example of dynamic guest numbers
const packageGuestNumbers = {
    wedding1: 100,
    wedding2: 150,
    wedding3: 200,
};

 Use the following to set the value dynamically
numberOfGuestsInput.value = packageGuestNumbers[eventPackagesSelect.value] || '';   */
  </script>



</body>

</html>