<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['first_name']) || !isset($_SESSION['last_name'])) {
    // If not logged in, set a default username
    $_SESSION['users'] = [
        'first_name' => 'Guest',
        'last_name' => ''
    ];
} else {
    // Otherwise, use the full name from the session
    $_SESSION['users'] = [
        'first_name' => htmlspecialchars($_SESSION['first_name']), // Sanitize for safety
        'last_name' => htmlspecialchars($_SESSION['last_name']) // Assuming you have a last_name session variable
    ];
}
?>
