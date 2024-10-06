<?php

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['first_name'])) {
    // If not logged in, set a default username
    $user = 'Guest';
} else {
    // Otherwise, use the full name from the session
    $user = htmlspecialchars($_SESSION['first_name']); // Sanitize for safety
}

?>
