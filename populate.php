<?php
// db_connect.php - Database connection
include('db_connect.php');

// Function to populate the availability for a given year
function populateAvailability($year, $defaultSlots) {
    global $conn; // Use the database connection from the included file

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO availability (date, slots) VALUES (?, ?)");

    // Loop through each day of the year
    for ($month = 0; $month < 12; $month++) {
        for ($day = 1; $day <= cal_days_in_month(CAL_GREGORIAN, $month + 1, $year); $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month + 1, $day); // Format the date as YYYY-MM-DD

            // Bind parameters and execute
            $stmt->bind_param("si", $date, $defaultSlots);
            if (!$stmt->execute()) {
                echo "Error inserting data for date $date: " . $stmt->error . "\n"; // Handle any errors
            }
        }
    }

    // Close the statement
    $stmt->close();
}

// Example usage: Populate availability for 2024 with 10 available slots for each day
populateAvailability(2025, 4);

// Close the database connection
$conn->close();
?>
