<!-- File: edit_service.php-->
<!DOCTYPE html>
<html lang="en">
 
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Services</title>

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

  <!-- Additional Styles -->
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      text-align: left;
      padding: 8px;
    }

    tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    th {
      background-color: #275437;
      color: white;
    }

    .pagination {
      display: inline-block;
      margin-top: 10px;
    }

    .pagination a {
      color: black;
      float: left;
      padding: 8px 16px;
      text-decoration: none;
      border: 1px solid #ddd;
      margin: 4px 2px;
    }

    .pagination a.active {
      background-color: #275437;
      color: white;
      border: 1px solid #275437;
    }

    .pagination a:hover:not(.active) {
      background-color: #ddd;
    }

    .actions {
      display: flex;
      justify-content: space-around;
    }

    .actions button {
      border: none;
      background-color: transparent;
      padding: 10px;
      cursor: pointer;
    }

    .edit-icon {
      color: #f44336;
    }

    .delete-icon {
      color: #2196F3;
    }
  </style>
</head>
<body>

  <div class="wrapper d-flex align-items-stretch">
    <?php include 'admin/includes/sidebar.php'; ?>

     <!-- Page Content -->
     <div id="content" class="p-4 p-md-5 pt-5">
      <h2 class="mb-4">Edit Services</h2>
      <p>This page will allow you to edit services.</p>

      <!-- Image Upload Form -->
      <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Service Title</label>
        <input type="text" name="title" id="title" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="image">Upload Image</label>
        <input type="file" name="image" id="image" class="form-control-file" required>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Upload</button>
</form>


      <!-- Services Table -->
      <?php
      include 'db_connect.php';

      // Define the number of records per page
      $records_per_page = 10; // Adjust as needed

      // Get the current page number from the URL, default to 1 if not set
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      $page = $page > 0 ? $page : 1;

      // Calculate the starting record for the current page
      $start_from = ($page - 1) * $records_per_page;

      // Fetch the total number of records
      $total_records_query = "SELECT COUNT(*) AS total FROM services";
      $result = $conn->query($total_records_query);
      $row = $result->fetch_assoc();
      $total_records = $row['total'];

      // Calculate total pages
      $total_pages = ceil($total_records / $records_per_page);

      // Fetch the records for the current page
      $sql = "SELECT * FROM services LIMIT $start_from, $records_per_page";
      $result = $conn->query($sql);
      ?>

      <table style="width:100%; margin-top: 20px;">
        <tr>
            <th>S.NO</th>
            <th>TITLE</th>
            <th>STATUS</th>
            <th>MODIFIED</th>
            <th>ACTIONS</th>
        </tr>
        <?php
        $counter = $start_from + 1;
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $counter++; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo htmlspecialchars(date('d M, Y H:i:s', strtotime($row['modified_at']))); ?></td>
                <td class="actions">
                    <button class="btn btn-link edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $row['id']; ?>"><i class="edit-icon">&#9998;</i></button>
                    <button class="btn btn-link delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $row['id']; ?>"><i class="delete-icon">&#128465;</i></button>
                </td>
            </tr>
        <?php endwhile; ?>
      </table>

      <!-- Pagination -->
      <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <!-- JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
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

  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Service</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editForm" enctype="multipart/form-data">
            <div class="form-group">
              <label for="editTitle">Service Title</label>
              <input type="text" name="editTitle" id="editTitle" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="editImage">Upload New Image</label>
              <input type="file" name="editImage" id="editImage" class="form-control-file">
            </div>
            <input type="hidden" id="editServiceId" name="editServiceId">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveEditBtn">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete Service</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this service?</p>
          <input type="hidden" id="deleteServiceId">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="successMessage">
        <!-- Success message will be injected here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle form submission for file upload
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const form = document.getElementById('uploadForm');
        const formData = new FormData(form);

        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = result.message;
            new bootstrap.Modal(document.getElementById('successModal')).show();
            if (result.status === 'success') {
                setTimeout(() => window.location.reload(), 2000); // Reload after showing modal
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = "An error occurred. Please try again.";
            new bootstrap.Modal(document.getElementById('successModal')).show();
        });
    });

    // Handle Edit Button Click
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const serviceId = this.getAttribute('data-id');

            fetch('upload.php?id=' + serviceId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editTitle').value = data.title;
                    document.getElementById('editServiceId').value = serviceId;
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Handle Save Edit Button Click
    document.getElementById('saveEditBtn').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const form = document.getElementById('editForm');
        const formData = new FormData(form);
        formData.append('action', 'update');

        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = result.message;
            new bootstrap.Modal(document.getElementById('successModal')).show();
            if (result.status === 'success') {
                setTimeout(() => window.location.reload(), 2000); // Reload after showing modal
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = "An error occurred. Please try again.";
            new bootstrap.Modal(document.getElementById('successModal')).show();
        });
    });

    // Handle Delete Button Click
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const serviceId = this.getAttribute('data-id');
            document.getElementById('deleteServiceId').value = serviceId;
        });
    });

    // Handle Confirm Delete Button Click
    document.getElementById('confirmDeleteBtn').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const id = document.getElementById('deleteServiceId').value;

        fetch('upload.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=delete&id=' + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(result => {
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = result.message;
            new bootstrap.Modal(document.getElementById('successModal')).show();
            if (result.status === 'success') {
                setTimeout(() => window.location.reload(), 2000); // Reload after showing modal
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = "An error occurred. Please try again.";
            new bootstrap.Modal(document.getElementById('successModal')).show();
        });
    });
});
</script>




</body>
</html>