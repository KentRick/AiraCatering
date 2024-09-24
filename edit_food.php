<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['foodId'], $_POST['foodName'])) {
    $foodId = intval($_POST['foodId']);
    $foodName = $conn->real_escape_string(trim($_POST['foodName']));

    if (!empty($foodName)) {
        $updateSql = "UPDATE menu SET item = '$foodName' WHERE id = $foodId";

        if ($conn->query($updateSql) === TRUE) {
            header('Location: edit_menu.php?message=updated');
            exit();
        } else {
            echo "Error: " . $conn->error; // For debugging
        }
    }
}

$conn->close();
?>
