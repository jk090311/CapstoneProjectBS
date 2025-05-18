<?php 
session_start();
if (isset($_SESSION['unique_id'])) { // Check if the session variable exists
    include_once "../PHP/dbconnection.php";

    $outgoing_id = mysqli_real_escape_string($conn, $_POST['outgoing_id']);
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($conn, $_POST['msg']);

    if (!empty($message)) {
        $sql = "INSERT INTO mesages (incoming_msg_id, outgoing_msg_id, msg) VALUES ('$incoming_id', '$outgoing_id', '$message')";
        if (!mysqli_query($conn, $sql)) {
            die("Error inserting message: " . mysqli_error($conn)); // Add error handling
        }
    }
} else {
    die("Session not set. Please log in again.");
}
?>