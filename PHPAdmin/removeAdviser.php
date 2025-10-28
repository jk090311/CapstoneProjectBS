<?php
$connection = mysqli_connect("sql211.infinityfree.com", "if0_40275155", "EduGuard202526", "if0_40275155_eduguarddb");

if (isset($_POST['adviserFullName'])) {
    $adviserFullName = $_POST['adviserFullName'];

    $query = "DELETE FROM advisers WHERE adviserFullName = '$adviserFullName'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        echo "Adviser removed successfully.";
    } else {
        echo "Failed to remove adviser.";
    }
}
?>