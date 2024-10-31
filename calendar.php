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
        // Check the slots to determine availability
        $availability[$day] = [
            'status' => ($row['slots'] > 0) ? 'available' : 'booked', // 'available' or 'booked'
            'slots' => $row['slots'], // Store the number of available slots
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
        position: relative;
        cursor: pointer;
    }
    .day.unavailable {
        background-color: #f8d7da;
        color: #721c24;
        cursor: not-allowed;
    }
    .notes-month {
        font-size: 20px;
        margin: 10px 0;
        grid-column: span 7;
        text-align: center;
    }

    /* Responsive styles */
    @media (min-width: 769px) and (max-width: 1000px) {
        #calendar {
            grid-template-columns: repeat(4, 1fr);
        }
        .day {
            padding: 15px;
            font-size: 16px;
        }
    }
    @media (max-width: 768px) {
        .calendar-container {
            flex-direction: column;
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
                    <input type="month" id="month-picker" class="form-control" value="2024-10">
                </div>
                <div id="calendar"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let events = {};

    const fetchAvailability = async (year, month) => {
        try {
            const response = await fetch('fetch_availability.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    year: year,
                    month: month,
                }),
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            events = await response.json();
            updateCalendar(year, month);
        } catch (error) {
            console.error('Error fetching availability:', error);
        }
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

            // Check if the day exists in events and is available
            if (events[i]) {
                if (events[i].status === 'booked') {
                    day.classList.add('unavailable');
                    day.title = "Unavailable";
                } else {
                    // Only make the day clickable if it is available
                    day.classList.remove('unavailable'); // Remove unavailable class
                    day.addEventListener('click', () => {
                        const selectedDate = new Date(year, month, i).toISOString().split('T')[0];
                        window.location.href = `contract.php?date=${selectedDate}`;
                    });
                }
            } else {
                // Mark days without data as unavailable
                day.classList.add('unavailable');
                day.title = "Unavailable";
            }

            calendar.appendChild(day);
        }
    };

    const updateCalendar = (year, month) => {
        createCalendar(year, month);
    };

    document.getElementById('month-picker').addEventListener('change', (event) => {
        const [year, month] = event.target.value.split('-').map(Number);
        fetchAvailability(year, month - 1);
    });

    document.addEventListener('DOMContentLoaded', () => {
        const monthPicker = document.getElementById('month-picker');
        const today = new Date();
        monthPicker.value = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}`;
        fetchAvailability(today.getFullYear(), today.getMonth());
    });
</script>
