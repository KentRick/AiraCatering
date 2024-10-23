<?php
// db_connect.php - Database connection
include('db_connect.php');

// Handle AJAX request to fetch availability
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = $_POST['year'];
    $month = $_POST['month'];

    // Fetch availability from the database for the specified month and year
    $stmt = $conn->prepare("SELECT * FROM availability WHERE YEAR(date) = ? AND MONTH(date) = ?");
    $stmt->bind_param("ii", $year, $month);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $availability = [];
    
    while ($row = $result->fetch_assoc()) {
        $day = (int)date('j', strtotime($row['date'])); // Extract the day part of the date
        if (!isset($availability[$day])) {
            $availability[$day] = [];
        }
        $availability[$day][] = [
            'time_slot' => $row['time_slot'],
            'status' => $row['status'],
            'time' => date('H:i', strtotime($row['time'])) // Format time (HH:mm)
        ];
    }

    echo json_encode($availability); // Return as JSON to be used by JavaScript
    exit();
}
?>

<style>
    .calendar-container { 
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
    }
    #calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        margin: 20px;
    }
    .day {
        padding: 20px;
        border: 1px solid #ddd;
        text-align: center;
        transition: background-color 0.3s;
        position: relative; /* For absolute positioning of events */
    }
    .day:hover {
        background-color: #e6f7ff;
    }
    .cal-event {
        margin-top: 5px;
        padding: 5px;
        border-radius: 4px;
        color: white;
        font-size: 12px;
        display: none; /* Initially hide events */
        position: relative; /* Position events relative to the day */
        width: 100%; /* Adjust width to fit inside day */
        box-sizing: border-box; /* Ensure padding is included in width */
        cursor: pointer; /* Show pointer cursor on hover */
    }
    .reserved { 
        background-color: #f44336;
    }
    .available { 
        background-color: #4caf50;
    }
    .notes-month {
        font-size: 20px;
        margin: 10px 0;
        grid-column: span 7;
        text-align: center;
    }

    @media (min-width: 769px) and (max-width: 1000px) {
        #calendar {
            grid-template-columns: repeat(4, 1fr);
        }

        .day {
            padding: 15px;
            font-size: 16px;
        }

        .modal-content {
            margin: 0 20px;
        }
    }

    @media (max-width: 768px) {
        .calendar-container {
            flex-direction: column;
            align-items: stretch;
        }

        #calendar {
            grid-template-columns: repeat(3, 1fr);
        }

        .day {
            padding: 10px;
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        #calendar {
            grid-template-columns: repeat(2, 1fr);
        }

        .day {
            padding: 5px;
            font-size: 12px;
        }
    }

    .current-day {
    background-color: #ffeb3b; /* Change this color as desired */
    font-weight: bold; /* Optional: Make it bold */
}
</style>


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

<script>
    let events = {};

    // Fetch availability from the database
    const fetchAvailability = async () => {
        try {
            const response = await fetch('fetch_availability.php');
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            events = await response.json();
            console.log('Fetched events:', events); // Log the fetched events for debugging
            updateCalendar(); // Update the calendar with the fetched events
        } catch (error) {
            console.error('Error fetching availability:', error);
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
                
                // Only add click event for available events
                if (event.type === 'available') {
                    eventDiv.addEventListener('click', () => {
                        const selectedDate = new Date(year, month, i).toISOString().split('T')[0]; // Format: YYYY-MM-DD
                        window.location.href = `contract.php?date=${selectedDate}`; // Redirect to contract.php with the selected date
                    });
                }
            });

            // Click listener to show all events when the day is clicked
            day.addEventListener('click', () => {
                const eventDivs = day.querySelectorAll('.cal-event');
                eventDivs.forEach(eventDiv => {
                    eventDiv.style.display = 'block'; // Show all events
                });
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
        const monthPicker = document.getElementById('month-picker');
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth();
        monthPicker.value = `${year}-${String(month + 1).padStart(2, '0')}`;
        monthPicker.min = `${year}-${String(month + 1).padStart(2, '0')}`;
        
        fetchAvailability(); // Fetch availability on load
    });

    // Add functionality for "Reserve Now" button
    document.getElementById('reserveNowButton').addEventListener('click', () => {
        window.location.href = "contract.php"; // Redirect to contract.php
    });
</script>

