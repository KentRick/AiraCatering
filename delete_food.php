<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    $ids = $_POST['ids']; // Array of IDs to delete
    $ids = array_map('intval', $ids); // Sanitize input

    if (!empty($ids)) {
        $idList = implode(',', $ids);
        $deleteSql = "DELETE FROM menu WHERE id IN ($idList)";
        if ($conn->query($deleteSql) === TRUE) {
            echo "Success";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>
