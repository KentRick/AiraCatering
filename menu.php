<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['users'])) {
  // Redirect to login page if not logged in
  header("Location: login.php");
  exit(); // Stop further execution
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

// Retrieve the submitted data using $_GET
$eventPackages = $_GET['event-packages'] ?? 'Not selected';
$numberOfGuests = $_GET['number-of-guests'] ?? 'Not specified';
$eventType = $_GET['event-type'] ?? 'Not specified';
$otherEvent = $_GET['other-event'] ?? 'Not specified';
$motif = $_GET['motif'] ?? 'Not specified';
$time = $_GET['time'] ?? 'Not specified';

// Example selected date, you can adjust this based on your logic
$selectedDate = $_GET['selected-date'] ?? 'Not specified';


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
  <?php include 'header.php'; ?>
  <!-- Navbar End -->


<!-- MENUS -->
<div id="menu-grid">
    <h1 class="text-primary fw-bold mb-0 text-center" id="Event-Packages-Name">
        ME<span class="text-dark">NU</span>
    </h1>
    <form action="submit_menu.php" method="post">
        <div class="grid-container">
            <?php foreach ($menu as $category => $items): ?>
                <div class="grid-item">
                    <div class="category-title"><?php echo htmlspecialchars($category); ?></div>
                    <div class="checkbox-container">
                        <?php foreach ($items as $item): ?>
                            <label>
                                <input type="checkbox" name="menu_items[]" value="<?php echo htmlspecialchars($category . '|' . $item); ?>">
                                <?php echo htmlspecialchars($item); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Hidden fields for additional data -->
        <input type="hidden" name="selected_date" value="<?php echo htmlspecialchars($selectedDate); ?>">
        <input type="hidden" name="event_package" value="<?php echo htmlspecialchars($eventPackages); ?>">
        <input type="hidden" name="number_of_guests" value="<?php echo htmlspecialchars($numberOfGuests); ?>">
        <input type="hidden" name="event_type" value="<?php echo htmlspecialchars($eventType); ?>">
        <input type="hidden" name="other_event" value="<?php echo htmlspecialchars($otherEvent); ?>">
        <input type="hidden" name="motif" value="<?php echo htmlspecialchars($motif); ?>">
        <input type="hidden" name="time" value="<?php echo htmlspecialchars($time); ?>">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId); ?>"> <!-- Ensure userId is defined -->

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
<!-- END MENU -->


  <!-- Modal for Selected Items -->
  <div class="modal fade" id="selectedItemsModal" tabindex="-1" role="dialog" aria-labelledby="selectedItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="selectedItemsModalLabel">Selected Menu Items</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <h2>Event Details:</h2>

        <p><strong>Selected Date:</strong> <?php echo htmlspecialchars($selectedDate); ?></p>
        <p><strong>Event Package:</strong> <?php echo htmlspecialchars($eventPackages); ?></p>
        <p><strong>Number of Guests:</strong> <?php echo htmlspecialchars($numberOfGuests); ?></p>
        <p><strong>Type of Event:</strong> <?php echo htmlspecialchars($eventType); ?></p>
        <p><strong>Specify Other Event:</strong> <?php echo htmlspecialchars($otherEvent); ?></p>
        <p><strong>Motif:</strong> <?php echo htmlspecialchars($motif); ?></p>
        <p><strong>Time:</strong> <?php echo htmlspecialchars($time); ?></p>
        <div class="modal-body">
          <ul id="selectedItemsList"></ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Close</button>
          <button type="button" class="btn btn-primary" onclick="submitMenu()">Confirm</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Function to handle form submission
    document.querySelector('form').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent the default form submission

      const selectedItems = Array.from(document.querySelectorAll('input[name="menu_items[]"]:checked'))
        .map(input => input.value);

      // Create an object to group selected items by category
      const groupedItems = {};

      selectedItems.forEach(item => {
        const [category, itemName] = item.split('|'); // Split category and item
        if (!groupedItems[category]) {
          groupedItems[category] = [];
        }
        groupedItems[category].push(itemName); // Add item to the corresponding category
      });

      // Display selected items in the modal
      const selectedItemsList = document.getElementById('selectedItemsList');
      selectedItemsList.innerHTML = ''; // Clear the list

      for (const [category, items] of Object.entries(groupedItems)) {
        const categoryHeader = document.createElement('strong');
        categoryHeader.textContent = category; // Create a header for the category
        selectedItemsList.appendChild(categoryHeader);

        const itemList = document.createElement('ul');
        items.forEach(itemName => {
          const li = document.createElement('li');
          li.textContent = itemName; // Create a list item for each selected item
          itemList.appendChild(li);
        });

        selectedItemsList.appendChild(itemList); // Append the list of items to the category header
      }

      // Show the modal
      $('#selectedItemsModal').modal('show');
    });

    // Function to submit the menu after confirmation
    function submitMenu() {
      document.querySelector('form').submit(); // Submit the form
    }

    // Function to close the modal and clear the selected items
    function closeModal() {
      const selectedItemsList = document.getElementById('selectedItemsList');
      selectedItemsList.innerHTML = ''; // Clear the selected items list
      $('#selectedItemsModal').modal('hide'); // Hide the modal
    }
  </script>


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

<!-- Floating Messenger Button -->
<?php include 'messenger.php'; ?>

<!-- Back to Top -->
<!-- <a href="#" class="btn btn-md-square btn-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> -->

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