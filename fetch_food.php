<?php
include 'db_connect.php'; // Include the database connection file

if (isset($_POST['category'])) {
    $category = $conn->real_escape_string($_POST['category']);

    // Fetch food items based on the selected category
    $sql = "SELECT id, item FROM menu WHERE category = '$category' ORDER BY item";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output the food items as checkboxes
        while ($row = $result->fetch_assoc()) {
            $item = htmlspecialchars($row['item']);
            $id = $row['id'];

            echo '<div class="form-check">
                    <input class="form-check-input food-checkbox" type="checkbox" id="item-' . $id . '" value="' . $id . '">
                    <label class="form-check-label" for="item-' . $id . '">' . $item . '</label>
                  </div>';
        }
    } else {
        echo '<p>No food items found for this category.</p>';
    }

    $conn->close();
} else {
    echo '<p>Invalid request.</p>';
}
