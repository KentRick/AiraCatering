<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Calendar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/antd@5.20.6/dist/reset.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/antd@5.20.6/dist/antd.min.css">
    <style>
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
        }
        .event {
            margin-top: 5px;
            padding: 5px;
            border-radius: 4px;
            color: white;
            font-size: 12px;
        }
        .success {
            background-color: #4caf50;
        }
        .warning {
            background-color: #ff9800;
        }
        .error {
            background-color: #f44336;
        }
        .notes-month {
            font-size: 20px;
            margin: 10px 0;
            grid-column: span 7;
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="calendar"></div>

    <script>
        const events = {
            8: [
                { type: 'warning', content: 'This is warning event.' },
                { type: 'success', content: 'This is usual event.' },
            ],
            10: [
                { type: 'warning', content: 'This is warning event.' },
                { type: 'success', content: 'This is usual event.' },
                { type: 'error', content: 'This is error event.' },
            ],
            15: [
                { type: 'warning', content: 'This is warning event.' },
                { type: 'success', content: 'This is very long usual event......' },
                { type: 'error', content: 'This is error event 1.' },
                { type: 'error', content: 'This is error event 2.' },
                { type: 'error', content: 'This is error event 3.' },
                { type: 'error', content: 'This is error event 4.' },
            ],
        };

        const getListData = (date) => {
            return events[date] || [];
        };

        const createCalendar = () => {
            const calendar = document.getElementById('calendar');
            const monthDays = new Date(2024, 8, 0).getDate(); // September 2024
            const monthDisplay = document.createElement('div');
            monthDisplay.className = 'notes-month';
            monthDisplay.innerText = `September 2024`;
            calendar.appendChild(monthDisplay);

            for (let i = 1; i <= monthDays; i++) {
                const day = document.createElement('div');
                day.className = 'day';
                day.innerText = i;

                const dayEvents = getListData(i);
                dayEvents.forEach(event => {
                    const eventDiv = document.createElement('div');
                    eventDiv.className = `event ${event.type}`;
                    eventDiv.innerText = event.content;
                    day.appendChild(eventDiv);
                });

                calendar.appendChild(day);
            }
        };

        createCalendar();
    </script>
</body>
</html>
