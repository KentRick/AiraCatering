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
$sql = "SELECT title, description, pax FROM event_packages";
$result = $conn->query($sql);

// Get the date from URL parameter
$selectedDate = isset($_GET['date']) ? $_GET['date'] : null;

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
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/ionicons.min.css">




</head>

<body>

    <!-- Navbar start -->
    <?php include 'header.php'; ?>
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
            <form action="menu.php" method="post" class="custom-form"> <!-- Changed to POST -->

                <p>Selected Date: <?php echo htmlspecialchars($selectedDate); ?></p>
                <input type="hidden" name="selected-date" value="<?php echo htmlspecialchars($selectedDate); ?>"> <!-- Hidden input for selected date -->

                <label for="event-packages" class="custom-label">Event Packages:</label>
                <select id="event-packages" name="event-packages" class="custom-select" required>
                    <option value="">Select an event type</option>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($row['title']) . '" 
                            data-description="' . htmlspecialchars($row['description']) . '" 
                            data-guests="' . htmlspecialchars($row['pax']) . '">' .
                                htmlspecialchars($row['title']) .
                                '</option>';
                        }
                    } else {
                        echo '<option value="">No packages available</option>';
                    }
                    ?>
                </select>

                <div id="number-of-guests-container" style="display: none;">
                    <span id="number-of-guests-label" class="custom-label"></span> <!-- Label to display guests -->
                </div>
                <label for="multiple-select" class="custom-label">Event Add-ons:</label>
                <select class="form-select" id="multiple-select-field" multiple required>
                    <option value="1">Addon 1</option>
                    <option value="2">Addon 2</option>
                    <option value="3">Addon 3</option>
                </select>

                <label for="multiple-select" class="custom-label">Service Packages:</label>
                <select class="form-select" id="multiple-select-field2" multiple required>
                    <option value="1">Service 1</option>
                    <option value="2">Service 2</option>
                    <option value="3">Service 3</option>
                </select>

                <label for="multiple-select" class="custom-label">Service Add-ons:</label>
                <select class="form-select" id="multiple-select-field3" multiple required>
                    <option value="1">Addon 1</option>
                    <option value="2">Addon 2</option>
                    <option value="3">Addon 3</option>
                </select>


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
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

    <script>
        $('#multiple-select-field').select2({
            theme: "bootstrap-5",
           closeOnSelect: false,
           
        });

        $('#multiple-select-field2').select2({
            theme: "bootstrap-5",
           closeOnSelect: false,
           
        });
        $('#multiple-select-field').select2({
            theme: "bootstrap-5",
           closeOnSelect: false,
           
        });

        $('#multiple-select-field3').select2({
            theme: "bootstrap-5",
           closeOnSelect: false,
           
        });

        // JavaScript to handle the display of guest numbers
        $(document).ready(function() {
            $('#event-packages').change(function() {
                var selectedOption = $(this).find(':selected');
                var guests = selectedOption.data('guests');
                if (guests) {
                    $('#number-of-guests-label').text('Number of Guests: ' + guests);
                    $('#number-of-guests-container').show(); // Show the label with number of guests
                } else {
                    $('#number-of-guests-label').text('');
                    $('#number-of-guests-container').hide(); // Hide the label if no guests
                }
            });
        });
    </script>


</body>

</html>