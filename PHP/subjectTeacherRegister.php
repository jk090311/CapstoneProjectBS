<?php
session_start();

// Database connection
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['subjectTeacherRegister'])) {
    // Sanitize input
    $stFullName = mysqli_real_escape_string($connection, $_POST['stFullName']);
    $stContactNumber = mysqli_real_escape_string($connection, $_POST['stContactNumber']);
    $stEmail = mysqli_real_escape_string($connection, $_POST['stEmail']);
    $stPassword = mysqli_real_escape_string($connection, $_POST['stPassword']);
    $stSubject = mysqli_real_escape_string($connection, $_POST['stSubject']);

    // Hash the password
    $stHashedPassword = password_hash($stPassword, PASSWORD_BCRYPT);

    // Start transaction
    mysqli_begin_transaction($connection);

    try {
        // Insert into subject_teachers table
        $insert_teacher_query = "INSERT INTO subject_teachers (stFullName, stContactNumber, stEmail, stPassword, stSubject)
                        VALUES ('$stFullName', '$stContactNumber', '$stEmail', '$stHashedPassword', '$stSubject')";
        $insert_teacher_query_run = mysqli_query($connection, $insert_teacher_query);

        if (!$insert_teacher_query_run) {
            throw new Exception("Subject Teacher insert failed: " . mysqli_error($connection));
        }

        // Insert into user_acc table
        $insert_user_query = "INSERT INTO user_acc (user_email, user_password, user_role)
                     VALUES ('$stEmail', '$stHashedPassword', 'subject_teacher')";
        $insert_user_query_run = mysqli_query($connection, $insert_user_query);

        if (!$insert_user_query_run) {
            throw new Exception("User account insert failed: " . mysqli_error($connection));
        }

        // Commit transaction
        mysqli_commit($connection);

        $_SESSION['status'] = "Subject Teacher Profile and Account Added Successfully";
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($connection);
        $_SESSION['status'] = "Error: Account Creation Unsuccessful. " . $e->getMessage();
    }

    header('Location: ../PHPAdmin/adminTeachers.php');
    exit;
}

// Close connection
mysqli_close($connection);
?>