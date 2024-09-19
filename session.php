<?php

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    // If not logged in, set a default username
    $username = 'Guest';
} else {
    // Otherwise, use the full name from the session
    $username = htmlspecialchars($_SESSION['full_name']); // Sanitize for safety
}

?>