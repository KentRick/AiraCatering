

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap" rel="stylesheet" />

  <!-- Icon Font Stylesheet -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

  <!-- Libraries Stylesheet -->
  <link href="lib/animate/animate.min.css" rel="stylesheet" />
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet" />
  <link href="lib/owlcarousel/owl.carousel.min.css" rel="stylesheet" />

  <!-- Customized Bootstrap Stylesheet -->
  <link href="css/bootstrap.min.css" rel="stylesheet" />

  <!-- Template Stylesheet -->
  <link href="css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/ionicons.min.css">
  
  <style>
    .chart-container {
      position: relative;
      margin: auto;
      height: 40vh;
      width: 80vw;
    }
  </style>
</head>
<body>

  <div class="wrapper d-flex align-items-stretch">
    <?php include 'admin/includes/sidebar.php'; ?>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5 pt-5">
      <h2 class="mb-4">Dashboard</h2>
      <p>Welcome to the Dashboard. Here you can view and manage various aspects of your application.</p>

      <div class="row">
        <div class="col-md-6">
          <h4>Number of Users</h4>
          <div class="chart-container">
            <canvas id="usersChart"></canvas>
          </div>
        </div>
        <div class="col-md-6">
          <h4>Reservations Status</h4>
          <div class="chart-container">
            <canvas id="reservationsChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript Libraries -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/waypoints/waypoints.min.js"></script>
  <script src="lib/counterup/counterup.min.js"></script>
  <script src="lib/lightbox/js/lightbox.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Template Javascript -->
  <script src="js/main.js"></script>

<?php
// Database configuration
include 'db_connect.php';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the number of users
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    $userCount = $stmt->fetchColumn();

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


  <script>

    // Users Chart
    const ctx1 = document.getElementById('usersChart').getContext('2d');
    const usersChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Active Users'],
            datasets: [{
                label: '# of Users',
                data: [<?php echo $userCount; ?>], // Dynamic user count
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
    scales: {
        y: {
            beginAtZero: true,
            max: 50 // Set this to a higher value than expected user count
        }
    }
}
    });

    // Fetch number of users from the database
    $.ajax({
        url: 'get_users.php',
        method: 'GET',
        success: function(data) {
            const userCount = JSON.parse(data);
            updateUsersChart(userCount);
        },
        error: function(err) {
            console.error('Error fetching user data:', err);
        }
    });

    function updateUsersChart(userCount) {
        usersChart.data.datasets[0].data[0] = userCount;
        usersChart.update();
    }

    // Reservations Chart
    const ctx2 = document.getElementById('reservationsChart').getContext('2d');
    const reservationsChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Pending', 'Cancelled'],
            datasets: [{
                label: 'Reservations',
                data: [26, 12, 2], // Replace with your actual data
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Reservations Overview'
                }
            }
        }
    });
</script>

</body>
</html>
