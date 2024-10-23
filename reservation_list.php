<?php
require 'db_connect.php';
session_start();

// Check if admin is logged in
//if (!isset($_SESSION['admin_logged_in'])) {
//    header("Location: login.php");
//    exit();
//}

// Handle different actions based on request
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'view':
        // View reservation details
        $reservationId = $_GET['id'] ?? null;
        if ($reservationId) {
            $stmt = $conn->prepare("SELECT r.*, u.first_name, u.last_name FROM reservations r JOIN users u ON r.user_id = u.id WHERE r.id = ?");
            $stmt->bind_param("i", $reservationId);
            $stmt->execute();
            $result = $stmt->get_result();
            $reservation = $result->fetch_assoc();
        } else {
            header("Location: manage_reservations.php");
            exit();
        }
        break;

    case 'edit':
        // Edit reservation details
        $reservationId = $_GET['id'] ?? null;
        if ($reservationId) {
            $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
            $stmt->bind_param("i", $reservationId);
            $stmt->execute();
            $result = $stmt->get_result();
            $reservation = $result->fetch_assoc();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selectedDate = $_POST['selected_date'];
            $eventPackage = $_POST['event_package'];
            $numberOfGuests = $_POST['number_of_guests'];
            $eventType = $_POST['event_type'];
            $otherEvent = $_POST['other_event'];
            $motif = $_POST['motif'];
            $time = $_POST['time'];
            $menuItems = isset($_POST['menu_items']) ? implode(',', $_POST['menu_items']) : '';

            // Prepare SQL statement
            $stmt = $conn->prepare("UPDATE reservations SET selected_date=?, event_package=?, number_of_guests=?, event_type=?, other_event=?, motif=?, time=?, menu_items=? WHERE id=?");
            $stmt->bind_param("ssisssssi", $selectedDate, $eventPackage, $numberOfGuests, $eventType, $otherEvent, $motif, $time, $menuItems, $reservationId);

            // Execute and redirect
            if ($stmt->execute()) {
                header("Location: manage_reservations.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        break;

    case 'delete':
        // Delete reservation
        $reservationId = $_GET['id'] ?? null;
        if ($reservationId) {
            $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
            $stmt->bind_param("i", $reservationId);
            if ($stmt->execute()) {
                header("Location: manage_reservations.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        break;

    default:
        // Default case: List all reservations
        $sql = "SELECT r.*, u.first_name, u.last_name FROM reservations r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC";
        $result = $conn->query($sql);
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservation List</title>

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
</head>
<body>

  <div class="wrapper d-flex align-items-stretch">
    <?php include 'admin/includes/sidebar.php'; ?>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5 pt-5">
      <h2 class="mb-4">Reservation List</h2>
      <p>This page will display a list of reservations.</p>

      <?php if ($action === 'view' && isset($reservation)): ?>
        <h2>Reservation Details</h2>
        <table class="table table-bordered">
            <tr><th>ID</th><td><?php echo htmlspecialchars($reservation['id']); ?></td></tr>
            <tr><th>User</th><td><?php echo htmlspecialchars($reservation['first_name'] . ' ' . $reservation['last_name']); ?></td></tr>
            <tr><th>Event Date</th><td><?php echo htmlspecialchars($reservation['selected_date']); ?></td></tr>
            <tr><th>Event Package</th><td><?php echo htmlspecialchars($reservation['event_package']); ?></td></tr>
            <tr><th>Number of Guests</th><td><?php echo htmlspecialchars($reservation['number_of_guests']); ?></td></tr>
            <tr><th>Event Type</th><td><?php echo htmlspecialchars($reservation['event_type']); ?></td></tr>
            <tr><th>Menu Items</th><td><?php echo nl2br(htmlspecialchars($reservation['menu_items'])); ?></td></tr>
        </table>
        <a href="manage_reservations.php" class="btn btn-secondary">Back to Reservations</a>

      <?php elseif ($action === 'edit' && isset($reservation)): ?>
        <h2>Edit Reservation</h2>
        <form action="" method="post">
            <div class="mb-3">
                <label for="selected_date" class="form-label">Event Date</label>
                <input type="date" name="selected_date" id="selected_date" class="form-control" value="<?php echo htmlspecialchars($reservation['selected_date']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="event_package" class="form-label">Event Package</label>
                <input type="text" name="event_package" id="event_package" class="form-control" value="<?php echo htmlspecialchars($reservation['event_package']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="number_of_guests" class="form-label">Number of Guests</label>
                <input type="number" name="number_of_guests" id="number_of_guests" class="form-control" value="<?php echo htmlspecialchars($reservation['number_of_guests']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="event_type" class="form-label">Event Type</label>
                <input type="text" name="event_type" id="event_type" class="form-control" value="<?php echo htmlspecialchars($reservation['event_type']); ?>">
            </div>
            <div class="mb-3">
                <label for="other_event" class="form-label">Other Event</label>
                <input type="text" name="other_event" id="other_event" class="form-control" value="<?php echo htmlspecialchars($reservation['other_event']); ?>">
            </div>
            <div class="mb-3">
                <label for="motif" class="form-label">Motif</label>
                <input type="text" name="motif" id="motif" class="form-control" value="<?php echo htmlspecialchars($reservation['motif']); ?>">
            </div>
            <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <input type="time" name="time" id="time" class="form-control" value="<?php echo htmlspecialchars($reservation['time']); ?>">
            </div>
            <div class="mb-3">
                <label for="menu_items" class="form-label">Menu Items (Comma-separated)</label>
                <input type="text" name="menu_items" id="menu_items" class="form-control" value="<?php echo htmlspecialchars($reservation['menu_items']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Reservation</button>
            <a href="manage_reservations.php" class="btn btn-secondary">Cancel</a>
        </form>

      <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Event Date</th>
                    <th>Package</th>
                    <th>Guests</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['selected_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['event_package']); ?></td>
                            <td><?php echo htmlspecialchars($row['number_of_guests']); ?></td>
                            <td>
                                <a href="?action=view&id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View</a>
                                <a href="?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this reservation?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No reservations found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
      <?php endif; ?>
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

<?php
$conn->close();
?>
