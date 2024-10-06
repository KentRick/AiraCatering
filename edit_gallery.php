<?php
include 'db_connect.php';

// Start session to store messages
session_start();

// Functions to manage events
function addEvent($eventName, $category, $imageFile)
{
    global $conn;
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($imageFile["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a real image
    $check = getimagesize($imageFile["tmp_name"]);
    if ($check === false) {
        $_SESSION['modal_message'] = "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        $_SESSION['modal_message'] = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($imageFile["size"] > 20971520) {
        $_SESSION['modal_message'] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $_SESSION['modal_message'] = "Sorry, only JPG, JPEG, and PNG files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($imageFile["tmp_name"], $targetFile)) {
            $sql = "INSERT INTO events (event_name, category, image_path) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $eventName, $category, $targetFile);
            if ($stmt->execute()) {
                $_SESSION['modal_message'] = "Event has been added.";
            } else {
                $_SESSION['modal_message'] = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['modal_message'] = "Sorry, there was an error uploading your file.";
        }
    }
}

function editEvent($id, $eventName, $category, $imageFile = null)
{
    global $conn;
    if ($imageFile) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($imageFile["name"]);
        move_uploaded_file($imageFile["tmp_name"], $targetFile);
        $sql = "UPDATE events SET event_name = ?, category = ?, image_path = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $eventName, $category, $targetFile, $id);
    } else {
        $sql = "UPDATE events SET event_name = ?, category = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $eventName, $category, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['modal_message'] = "Event has been updated.";
    } else {
        $_SESSION['modal_message'] = "Error: " . $stmt->error;
    }
    $stmt->close();
}

function deleteEvent($id)
{
    global $conn;
    $sql = "SELECT image_path FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagePath);
    $stmt->fetch();
    if (file_exists($imagePath)) {
        unlink($imagePath); // Delete image file
    }
    $stmt->close();

    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['modal_message'] = "Event has been deleted.";
    } else {
        $_SESSION['modal_message'] = "Error: " . $stmt->error;
    }
    $stmt->close();
}

function getAllEvents()
{
    global $conn;
    $sql = "SELECT * FROM events";
    $result = $conn->query($sql);
    return $result;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_event'])) {
        addEvent($_POST['event_name'], $_POST['category'], $_FILES['image']);
    }
    if (isset($_POST['edit_event'])) {
        editEvent($_POST['id'], $_POST['event_name'], $_POST['category'], $_FILES['image']);
    }
    if (isset($_POST['delete_event'])) {
        deleteEvent($_POST['id']);
    }
}

$events = getAllEvents();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gallery</title>

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
</head>

<body>

    <div class="wrapper d-flex align-items-stretch">
        <?php include 'admin/includes/sidebar.php'; ?>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5 pt-5">
            <h2 class="mb-4">Edit Gallery</h2>

            <!-- Add Event Form -->
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="event_name" class="form-label">Event Name</label>
                    <input type="text" name="event_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Upload Image</label>
                    <input type="file" name="image" class="form-control" required>
                </div>
                <button type="submit" name="add_event" class="btn btn-primary">Add Event</button>
            </form>

            <hr>

            <!-- Existing Events -->
            <h3>Existing Events</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($event = $events->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $event['event_name']; ?></td>
                            <td><?php echo $event['category']; ?></td>
                            <td><img src="<?php echo $event['image_path']; ?>" width="100"></td>
                            <td>
                                <!-- Edit Event Form -->
                                <form action="" method="post" enctype="multipart/form-data" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                    <input type="text" name="event_name" value="<?php echo $event['event_name']; ?>" required>
                                    <input type="text" name="category" value="<?php echo $event['category']; ?>" required>
                                    <input type="file" name="image">
                                    <button type="submit" name="edit_event" class="btn btn-warning">Edit</button>
                                </form>

                                <!-- Delete Event Form -->
                                <form action="" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                    <button type="submit" name="delete_event" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessageContent"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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

    <!-- Add this script to show modal messages -->
    <script>
        $(document).ready(function() {
            <?php if (isset($_SESSION['modal_message'])): ?>
                $('#modalMessageContent').text('<?php echo $_SESSION['modal_message']; ?>');
                $('#messageModal').modal('show');
                <?php unset($_SESSION['modal_message']); ?>
            <?php endif; ?>
        });
    </script>

</body>

</html>