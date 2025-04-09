<?php
session_start();

// Database connection
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if (isset($_POST['adviserRegister'])) {
    // Get form data safely
    $adviserFullName = mysqli_real_escape_string($connection, $_POST['adviserFullName']);
    $adviserContactNumber = mysqli_real_escape_string($connection, $_POST['adviserContactNumber']);
    $adviserGrLvl = mysqli_real_escape_string($connection, $_POST['adviserGrLvl']);
    $adviserEmailAddress = mysqli_real_escape_string($connection, $_POST['adviserEmailAddress']);
    $adviserPassword = mysqli_real_escape_string($connection, $_POST['adviserPassword']);
    $adviserSection = mysqli_real_escape_string($connection, $_POST['adviserSection']);

    // Validate that adviserSection is not empty
    if (empty($adviserSection)) {
        $_SESSION['status'] = "Error: Section cannot be empty. Please select a valid section.";
        header('Location: ../PHPmain/adminTeachers.php');
        exit;
    }

    // Hash password for security
    $hashedPassword = password_hash($adviserPassword, PASSWORD_DEFAULT);

    // Insert into advisers table
    $insert_adviser_query = "INSERT INTO advisers (adviserFullName, adviserContactNumber, adviserGrLvl, adviserEmailAddress, adviserPassword, adviserPlainPassword, adviserSection)
                             VALUES ('$adviserFullName', '$adviserContactNumber', '$adviserGrLvl', '$adviserEmailAddress', '$hashedPassword', '$adviserPassword', '$adviserSection')";
    $insert_adviser_query_run = mysqli_query($connection, $insert_adviser_query);

    // Insert into user_acc table
    $insert_user_query = "INSERT INTO user_acc (user_email, user_password, user_role) 
                          VALUES ('$adviserEmailAddress', '$hashedPassword', 'adviser')";
    $insert_user_query_run = mysqli_query($connection, $insert_user_query);

    // Check if both queries were successful
    if ($insert_adviser_query_run && $insert_user_query_run) {
        $_SESSION['status'] = "Adviser Profile and Account Added Successfully";
        $_SESSION['show_part'] = 2; // Show Part 2
    } else {
        $_SESSION['status'] = "Error: " . mysqli_error($connection);
        $_SESSION['show_part'] = 1; // Show Part 1 if query fails
    }

    // Redirect back to the adminTeachers.php page
    header('Location: ../PHPAdmin/adminTeachers.php');
    exit;
}

// Close the connection
mysqli_close($connection);
?>