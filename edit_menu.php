<?php
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

// Handle category deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteCategory'])) {
    $categoryToDelete = $conn->real_escape_string(trim($_POST['deleteCategory']));
    $deleteSql = "DELETE FROM menu WHERE category = '$categoryToDelete'";
    if ($conn->query($deleteSql) === TRUE) {
        $message = "Category deleted successfully.";
    } else {
        $message = "Error deleting category.";
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
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/ionicons.min.css">

    <style>
        html,
        body {
            height: 100%;
            /* Ensure full height for the body */
            margin: 0;
            /* Remove default margin */
        }

        .wrapper {
            height: 100vh;
            /* Full viewport height */
            display: flex;
            /* Flex display for wrapper */
        }

        .sidebar {
            height: 100%;
            /* Full height for sidebar */
            /* Add your sidebar styling here */
        }

        .container {
            margin-top: 40px;
            flex: 1;
            /* Allow main content to fill the remaining space */
        }

        .card {
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }

        h2 {
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #343a40;
            color: #fff;
        }

        .card-body {
            background-color: #fff;
        }

        .category-list {
            max-height: 200px;
            overflow-y: auto;
        }

        .btn-primary,
        .btn-danger {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <?php include 'admin/includes/sidebar.php'; ?>

        <div class="container">
            <h2>Manage Menu Category</h2>
            <!-- Row containing Add Category and Category List -->
            <div class="row">
                <!-- Add Category Card -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Add New Food Category
                        </div>
                        <div class="card-body">
                            <form id="addMenu" action="" method="post">
                                <div class="form-group">
                                    <label for="title">Food Category</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter category name" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Category</button>
                            </form>
                            <div id="feedback"></div>
                        </div>
                    </div>
                </div>

                <!-- Categories List -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Food Categories</div>
                        <div class="card-body category-list">
                            <ul class="list-group">
                                <?php foreach ($categories as $category): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo htmlspecialchars($category); ?>
                                        <button class="btn btn-danger btn-sm delete-category" data-category="<?php echo htmlspecialchars($category); ?>">Delete</button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <h2>Manage Menu Food</h2>
            <!-- Row containing Add Food Header and Food List Header -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Add Food Item</div>
                        <div class="card-body">
                            <form id="addFoodForm" action="add_food.php" method="post">
                                <div class="form-group">
                                    <label for="foodName">Food Name</label>
                                    <input type="text" name="foodName" id="foodName" class="form-control" placeholder="Enter food name" required>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="foodCategory">Select Category</label>
                                    <select name="foodCategory" id="foodCategory" class="form-control" required>
                                        <option value="">Select a Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Add Food</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Food List -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Food List</div>
                        <div class="card-body">
                            <!-- New Category Selection for Food List -->
                            <div class="form-group">
                                <label for="foodListCategory">Select Category to View Foods</label>
                                <select name="foodListCategory" id="foodListCategory" class="form-control">
                                    <option value="">Select a Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Food Items Display Area -->
                            <div id="food-list">Select a category to view the food items.</div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Food Item</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editFoodForm" action="edit_food.php" method="post">
                                    <input type="hidden" name="foodId" id="editFoodId">
                                    <div class="mb-3">
                                        <label for="editFoodName" class="form-label">Food Name</label>
                                        <input type="text" class="form-control" id="editFoodName" name="foodName" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update Food Item</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Delete Food Item</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this food item?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
        $(document).ready(function() {
            // Delete Category Event
            $('.delete-category').on('click', function() {
                var category = $(this).data('category');
                if (confirm('Are you sure you want to delete the category "' + category + '"?')) {
                    $.ajax({
                        url: 'edit_menu.php',
                        type: 'POST',
                        data: {
                            deleteCategory: category
                        },
                        success: function(response) {
                            alert('Category deleted successfully.');
                            location.reload();
                        },
                        error: function() {
                            alert('Error deleting category.');
                        }
                    });
                }
            });

            // Load Food Items Function
            function loadFoodItems(category) {
                $.ajax({
                    url: 'fetch_food.php',
                    type: 'POST',
                    data: {
                        category: category
                    },
                    success: function(response) {
                        $('#food-list').html(response);
                    },
                    error: function() {
                        $('#food-list').html('<p>Error loading food items.</p>');
                    }
                });
            }

            // Load Food Items on Category Selection for "View Food"
            $('#foodListCategory').on('change', function() {
                var selectedCategory = $(this).val();
                if (selectedCategory) {
                    loadFoodItems(selectedCategory);
                } else {
                    $('#food-list').html('Select a category to view the food items.');
                }
            });

            // The Add Food Item dropdown does not load food items
            $('#foodCategory').on('change', function() {
                // This is intentionally left empty or could show a message indicating that
                // no food items will be loaded from this dropdown
            });

            // Edit Food Item Event
            $('#food-list').on('click', '.edit-btn', function() {
                var foodId = $(this).data('id');
                var foodName = $(this).data('name');

                $('#editFoodId').val(foodId);
                $('#editFoodName').val(foodName);
            });

            // Delete Food Item Event
            $('#food-list').on('click', '.delete-btn', function() {
                var foodId = $(this).data('id');
                $('#confirmDeleteBtn').data('id', foodId);
            });

            // Confirm Delete
            $('#confirmDeleteBtn').on('click', function() {
                var foodId = $(this).data('id');
                $.ajax({
                    url: 'delete_food.php',
                    type: 'POST',
                    data: {
                        ids: [foodId] // Wrap in an array to match your existing delete structure
                    },
                    success: function(response) {
                        alert('Food item deleted successfully.');
                        location.reload();
                    },
                    error: function() {
                        alert('Error deleting food item.');
                    }
                });
            });
        });
    </script>
</body>

</html>