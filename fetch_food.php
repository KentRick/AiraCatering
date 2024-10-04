<?php
include 'db_connect.php'; // Include the database connection file

if (isset($_POST['category'])) {
    $category = $conn->real_escape_string($_POST['category']);

    // Fetch food items based on the selected category
    $sql = "SELECT id, item FROM menu WHERE category = '$category' ORDER BY item";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output the food items with edit and delete buttons
        while ($row = $result->fetch_assoc()) {
            $item = htmlspecialchars($row['item']);
            $id = $row['id'];

            echo '<div class="d-flex justify-content-between align-items-center mb-2">
                    <span>' . $item . '</span>
                    <div>
                        <button class="btn btn-link edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . $id . '" data-name="' . $item . '">
                            <i class="edit-icon">&#9998;</i> Edit
                        </button>
                        <button class="btn btn-link delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="' . $id . '">
                            <i class="delete-icon">&#128465;</i> Delete
                        </button>
                    </div>
                </div>';
        }
    } else {
        echo '<p>No food items found for this category.</p>';
    }

    $conn->close();
} else {
    echo '<p>Invalid request.</p>';
}
