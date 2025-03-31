<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "attendance_tracking");

if (isset($_POST['studentUpdate'])) {
    $originalFirstName = $_POST['originalFirstName'];
    $originalMiddleName = $_POST['originalMiddleName'];
    $originalLastName = $_POST['originalLastName'];

    $firstName = $_POST['studentFirstName'];
    $middleName = $_POST['studentMiddleName'];
    $lastName = $_POST['studentLastName'];
    $contactNumber = $_POST['studentContactNumber'];
    $gradeLevel = $_POST['studentGrLvl'];
    $section = $_POST['studentSection'];

    // Prepare and execute the update query
    $update_query = "UPDATE student SET

                        first_name = '$firstName', 
                        middle_name = '$middleName', 
                        last_name = '$lastName', 
                        contact_number = '$contactNumber', 
                        grade_level = '$gradeLevel', 
                        section = '$section' 
                     WHERE first_name = '$originalFirstName' 
                     AND middle_name = '$originalMiddleName' 
                     AND last_name = '$originalLastName'";

    $update_query_run = mysqli_query($connection, $update_query);

    if ($update_query_run) {
        $_SESSION['status'] = "Student record updated successfully!";
        header("Location: ../PHPmain/studentList.php");
        exit(0);
    } else {
        $_SESSION['status'] = "Student record update failed!";
        header("Location: ../PHPmain/studentList.php");
        exit(0);
    }
}
?>
