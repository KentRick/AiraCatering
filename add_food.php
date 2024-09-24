<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['foodName'], $_POST['foodCategory'])) {
    $foodName = $conn->real_escape_string(trim($_POST['foodName']));
    $foodCategory = $conn->real_escape_string(trim($_POST['foodCategory']));

    if (!empty($foodName) && !empty($foodCategory)) {
        $insertSql = "INSERT INTO menu (item, category) VALUES ('$foodName', '$foodCategory')";
        if ($conn->query($insertSql) === TRUE) {
            header('Location: edit_menu.php'); // Redirect back after success
            exit();
        } else {
            echo "Error: " . $conn->error; // For debugging
        }
    }
}

$conn->close();
?>
