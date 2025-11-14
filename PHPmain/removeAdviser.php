<?php
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

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





