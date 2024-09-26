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

    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
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
            padding-top: 20px;
        }
        form#addMenu {
            margin-bottom: 20px;
        }
        #food-list {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper d-flex align-items-stretch">

        <?php include 'admin/includes/sidebar.php'; ?>

        <div id="content" class="p-4 p-md-5 pt-5">
            <h2 class="mb-4">Edit Menu</h2>
            <p>This page will allow you to edit Menu.</p>
            <!-- Add Menu Category -->
            <form id="addMenu" action="" method="post">
                <div class="form-group">
                    <label for="title">Food Category</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add Category</button>
            </form>
            <!-- Add Food -->
            <form id="addFoodForm" action="add_food.php" method="post">
                <div class="form-group">
                    <label for="foodName">Food Name</label>
                    <input type="text" name="foodName" id="foodName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="foodCategory">Select Category</label>
                    <select name="foodCategory" id="foodCategory" class="form-control">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add Food</button>
            </form>
            <!-- Dropdown for Category Selection -->
            <div id="menu-container" class="mb-4">
                <div class="dropdown d-inline-block">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Menu Category
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a class="dropdown-item" href="#" data-category="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="mt-3" style="padding-bottom: 15px;">
                    <button id="deleteCategoryButton" class="btn btn-danger">Delete Selected Category</button>
                </div>
            </div>
            <div id="food-list"></div>
            <div class="mt-3">
                <button id="editSelectedButton" class="btn btn-warning">Edit Selected Food</button>
                <button id="deleteSelectedButton" class="btn btn-danger">Delete Selected Food</button>
            </div>
        </div>
    </div>


       <!-- JavaScript Libraries -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            const dropdownItems = $('.dropdown-item');
            const dropdownButton = $('#dropdownMenuButton');
            let selectedCategory = '';

            dropdownItems.on('click', function(event) {
                event.preventDefault();
                selectedCategory = $(this).data('category');
                dropdownButton.text(selectedCategory);
                loadFoodItems(selectedCategory);
            });

            function loadFoodItems(category) {
                $.ajax({
                    url: 'fetch_food.php',
                    type: 'POST',
                    data: { category: category },
                    success: function(response) {
                        $('#food-list').html(response);
                    },
                    error: function() {
                        $('#food-list').html('<p>Error loading food items.</p>');
                    }
                });
            }

            $('#deleteCategoryButton').on('click', function() {
                if (!selectedCategory) {
                    alert("Please select a category to delete.");
                    return;
                }

                if (confirm("Are you sure you want to delete the category '" + selectedCategory + "'?")) {
                    $.ajax({
                        url: 'edit_menu.php',
                        type: 'POST',
                        data: { deleteCategory: selectedCategory },
                        success: function(response) {
                            alert("Category deleted successfully.");
                            location.reload(); // Reload to see updated categories
                        },
                        error: function() {
                            alert('Error deleting category.');
                        }
                    });
                }
            });

            $('#editSelectedButton').on('click', function() {
                const selectedItems = $("input[type='checkbox']:checked");
                if (selectedItems.length !== 1) {
                    alert("Please select exactly one food item to edit.");
                    return;
                }

                const selectedItem = selectedItems.first();
                const foodId = selectedItem.attr('id').split('-')[1]; // Assuming id format is 'item-<id>'
                const foodName = selectedItem.val();

                // Populate the modal or form to edit food
                $('#editFoodModal').modal('show');
                $('#foodId').val(foodId);
                $('#editFoodName').val(foodName);
            });

            $('#deleteSelectedButton').on('click', function() {
                const selectedItems = $("input[type='checkbox']:checked");
                if (selectedItems.length === 0) {
                    alert("Please select at least one food item to delete.");
                    return;
                }

                const foodIds = selectedItems.map(function() {
                    return $(this).attr('id').split('-')[1];
                }).get();

                if (confirm("Are you sure you want to delete the selected food items?")) {
                    $.ajax({
                        url: 'delete_food.php',
                        type: 'POST',
                        data: { ids: foodIds },
                        success: function(response) {
                            alert("Food items deleted successfully.");
                            loadFoodItems(selectedCategory); // Refresh the food items
                        },
                        error: function() {
                            alert('Error deleting food items.');
                        }
                    });
                }
            });
        });
    </script>

    <!-- Edit Food Modal -->
    <div class="modal fade" id="editFoodModal" tabindex="-1" aria-labelledby="editFoodModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFoodModalLabel">Edit Food Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editFoodForm" action="edit_food.php" method="post">
                        <input type="hidden" name="foodId" id="foodId">
                        <div class="mb-3">
                            <label for="editFoodName" class="form-label">Food Name</label>
                            <input type="text" class="form-control" name="foodName" id="editFoodName" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


      <!-- Template Javascript -->
      <script src="js/main.js"></script>
</body>
</html>
