<?php
// File: admin_login.php

// Database configuration
$host = 'localhost';
$dbname = 'my_database';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT password FROM admins WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if admin exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            echo "Login successful!";
            // Start session and redirect to admin dashboard
            session_start();
            $_SESSION['admin_email'] = $email;
            header('Location: admin.php');
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No admin found with that email address.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
