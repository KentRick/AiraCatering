<?php
include 'db_connect.php'; // Ensure you have the db_connect.php for connection

// Handle category add, update, delete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $sql = "INSERT INTO event_categories (category_name) VALUES ('$category_name')";
    $conn->query($sql);
  } elseif (isset($_POST['edit_category'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    $sql = "UPDATE event_categories SET category_name='$category_name' WHERE id='$category_id'";
    $conn->query($sql);
  } elseif (isset($_POST['delete_category'])) {
    $category_id = $_POST['category_id'];

    // Delete related packages first
    $sql_delete_packages = "DELETE FROM event_packages WHERE category_id='$category_id'";
    $conn->query($sql_delete_packages);

    // Now delete the category
    $sql = "DELETE FROM event_categories WHERE id='$category_id'";
    $conn->query($sql);
  }
}

// Handle package add, update, delete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['add_package'])) {
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $pax = $_POST['pax'];
    $description = $_POST['description'];

    // File upload handling
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
      $sql = "INSERT INTO event_packages (category_id, title, pax, description, image) VALUES ('$category_id', '$title', '$pax', '$description', '$image')";
      $conn->query($sql);
    }
  } elseif (isset($_POST['edit_package'])) {
    $package_id = $_POST['package_id'];
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $pax = $_POST['pax'];
    $description = $_POST['description'];

    // Handle image update if uploaded
    if ($_FILES['image']['name']) {
      $image = $_FILES['image']['name'];
      $target = "uploads/" . basename($image);

      if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "UPDATE event_packages SET category_id='$category_id', title='$title', description='$description', image='$image' WHERE id='$package_id'";
        $conn->query($sql);
      }
    } else {
      $sql = "UPDATE event_packages SET category_id='$category_id', title='$title', description='$description' WHERE id='$package_id'";
      $conn->query($sql);
    }
  } elseif (isset($_POST['delete_package'])) {
    $package_id = $_POST['package_id'];
    $sql = "DELETE FROM event_packages WHERE id='$package_id'";
    $conn->query($sql);
  }
}

// Fetch all categories and packages
$sql_categories = "SELECT * FROM event_categories";
$categories = $conn->query($sql_categories);

