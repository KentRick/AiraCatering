<?php
include 'session.php';
include 'db_connect.php';

// Fetch event packages from the database
$sql = "SELECT title, description FROM event_packages";
$result = $conn->query($sql);

// Handle form submission to add event type
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event-type'])) {
    $eventType = trim($_POST['event-type']);

    // Insert event type into the database
    if (!empty($eventType)) {
        $stmt = $conn->prepare("INSERT INTO event_types (type) VALUES (?)");
        $stmt->bind_param("s", $eventType);
        if ($stmt->execute()) {
            $successMessage = "Event type added successfully.";
        } else {
            $errorMessage = "Error adding event type: " . $conn->error;
        }
        $stmt->close();
    } else {
        $errorMessage = "Please enter a valid event type.";
    }
}

// Handle delete request for event types
if (isset($_GET['delete'])) {
    $eventTypeId = $_GET['delete'];

    // Delete the event type from the database
    $stmt = $conn->prepare("DELETE FROM event_types WHERE id = ?");
    $stmt->bind_param("i", $eventTypeId);
    if ($stmt->execute()) {
        $successMessage = "Event type deleted successfully.";
    } else {
        $errorMessage = "Error deleting event type: " . $conn->error;
    }
    $stmt->close();
}

// Fetch existing event types from the database
$query = "SELECT * FROM event_types";
$eventTypesResult = $conn->query($query);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Event Types</title>

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap" rel="stylesheet" />

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>

<div class="wrapper d-flex align-items-stretch">
    <?php include 'admin/includes/sidebar.php'; ?>

    <!-- Page Content -->
    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4">Manage Event Types</h2>

        <!-- Display messages -->
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success">
                <?php echo $successMessage; ?>
            </div>
        <?php elseif (isset($errorMessage)): ?>
            <div class="alert alert-danger">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <!-- Form to add new event type -->
        <form action="" method="post">
            <div class="mb-3">
                <label for="event-type" class="form-label">Event Type:</label>
                <input type="text" id="event-type" name="event-type" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Event Type</button>
        </form>

        <!-- Table to display existing event types -->
        <h3 class="mt-4">Existing Event Types</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Event Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['type']); ?></td>
                            <td>
                                <a href="edit_event_type.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event type?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No event types found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>
</body>
</html>
