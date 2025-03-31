<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if(isset($_POST['adviserFullName']) && $_POST['action_type'] === 'edit') {
    // Get form data
    $originalAdviserName = mysqli_real_escape_string($connection, $_POST['originalAdviserName']);  // The original name used to find the row
    $adviserFullName = mysqli_real_escape_string($connection, $_POST['adviserFullName']);
    $adviserContactNumber = mysqli_real_escape_string($connection, $_POST['adviserContactNumber']);
    $adviserGrLvl = mysqli_real_escape_string($connection, $_POST['adviserGrLvl']);
    $adviserEmailAddress = mysqli_real_escape_string($connection, $_POST['adviserEmailAddress']);
    $adviserPassword = mysqli_real_escape_string($connection, $_POST['adviserPassword']);
    $adviserSection = mysqli_real_escape_string($connection, $_POST['adviserSection']);

    if (empty($adviserFullName) || empty($adviserContactNumber) || empty($adviserGrLvl) || empty($adviserEmailAddress) || empty($adviserPassword) || empty($adviserSection)) {
        $_SESSION['status'] = "One or more fields are missing. Please fill all the fields.";
        header('Location: ../PHPmain/adminTeachers.php');
        exit();
    }

    // Update the adviser based on their original name
    $update_query = "UPDATE adviser SET 
                    adviserFullName = '$adviserFullName', 
                    adviserContactNumber = '$adviserContactNumber', 
                    adviserGrLvl = '$adviserGrLvl', 
                    adviserEmailAddress = '$adviserEmailAddress',
                    adviserPassword = '$adviserPassword',
                    adviserSection = '$adviserSection'
                    WHERE adviserFullName = '$originalAdviserName'";
    
    $update_query_run = mysqli_query($connection, $update_query);

    if($update_query_run && mysqli_affected_rows($connection) > 0) {
        $_SESSION['status'] = "Adviser updated successfully.";
    } else {
        $_SESSION['status'] = "Failed to update adviser. Please try again.";
    }
} else {
    $_SESSION['status'] = "Invalid request or missing data.";
}

header('Location: ../PHPmain/adminTeachers.php');
exit();
