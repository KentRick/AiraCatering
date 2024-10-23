<?php
// Include your database connection
include 'db_connect.php';

header('Content-Type: application/json');

$query = "SELECT date, time_slot, status FROM availability";
$result = mysqli_query($conn, $query);

$events = [];

while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['date'];
    $time_slot = $row['time_slot'];
    $status = $row['status'];

    $monthYearKey = date('Y-m', strtotime($date));
    $dayKey = date('j', strtotime($date));

    if (!isset($events[$monthYearKey])) {
        $events[$monthYearKey] = [];
    }

    if (!isset($events[$monthYearKey][$dayKey])) {
        $events[$monthYearKey][$dayKey] = [];
    }

    $events[$monthYearKey][$dayKey][] = [
        'type' => $status === 'available' ? 'available' : 'reserved',
        'content' => $status === 'available' ? 'Reservation is open' : 'Reserved',
    ];
}

echo json_encode($events);
