<?php
include 'dbconnectionSections.php'; // Ensure this file connects to your database
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $section_id = $_POST["section_id"]; // Updated to use section_id

    // Delete the section from the database
    $sql = "DELETE FROM class_sections WHERE id='$section_id'"; // Updated table name

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Section deleted successfully!";
        header("Location: ../PHPmain/AdminAddSection.php"); // Updated redirect location
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>