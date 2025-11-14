<?php
include 'dbconnectionSubjects.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id = $_POST["subject_id"];

    // Delete the subject from the database
    $sql = "DELETE FROM subjects WHERE subject_id='$subject_id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Subject deleted successfully!";
        header("Location:../PHPAdviser/adviserSubject.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>