$sql_packages = "SELECT p.*, c.category_name FROM event_packages p JOIN event_categories c ON p.category_id = c.id";
$packages = $conn->query($sql_packages);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Packages</title>

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
    .table th {
      background-color: #f8f9fa;
    }

    .table td {
      vertical-align: middle;
    }

    .form-control {
      margin-bottom: 10px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .btn {
      margin-right: 5px;
    }

    .mb-4 {
      margin-bottom: 30px;
    }

    .column-segment {
      border: 1px solid #ced4da;
      border-radius: 5px;
      padding: 20px;
      margin-bottom: 20px;
      background-color: #f8f9fa;
    }

    .row {
      margin-bottom: 20px;
    }

    .card-header {
      background-color: #343a40;
      color: #fff;

    }

    h4 {
      font-family: 'Open Sans', sans-serif;
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.5;
      color: #fff;
    }
  </style>
</head>

<body>

  <div class="wrapper d-flex align-items-stretch">
    <?php include 'admin/includes/sidebar.php'; ?>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5 pt-5">
      <h2 class="mb-4">Edit Packages</h2>
      <div class="row">
        <!-- Column 1: Categories -->
        <div class="col-md-6">
          <div class="card column-segment">
            <div class="card-header">
              <h4>Categories</h4>
            </div>
            <div class="card-body">
              <form method="post">
                <input type="hidden" name="category_id" id="category_id">
                <div class="form-group">
                  <input type="text" name="category_name" id="category_name" class="form-control" placeholder="Enter Category Name" required>
                </div>
                <div class="form-group">
                  <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                </div>
              </form>

              <h5>Existing Categories:</h5>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Category Name</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($category = $categories->fetch_assoc()): ?>
                    <tr>
                      <td><?php echo $category['category_name']; ?></td>
                      <td>
                        <form method="post" style="display:inline-block">
                          <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                          <button type="button" class="btn btn-danger" onclick="openDeleteConfirmationModal('<?php echo $category['id']; ?>')">Delete</button>
                        </form>
                        <button type="button" class="btn btn-warning" onclick="openEditCategoryModal('<?php echo $category['id']; ?>', '<?php echo $category['category_name']; ?>')">Edit</button>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- Edit Category Modal -->
        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form method="post">
                  <input type="hidden" name="category_id" id="modal_category_id">
                  <div class="form-group mb-3">
                    <label for="modal_category_name">Category Name</label>
                    <input type="text" name="category_name" id="modal_category_name" class="form-control" placeholder="Enter Category Name" required>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="edit_category" class="btn btn-warning">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
                    <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this category? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <form method="post" id="deleteCategoryForm">
          <input type="hidden" name="category_id" id="modal_delete_category_id">
          <button type="submit" name="delete_category" class="btn btn-danger">Delete</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

        <!-- Column 2: Packages -->
<div class="col-md-6">
  <div class="card column-segment">
    <div class="card-header">
      <h4>Packages</h4>
    </div>
    <div class="card-body">
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="package_id" id="package_id">
        <div class="form-group">
          <label for="category_id">Select Category</label>
          <select name="category_id" id="category_id" class="form-select" required>
            <?php
            $categories = $conn->query($sql_categories);
            while ($category = $categories->fetch_assoc()): ?>
              <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <input type="text" name="title" id="title" class="form-control" placeholder="Enter Package Title" required>
        </div>
        <div class="form-group">
          <input type="text" name="pax" id="pax" class="form-control" placeholder="Enter Package PAX" required>
        </div>
        <div class="form-group">
          <textarea name="description" id="description" class="form-control" placeholder="Enter Package Description" required></textarea>
        </div>
        
        <div class="form-group">
          <input type="file" name="image" class="form-control">
        </div>
        <div class="form-group">
          <button type="submit" name="add_package" class="btn btn-primary">Add Package</button>
        </div>
      </form>

      <h5>Existing Packages:</h5>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Package Title</th>
            <th>Category</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($package = $packages->fetch_assoc()): ?>
            <tr>
              <td><?php echo $package['title']; ?></td>
              <td><?php echo $package['category_name']; ?></td>
              <td>
                <form method="post" style="display:inline-block">
                  <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
                  <button type="button" class="btn btn-danger" onclick="openDeletePackageConfirmationModal('<?php echo $package['id']; ?>')">Delete</button>
                </form>
                <button type="button" class="btn btn-warning" onclick="openEditPackageModal('<?php echo $package['id']; ?>', '<?php echo $package['title']; ?>', '<?php echo $package['description']; ?>', '<?php echo $package['category_id']; ?>')">Edit</button>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
  <!-- Edit Package Modal -->
  <div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPackageModalLabel">Edit Package</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="package_id" id="modal_package_id">
            <div class="form-group mb-3">
              <label for="modal_category_id">Select Category</label>
              <select name="category_id" id="modal_category_id" class="form-select" required>
                <?php
                $categories = $conn->query($sql_categories);
                while ($category = $categories->fetch_assoc()): ?>
                  <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group mb-3">
              <label for="modal_title">Package Title</label>
              <input type="text" name="title" id="modal_title" class="form-control" placeholder="Enter Package Title" required>
            </div>
            <div class="form-group mb-3">
              <label for="modal_description">Package Description</label>
              <textarea name="description" id="modal_description" class="form-control" placeholder="Enter Package Description" required></textarea>
            </div>
            <div class="form-group mb-3">
              <label for="modal_image">Package Image (optional)</label>
              <input type="file" name="image" id="modal_image" class="form-control">
            </div>
            <div class="modal-footer">
              <button type="submit" name="edit_package" class="btn btn-warning">Save Changes</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal for Packages -->
<div class="modal fade" id="deletePackageConfirmationModal" tabindex="-1" aria-labelledby="deletePackageConfirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletePackageConfirmationModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this package? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <form method="post" id="deletePackageForm">
          <input type="hidden" name="package_id" id="modal_delete_package_id">
          <button type="submit" name="delete_package" class="btn btn-danger">Delete</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

  <script>
    function populateEditCategory(id, name) {
      document.getElementById('category_id').value = id;
      document.getElementById('category_name').value = name;
    }

    function populateEditPackage(id, title, description, category_id) {
      document.getElementById('package_id').value = id;
      document.getElementById('title').value = title;
      document.getElementById('description').value = description;
      document.getElementById('category_id').value = category_id;
    }

    //modal
    function openEditCategoryModal(id, name) {
      document.getElementById('modal_category_id').value = id;
      document.getElementById('modal_category_name').value = name;
      // Show the modal
      var editCategoryModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
      editCategoryModal.show();
    }

    function openEditPackageModal(id, title, description, categoryId) {
      // Populate modal fields with package details
      document.getElementById('modal_package_id').value = id;
      document.getElementById('modal_title').value = title;
      document.getElementById('modal_description').value = description;
      document.getElementById('modal_category_id').value = categoryId;

      // Show the modal
      var editPackageModal = new bootstrap.Modal(document.getElementById('editPackageModal'));
      editPackageModal.show();
    }

    function openDeleteConfirmationModal(categoryId) {
    document.getElementById('modal_delete_category_id').value = categoryId;
    // Show the modal
    var deleteConfirmationModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    deleteConfirmationModal.show();
  }

  function openDeletePackageConfirmationModal(packageId) {
    document.getElementById('modal_delete_package_id').value = packageId;
    // Show the modal
    var deletePackageConfirmationModal = new bootstrap.Modal(document.getElementById('deletePackageConfirmationModal'));
    deletePackageConfirmationModal.show();
  }

  </script>

  <!-- JavaScript Libraries -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/waypoints/waypoints.min.js"></script>
  <script src="lib/counterup/counterup.min.js"></script>
  <script src="lib/lightbox/js/lightbox.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>

  <!-- Template Javascript -->
  <script src="js/main.js"></script>
</body>

</html>