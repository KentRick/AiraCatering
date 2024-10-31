<?php
include 'db_connect.php'; // Include database connection

// Handle addon add, update, delete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Event Packages Addons
    if (isset($_POST['add_addon'])) {
        $addon_name = $_POST['addon_name'];
        $addon_price = $_POST['addon_price'];
        $package_id = $_POST['package_id']; // Ensure you retrieve package ID
        $stmt = $conn->prepare("INSERT INTO packages_addons (addon_name, addon_price, package_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $addon_name, $addon_price, $package_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['edit_addon'])) {
        $addon_id = $_POST['addon_id'];
        $addon_name = $_POST['addon_name'];
        $addon_price = $_POST['addon_price'];
        $package_id = $_POST['package_id']; // Ensure you retrieve package ID
        $stmt = $conn->prepare("UPDATE packages_addons SET addon_name=?, addon_price=?, package_id=? WHERE id=?");
        $stmt->bind_param("sdii", $addon_name, $addon_price, $package_id, $addon_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_addon'])) {
        $addon_id = $_POST['addon_id'];
        $stmt = $conn->prepare("DELETE FROM packages_addons WHERE id=?");
        $stmt->bind_param("i", $addon_id);
        $stmt->execute();
        $stmt->close();
    }

    // Service Packages Addons
    if (isset($_POST['add_service_addon'])) {
        $service_addon_name = $_POST['service_addon_name'];
        $service_addon_price = $_POST['service_addon_price'];
        $service_id = $_POST['service_id']; // Retrieve service ID from the form
        $stmt = $conn->prepare("INSERT INTO service_packages_addons (addon_name, addon_price, service_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $service_addon_name, $service_addon_price, $service_id);
        $stmt->execute();
        $stmt->close();
        
    } elseif (isset($_POST['edit_service_addon'])) {
        $service_addon_id = $_POST['service_addon_id'];
        $service_addon_name = $_POST['service_addon_name'];
        $service_addon_price = $_POST['service_addon_price'];
        $stmt = $conn->prepare("UPDATE service_packages_addons SET addon_name=?, addon_price=? WHERE id=?");
        $stmt->bind_param("sdi", $service_addon_name, $service_addon_price, $service_addon_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_service_addon'])) {
        $service_addon_id = $_POST['service_addon_id'];
        $stmt = $conn->prepare("DELETE FROM service_packages_addons WHERE id=?");
        $stmt->bind_param("i", $service_addon_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all addons with package titles
$sql_addons = "
    SELECT a.id, a.addon_name, a.addon_price, p.title AS package_title 
    FROM packages_addons a 
    JOIN event_packages p ON a.package_id = p.id
";
$addons = $conn->query($sql_addons);

// Fetch all service addons
$sql_service_addons = "SELECT * FROM service_packages_addons";
$service_addons = $conn->query($sql_service_addons);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Addons</title>

  <!-- Google Web Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap" rel="stylesheet">

  <!-- Bootstrap and FontAwesome -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">

  <!-- Custom Stylesheet -->
  <link href="css/styles.css" rel="stylesheet">

<style>
</style>

</head>
<body>

<div class="wrapper d-flex align-items-stretch">
  <?php include 'admin/includes/sidebar.php'; ?>

<!-- Main Content -->
<div id="content" class="p-4 p-md-5 pt-5">
  <div class="container mt-5">
    <h2 class="text-center mb-4">Manage Addons</h2>

    <!-- Addon Form -->
    <div class="card mb-4">
      <div class="card-header">
        <h5>Add New Event Packages Addons</h5>
      </div>
      <div class="card-body">
        <form method="post" class="row g-3">
          <div class="col-md-6">
            <select name="package_id" class="form-control" required>
              <option value="">Select Event Package</option>
              <?php
              // Assuming you have a database connection already established
              $query = "SELECT id, title FROM event_packages"; // Adjust this query based on your database structure
              $result = mysqli_query($conn, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-6">
            <input type="text" name="addon_name" class="form-control" placeholder="Enter Addon Name" required>
          </div>
          <div class="col-md-4">
            <input type="number" name="addon_price" class="form-control" placeholder="Enter Addon Price" step="0.01" required>
          </div>
          <div class="col-md-2">
            <button type="submit" name="add_addon" class="btn btn-success w-100">Add Addon</button>
          </div>
        </form>
      </div>
    </div>

<!-- Existing Addons Table -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Existing Addons</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Addon Name</th>
                    <th>Addon Price</th>
                    <th>Package</th> <!-- New column for Package -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($addon = $addons->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($addon['addon_name']); ?></td>
                        <td>₱<?php echo number_format($addon['addon_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($addon['package_title']); ?></td> <!-- Displaying package title -->
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="openEditAddonModal('<?php echo $addon['id']; ?>', '<?php echo htmlspecialchars($addon['addon_name']); ?>', '<?php echo $addon['addon_price']; ?>')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="openDeleteConfirmationModal('<?php echo $addon['id']; ?>')">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>


      <!-- Service Addon Form -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Add New Service Packages Addons</h5>
    </div>
    <div class="card-body">
        <form method="post" class="row g-3">
            <div class="col-md-6">
                <select name="service_id" class="form-control" required>
                    <option value="">Select Service</option>
                    <?php
                    // Fetch services from the database
                    $query_services = "SELECT id, title FROM services"; // Adjust query based on your services table structure
                    $result_services = mysqli_query($conn, $query_services);
                    while ($row = mysqli_fetch_assoc($result_services)) {
                        echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['title']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="service_addon_name" class="form-control" placeholder="Enter Addon Name" required>
            </div>
            <div class="col-md-4">
                <input type="number" name="service_addon_price" class="form-control" placeholder="Enter Addon Price" step="0.01" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="add_service_addon" class="btn btn-success w-100">Add Addon</button>
            </div>
        </form>
    </div>
</div>


<!-- Edit Addon Modal -->
<div class="modal fade" id="editAddonModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Addon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post">
        <div class="modal-body">
          <input type="hidden" name="addon_id" id="edit-addon-id">
          <div class="form-group">
            <label for="edit-addon-name">Addon Name</label>
            <input type="text" class="form-control" id="edit-addon-name" name="addon_name" required>
          </div>
          <div class="form-group">
            <label for="edit-addon-price">Addon Price</label>
            <input type="number" class="form-control" id="edit-addon-price" name="addon_price" step="0.01" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="edit_addon" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Existing Service Addons Table -->
<div class="card">
    <div class="card-header">
        <h5>Existing Service Addons</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Addon Name</th>
                    <th>Addon Price</th>
                    <th>Service</th> <!-- New column for Service -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Update the SQL to fetch service titles
                $sql_service_addons = "
                    SELECT a.id, a.addon_name, a.addon_price, s.title AS service_title 
                    FROM service_packages_addons a 
                    JOIN services s ON a.service_id = s.id
                ";
                $service_addons = $conn->query($sql_service_addons);
                
                while ($service_addon = $service_addons->fetch_assoc()):
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($service_addon['addon_name']); ?></td>
                        <td>₱<?php echo number_format($service_addon['addon_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($service_addon['service_title']); ?></td> <!-- Displaying Service Title -->
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="openEditServiceAddonModal('<?php echo $service_addon['id']; ?>', '<?php echo htmlspecialchars($service_addon['addon_name']); ?>', '<?php echo $service_addon['addon_price']; ?>')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="openDeleteServiceConfirmationModal('<?php echo $service_addon['id']; ?>')">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Service Addon Modal -->
<div class="modal fade" id="editServiceAddonModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Service Addon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post">
        <div class="modal-body">
          <input type="hidden" name="service_addon_id" id="edit-service-addon-id">
          <div class="form-group">
            <label for="edit-service-addon-name">Addon Name</label>
            <input type="text" class="form-control" id="edit-service-addon-name" name="service_addon_name" required>
          </div>
          <div class="form-group">
            <label for="edit-service-addon-price">Addon Price</label>
            <input type="number" class="form-control" id="edit-service-addon-price" name="service_addon_price" step="0.01" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="edit_service_addon" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Addon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this addon?</p>
      </div>
      <form method="post">
        <input type="hidden" name="addon_id" id="delete-addon-id">
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="delete_addon" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Service Confirmation Modal -->
<div class="modal fade" id="deleteServiceConfirmationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Service Addon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this service addon?</p>
      </div>
      <form method="post">
        <input type="hidden" name="service_addon_id" id="delete-service-addon-id">
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="delete_service_addon" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function openEditAddonModal(id, name, price) {
    document.getElementById('edit-addon-id').value = id;
    document.getElementById('edit-addon-name').value = name;
    document.getElementById('edit-addon-price').value = price;
    var myModal = new bootstrap.Modal(document.getElementById('editAddonModal'), {});
    myModal.show();
  }

  function openDeleteConfirmationModal(id) {
    document.getElementById('delete-addon-id').value = id;
    var myModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'), {});
    myModal.show();
  }

  function openEditServiceAddonModal(id, name, price) {
    document.getElementById('edit-service-addon-id').value = id;
    document.getElementById('edit-service-addon-name').value = name;
    document.getElementById('edit-service-addon-price').value = price;
    var myModal = new bootstrap.Modal(document.getElementById('editServiceAddonModal'), {});
    myModal.show();
  }

  function openDeleteServiceConfirmationModal(id) {
    document.getElementById('delete-service-addon-id').value = id;
    var myModal = new bootstrap.Modal(document.getElementById('deleteServiceConfirmationModal'), {});
    myModal.show();
  }
</script>

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
</body>
</html>
