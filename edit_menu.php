<?php
// File: edit_menu.php
include 'db_connect.php'; // Include the database connection file

// Initialize the message variable
$message = "";

// Handle form submission for adding a new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = trim($_POST['title']);
    
    if (!empty($title)) {
        $title = $conn->real_escape_string($title);
        
        // Insert the new category into the database
        $insertSql = "INSERT INTO menu (category) VALUES ('$title')";
        if ($conn->query($insertSql) === TRUE) {
            $message = "success"; // Set message status to success
        } else {
            $message = "error"; // Set message status to error
        }
        
        // Redirect to avoid form resubmission
        header('Location: edit_menu.php');
        exit();
    } else {
        $message = "error"; // Set message status to error
    }
}

// Fetch distinct categories from the menu table
$sql = "SELECT DISTINCT category FROM menu ORDER BY category";
$result = $conn->query($sql);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>

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

    <!-- Additional CSS -->
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }

        #menu-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .dropdown {
            margin-right: 20px;
        }

        form#addMenu {
            margin-bottom: 20px;
        }

        .dropdown-toggle {
            font-size: 1.25rem;
            padding: 8px 15px;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dropdown-toggle::after {
            margin-left: 20px;
            font-size: 1.25rem;
        }

        .dropdown-menu {
            min-width: 200px;
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .dropdown-menu a {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
        }

        .dropdown-menu a:last-child {
            border-bottom: none;
        }

        .dropdown-menu a:hover {
            background-color: #f8f9fa;
            color: #007bff;
        }

        .menu-text-info {
            font-size: 20px;
            color: black;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Remove alert styling */
        .custom-alert {
            display: none; /* Ensure it's hidden by default */
        }

        @media (max-width: 768px) {
            #menu-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .dropdown-menu {
                min-width: 100%;
            }
        }

        #food-list {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <?php include 'admin/includes/sidebar.php'; ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5 pt-5">
            <h2 class="mb-4">Edit Menu</h2>
            <p>This page will allow you to edit Menu.</p>

            <div><!-- Add Menu Category -->
                <form id="addMenu" action="" method="post">
                    <div class="form-group">
                        <label for="title">Food Category</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Add Category</button>
                </form>
            </div>

            <!-- Container for Dropdown and Text -->
            <div id="menu-container" class="mb-4">
                <!-- Dropdown Menu -->
                <div class="dropdown d-inline-block">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Menu List
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php foreach ($categories as $category): ?>
                            <li><a class="dropdown-item" href="#" data-category="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Add Food Button -->
                <div class="d-inline-block ml-3">
                    <button class="btn btn-success" id="addFoodButton">Add Food</button>
                </div>
            </div>

            <!-- Container for Food List -->
            <div id="food-list">
                <!-- Food items will be displayed here -->
            </div>

            <!-- Temporary Alert Container (hidden) -->
            <div id="alertContainer" class="custom-alert"></div>
        </div>

        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="lib/wow/wow.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/counterup/counterup.min.js"></script>
        <script src="lib/lightbox/js/lightbox.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dropdownItems = document.querySelectorAll('.dropdown-item');
                const dropdownButton = document.getElementById('dropdownMenuButton');
                const foodList = document.getElementById('food-list');
                const alertContainer = document.getElementById('alertContainer');

                // Remove alert handling as it's now hidden
                alertContainer.style.display = 'none';

                dropdownItems.forEach(item => {
                    item.addEventListener('click', function(event) {
                        event.preventDefault();
                        const category = this.getAttribute('data-category');
                        dropdownButton.textContent = category;

                        // AJAX request to fetch food items for the selected category
                        $.ajax({
                            url: 'fetch_food.php', // PHP file that will handle the request
                            type: 'POST',
                            data: {
                                category: category
                            },
                            success: function(response) {
                                foodList.innerHTML = response;
                            },
                            error: function() {
                                foodList.innerHTML = '<p>Error loading food items.</p>';
                            }
                        });
                    });
                });
            });
        </script>
</body>

</html>
