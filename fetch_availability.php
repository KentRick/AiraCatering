<?php
// fetch_availability.php - Fetch availability based on selected month and year
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $year = $data['year'];
    $month = $data['month'];

    $stmt = $conn->prepare("SELECT DAY(date) as day, slots FROM availability WHERE YEAR(date) = ? AND MONTH(date) = ?");
    $stmt->bind_param("ii", $year, $month);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $availability = [];
    
    while ($row = $result->fetch_assoc()) {
        $day = (int)$row['day'];
        $availability[$day] = [
            'status' => ($row['slots'] > 0) ? 'available' : 'booked',
            'slots' => $row['slots'],
        ];
    }

    echo json_encode($availability);
    exit();
}
?>
