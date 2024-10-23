<?php
// Include database connection
require 'db_connect.php'; // Update the path as needed

// Start session to get user ID
session_start();
$userId = $_SESSION['user_id']; // Ensure user ID is stored in session upon login

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $selectedDate = $_POST['selected_date'];
    $eventPackage = $_POST['event_package'];
    $numberOfGuests = $_POST['number_of_guests'];
    $eventType = $_POST['event_type'];
    $otherEvent = $_POST['other_event'];
    $motif = $_POST['motif'];
    $time = $_POST['time'];
    $menuItems = isset($_POST['menu_items']) ? implode(',', $_POST['menu_items']) : '';

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO reservations (user_id, selected_date, event_package, number_of_guests, event_type, other_event, motif, time, menu_items) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississsss", $userId, $selectedDate, $eventPackage, $numberOfGuests, $eventType, $otherEvent, $motif, $time, $menuItems);

    // Execute and check for success
    if ($stmt->execute()) {
        echo "Reservation successfully made!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
