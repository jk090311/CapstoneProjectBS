<?php
$connection = mysqli_connect("sql211.infinityfree.com", "if0_40275155", "EduGuard202526", "if0_40275155_eduguarddb");

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