<?php
$connection = mysqli_connect("localhost", "root", "", "attendance_tracking");

if (isset($_POST['first_name'])) {
    $first_name = $_POST['first_name'];

    $query = "DELETE FROM students WHERE first_name = '$first_name'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        echo "Student removed successfully.";
    } else {
        echo "Failed to remove student.";
    }
}
?>