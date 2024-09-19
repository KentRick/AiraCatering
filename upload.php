<?php
include 'db_connect.php';

// Fetch service data for editing
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $stmt = $conn->prepare("SELECT title, image_path FROM services WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    echo json_encode($service);
    $stmt->close();
    $conn->close();
    exit;
}

// Handle file upload and add new service
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']) && isset($_POST['title']) && !isset($_POST['action'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $image = $_FILES['image'];

    // Validate the file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($image['type'], $allowed_types)) {
        echo json_encode(["status" => "error", "message" => "Invalid file type."]);
        exit;
    }

    // Validate file size
    if ($image['size'] > 10 * 1024 * 1024) { // 2 MB limit
        echo json_encode(["status" => "error", "message" => "File is too large."]);
        exit;
    }

    // Upload the file
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $file_name = basename($image['name']);
    $file_path = $upload_dir . $file_name;

    if (move_uploaded_file($image['tmp_name'], $file_path)) {
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO services (title, image_path, status) VALUES (?, ?, 'Original')");
        $stmt->bind_param('ss', $title, $file_path);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "File uploaded and data saved successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload file."]);
    }
    exit;
}

// Handle service update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    if (isset($_POST['editServiceId'])) {
        $id = $conn->real_escape_string($_POST['editServiceId']);
        $title = $conn->real_escape_string($_POST['editTitle']);
        $image = $_FILES['editImage'];

        // Fetch the current image path
        $stmt = $conn->prepare("SELECT image_path FROM services WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $service = $result->fetch_assoc();
        $current_image_path = $service['image_path'];
        $stmt->close();

        // Update query
        $update_query = "UPDATE services SET title = ?, status = 'Modified'";
        $params = [$title];

        // Check if a new image is uploaded
        if ($image['size'] > 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($image['type'], $allowed_types)) {
                echo json_encode(["status" => "error", "message" => "Invalid file type."]);
                exit;
            }

            // Validate file size
            if ($image['size'] > 2 * 1024 * 1024) { // 2 MB limit
                echo json_encode(["status" => "error", "message" => "File is too large."]);
                exit;
            }

            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_name = basename($image['name']);
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($image['tmp_name'], $file_path)) {
                $update_query .= ", image_path = ?";
                $params[] = $file_path;

                // Delete the old image file
                if (file_exists($current_image_path)) {
                    if (!unlink($current_image_path)) {
                        echo json_encode(["status" => "error", "message" => "Failed to delete old image."]);
                        exit;
                    }
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to upload file."]);
                exit;
            }
        }

        $update_query .= " WHERE id = ?";
        $params[] = $id;

        // Prepare and execute update statement
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Service updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Missing editServiceId."]);
    }
    exit;
}

// Handle service delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    if (isset($_POST['id'])) {
        $id = $conn->real_escape_string($_POST['id']);

        // Fetch the image path for the service to delete
        $stmt = $conn->prepare("SELECT image_path FROM services WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $service = $result->fetch_assoc();
        $image_path = $service['image_path'];
        $stmt->close();

        // Delete the service record
        $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            // Delete the image file from the uploads directory
            if (file_exists($image_path)) {
                if (!unlink($image_path)) {
                    echo json_encode(["status" => "error", "message" => "Failed to delete image."]);
                    exit;
                }
            }
            echo json_encode(["status" => "success", "message" => "Service and image deleted successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Missing service ID."]);
    }
    exit;
}

$conn->close();
?>
