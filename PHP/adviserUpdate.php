<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "adviser_list");

if(isset($_POST['adviserUpdate'])) {
    // Get form data
    $adviser_id = mysqli_real_escape_string($connection, $_POST['adviser_id']);
    $adviserFullName = mysqli_real_escape_string($connection, $_POST['adviserFullName']);
    $adviserContactNumber = mysqli_real_escape_string($connection, $_POST['adviserContactNumber']);
    $adviserGrLvl = mysqli_real_escape_string($connection, $_POST['adviserGrLvl']);
    $adviserSection = mysqli_real_escape_string($connection, $_POST['adviserSection']);
    $adviserEmail = mysqli_real_escape_string($connection, $_POST['adviserEmail']);

    // Check if email already exists for other advisers
    $check_email_query = "SELECT * FROM advisers WHERE adviserEmail = '$adviserEmail' AND id != '$adviser_id'";
    $check_email_query_run = mysqli_query($connection, $check_email_query);

    if(mysqli_num_rows($check_email_query_run) > 0) {
        $_SESSION['status'] = "Email ID already exists for another adviser";
    } else {
        // Update data in database
        $update_query = "UPDATE advisers SET 
                        adviserFullName = '$adviserFullName', 
                        adviserContactNumber = '$adviserContactNumber', 
                        adviserGrLvl = '$adviserGrLvl', 
                        adviserSection = '$adviserSection', 
                        adviserEmail = '$adviserEmail' 
                        WHERE id = '$adviser_id'";
        
        $update_query_run = mysqli_query($connection, $update_query);

        if($update_query_run) {
            $_SESSION['status'] = "Adviser updated successfully";
        } else {
            $_SESSION['status'] = "Failed to update adviser: " . mysqli_error($connection);
        }
    }
} else {
    $_SESSION['status'] = "Direct access not allowed";
}

// Redirect back to the advisers list page
header('Location: ../PHPmain/adminTeachers.php');
exit();
?>