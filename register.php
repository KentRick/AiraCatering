<?php
// File: register.php

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
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone_number, birth_date, gender, address, password) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssss', $full_name, $email, $phone_number, $birth_date, $gender, $address, $password);

    // Execute statement and check for errors
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
