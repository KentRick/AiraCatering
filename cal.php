<?php
// db_connect.php
include 'db_connect.php'; // Ensure this includes your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $timeSlot = $_POST['time_slot'];
    $status = $_POST['status'];

    $query = "INSERT INTO availability (date, time_slot, status) VALUES ('$date', '$timeSlot', '$status')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .day {
            display: inline-block;
            width: 14%;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .cal-event {
            margin-top: 5px;
            padding: 5px;
            border-radius: 3px;
        }
        .available { background-color: #28a745; color: white; }
        .reserved { background-color: #dc3545; color: white; }
        #calendar { display: flex; flex-wrap: wrap; }
        .notes-month { font-weight: bold; font-size: 1.5em; margin-bottom: 10px; }
    </style>
</head>
<body>

<!-- Reservation Calendar Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reservation Calendar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="month" id="month-picker" class="form-control" value="2024-09">
                </div>
                <div id="calendar"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Modal for Event Actions -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Event Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="eventContent"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="reserveNowButton">Reserve Now</button>
            </div>
        </div>
    </div>
</div>

<!-- Admin Modal for Creating Availability -->
<div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminModalLabel">Create Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createAvailabilityForm">
                    <div class="mb-3">
                        <label for="adminDate" class="form-label">Date</label>
                        <input type="date" id="adminDate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="adminTimeSlot" class="form-label">Time Slot</label>
                        <input type="time" id="adminTimeSlot" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="adminStatus" class="form-label">Status</label>
                        <select id="adminStatus" class="form-control">
                            <option value="available">Available</option>
                            <option value="booked">Booked</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Availability</button>
                </form>
            </div>
        </div>
    </div>
</div>

<button id="openAdminModal" class="btn btn-success m-3">Add Availability</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const events = {
        '2024-09': {
            8: [
                { type: 'available', content: 'Available' },
                { type: 'available', content: 'Available' },
                { type: 'reserved', content: 'Unavailable' },
            ]
        }
    };

    const getListData = (year, month, date) => {
        const monthKey = `${year}-${String(month).padStart(2, '0')}`;
        return (events[monthKey] && events[monthKey][date]) || [];
    };

    const createCalendar = (year, month) => {
        const calendar = document.getElementById('calendar');
        calendar.innerHTML = '';

        const monthDays = new Date(year, month + 1, 0).getDate();
        const monthDisplay = document.createElement('div');
        monthDisplay.className = 'notes-month';
        monthDisplay.innerText = `${new Date(year, month).toLocaleString('default', { month: 'long' })} ${year}`;
        calendar.appendChild(monthDisplay);

        for (let i = 1; i <= monthDays; i++) {
            const day = document.createElement('div');
            day.className = 'day';
            day.innerText = i;

            const dayEvents = getListData(year, month + 1, i);

            dayEvents.forEach(event => {
                const eventDiv = document.createElement('div');
                eventDiv.className = `cal-event ${event.type}`;
                eventDiv.innerText = event.content;
                day.appendChild(eventDiv);

                if (event.type === 'available') {
                    eventDiv.addEventListener('click', () => {
                        document.getElementById('eventContent').innerText = `Date is ${event.content} click reserve now to continue the reservation`;
                        const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
                        eventModal.show();
                    });
                }
            });

            calendar.appendChild(day);
        }
    };

    const updateCalendar = () => {
        const monthPicker = document.getElementById('month-picker');
        const [year, month] = monthPicker.value.split('-').map(Number);
        createCalendar(year, month - 1);
    };

    document.getElementById('month-picker').addEventListener('input', updateCalendar);

    document.addEventListener('DOMContentLoaded', () => {
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth();
        document.getElementById('month-picker').value = `${year}-${String(month + 1).padStart(2, '0')}`;
        updateCalendar();
    });

    // Add functionality for "Reserve Now" button
    document.getElementById('reserveNowButton').addEventListener('click', () => {
        window.location.href = "contract.php"; // Redirect to contract.php
    });

    // Dummy function to simulate saving availability (Replace with actual backend code)
    const saveAvailability = (date, timeSlot, status) => {
        const [year, month, day] = date.split('-');
        const monthKey = `${year}-${month}`;
        
        if (!events[monthKey]) {
            events[monthKey] = {};
        }

        if (!events[monthKey][Number(day)]) {
            events[monthKey][Number(day)] = [];
        }

        events[monthKey][Number(day)].push({
            type: status,
            content: status === 'available' ? 'Reservation is open' : 'Reserved'
        });

        updateCalendar(); // Refresh the calendar after saving
    };

    // Handle form submission for creating availability
    document.getElementById('createAvailabilityForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const date = document.getElementById('adminDate').value;
        const timeSlot = document.getElementById('adminTimeSlot').value;
        const status = document.getElementById('adminStatus').value;

        // Save the availability (AJAX request to PHP backend)
        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                date: date,
                time_slot: timeSlot,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                saveAvailability(date, timeSlot, status);
                const adminModal = bootstrap.Modal.getInstance(document.getElementById('adminModal'));
                adminModal.hide();
                document.getElementById('createAvailabilityForm').reset();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });

    // Event listener to open Admin modal
    document.getElementById('openAdminModal').addEventListener('click', () => {
        const adminModal = new bootstrap.Modal(document.getElementById('adminModal'));
        adminModal.show();
    });
</script>

</body>
</html>
