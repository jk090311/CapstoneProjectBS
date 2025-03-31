<?php
// === adviserRemove.php (Fixed for AJAX Requests) ===
session_start();
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

// Debugging (optional, you can remove this after testing)
file_put_contents('debug.txt', "POST data: " . print_r($_POST, true));

// Check if the adviser name is provided
if (isset($_POST['adviserFullName'])) {
    $adviserFullName = $_POST['adviserFullName'];

    $delete_query = "DELETE FROM advisers WHERE adviserFullName = ?";
    $stmt = $connection->prepare($delete_query);
    $stmt->bind_param("s", $adviserFullName);

    if ($stmt->execute()) {
        echo "Adviser successfully removed.";  // Send success message
    } else {
        echo "Failed to remove adviser.";  // Send error message
    }

    $stmt->close();
} else {
    echo "No adviser name provided.";  // Error message if no data was sent
}

$connection->close();
