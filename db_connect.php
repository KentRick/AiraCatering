<?php
// File: db_connect.php

// Database configuration
$host = 'localhost'; // Database host
$dbname = 'database'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

