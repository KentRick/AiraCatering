<?php
// File: services.php

// Fetch services from the database
include 'db_connect.php';

$sql = "SELECT * FROM services";
$result = $conn->query($sql);
?>

<style>
  /* Ensure the modal is centered in the viewport */
  .modal-dialog {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Full viewport height */
  }

  /* Center the image within the modal body */
  .modal-body {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 0; /* Remove default padding */
  }

  .modal-body img {
    max-width: 100%;
    max-height: 80vh; /* Adjust as needed */
    height: auto; /* Maintain aspect ratio */
  }

  /* Optional: Adjust the modal size if needed */
  .modal-lg {
    max-width: 90%; /* Adjust modal width */
  }

</style>


<div class="container Services" style="padding-bottom: 180px;">
    <h1 class="text-primary fw-bold mb-5 text-center" id="Event-Packages-Name" style="text-decoration: underline; text-decoration-color:black;">
        Catering<span class="text-dark">Services</span>
    </h1>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col">
                <div class="hovereffect" style="width: auto; height: 50vh;">
                    <img class="img-fluid" src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    <div class="overlay">
                        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                        <a class="info" href="#">View</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Added 'modal-lg' class for a larger modal -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="modalImage" src="" class="img-fluid" alt="Selected Image">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Get all "View" links
    const viewLinks = document.querySelectorAll('.info');

    viewLinks.forEach(link => {
      link.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior

        // Get the image source from the closest image element
        const imgElement = this.closest('.hovereffect').querySelector('img');
        const imgSrc = imgElement.src;

        // Set the src of the modal image
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imgSrc;

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
      });
    });
  });
</script>

<?php $conn->close(); ?>
