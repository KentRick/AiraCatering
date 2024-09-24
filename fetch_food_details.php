<?php
include 'db_connect.php';

if (isset($_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $sql = "SELECT id, item FROM menu WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Food item not found.']);
    }

    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>
