<?php
// === adviserRemove.php (UPDATED WITH DEBUGGING) ===
session_start();
$connection = mysqli_connect("localhost", "root", "", "adviser_list");

// Add debugging to see what's being received
file_put_contents('debug.txt', "POST data: " . print_r($_POST, true));

// Check if the form is submitted and adviser_id is provided
if (isset($_POST['adviserFullName'])) {
    $adviserFullName = $_POST['adviserFullName'];

    $connection = mysqli_connect("localhost", "root", "", "adviser_list");

    $delete_query = "DELETE FROM advisers WHERE adviserFullName = ?";
    $stmt = $connection->prepare($delete_query);
    $stmt->bind_param("s", $adviserFullName);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Adviser successfully removed.";
    } else {
        $_SESSION['status'] = "Failed to remove adviser.";
    }

    $stmt->close();
    $connection->close();

    header("Location: ../PHPmain/adminTeachers.php");
    exit();
}
