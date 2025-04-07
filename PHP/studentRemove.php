<?php
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

if (isset($_POST['first_name'])) {
    $first_name = $_POST['first_name'];

    $query = "DELETE FROM student WHERE first_name = '$first_name'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        echo "Student removed successfully.";
    } else {
        echo "Failed to remove student.";
    }
}
?>