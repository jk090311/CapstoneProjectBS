<?php
include 'dbconnectionSections.php'; // Ensure this file connects to your database
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $section_id = $_POST["section_id"]; // Updated to use section_id

    // Use a prepared statement to securely delete the section
    $stmt = $conn->prepare("DELETE FROM class_section WHERE section_id = ?");
    $stmt->bind_param("i", $section_id); // Bind the section_id as an integer

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Section deleted successfully!";
        header("Location: ../PHPAdmin/AdminAddSection.php"); // Redirect to the sections page
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close(); // Close the prepared statement
}
$conn->close(); // Close the database connection
?>




