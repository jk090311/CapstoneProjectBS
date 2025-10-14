<?php
session_start();

// Database connection
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['adviserRegister'])) {
    // Sanitize input
    $adviserFullName = mysqli_real_escape_string($connection, $_POST['adviserFullName']);
    $adviserContactNumber = mysqli_real_escape_string($connection, $_POST['adviserContactNumber']);
    $adviserGrLvl = mysqli_real_escape_string($connection, $_POST['adviserGrLvl']);
    $adviserSection = mysqli_real_escape_string($connection, $_POST['adviserSection']);
    $adviserSubject = mysqli_real_escape_string($connection, $_POST['adviserSubject']);
    $adviserEmailAddress = mysqli_real_escape_string($connection, $_POST['adviserEmailAddress']);
    $adviserPassword = mysqli_real_escape_string($connection, $_POST['adviserPassword']);

    // Validate required fields
    if (empty($adviserSection)) {
        $_SESSION['status'] = "Error: Section cannot be empty. Please select a valid section.";
        header('Location: ../PHPAdmin/adminTeachers.php');
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($adviserPassword, PASSWORD_BCRYPT);

    // Start transaction
    mysqli_begin_transaction($connection);

    try {
        // Insert into advisers
        $insert_adviser_query = "INSERT INTO advisers (adviserFullName, adviserContactNumber, adviserGrLvl, adviserEmailAddress, adviserPassword, adviserSection, adviserSubject)
                                 VALUES ('$adviserFullName', '$adviserContactNumber', '$adviserGrLvl', '$adviserEmailAddress', '$hashedPassword', '$adviserSection', '$adviserSubject')";
        $insert_adviser_query_run = mysqli_query($connection, $insert_adviser_query);

        if (!$insert_adviser_query_run) {
            throw new Exception("Adviser insert failed: " . mysqli_error($connection));
        }

        // Insert into user_acc
        $insert_user_query = "INSERT INTO user_acc (user_email, user_password, user_role) 
                              VALUES ('$adviserEmailAddress', '$hashedPassword', 'adviser')";
        $insert_user_query_run = mysqli_query($connection, $insert_user_query);

        if (!$insert_user_query_run) {
            throw new Exception("User account insert failed: " . mysqli_error($connection));
        }

        // All good? Commit.
        mysqli_commit($connection);

        $_SESSION['status'] = "Adviser Profile and Account Added Successfully";
        $_SESSION['show_part'] = 2;

    } catch (Exception $e) {
        // Something went wrong? Rollback.
        mysqli_rollback($connection);
        $_SESSION['status'] = "Error: Account Creation Unsuccessful. " . $e->getMessage();
        $_SESSION['show_part'] = 1;
    }

    header('Location: ../PHPAdmin/adminTeachers.php');
    exit;
}

// Close connection
mysqli_close($connection);
?>