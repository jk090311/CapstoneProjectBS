<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if(isset($_POST['stFullName'])) { // Changed from 'subjectTeacherFullName' to match form field name
    // Get form data with correct field names
    $originalstName = mysqli_real_escape_string($connection, $_POST['originalstName']);
    $stFullName = mysqli_real_escape_string($connection, $_POST['stFullName']); // Fixed field name
    $stContactNumber = mysqli_real_escape_string($connection, $_POST['stContactNumber']); // Fixed field name
    $stEmail = mysqli_real_escape_string($connection, $_POST['stEmail']); // Fixed field name
    $stPassword = mysqli_real_escape_string($connection, $_POST['stPassword']); // Fixed field name
    $stSubject = mysqli_real_escape_string($connection, $_POST['stSubject']); // Fixed field name

    // Hash the password if it's changed
    $stHashedPassword = password_hash($stPassword, PASSWORD_BCRYPT);

    try {
        mysqli_begin_transaction($connection);

        // First, get the old email for user_acc table update
        $get_old_email_query = "SELECT stEmail FROM subject_teachers WHERE stFullName = ?";
        $stmt_old = $connection->prepare($get_old_email_query);
        $stmt_old->bind_param("s", $originalstName);
        $stmt_old->execute();
        $result = $stmt_old->get_result();
        $old_data = $result->fetch_assoc();
        $old_email = $old_data['stEmail'];

        // Update subject_teachers table
        $update_query = "UPDATE subject_teachers SET 
                        stFullName = ?, 
                        stContactNumber = ?, 
                        stEmail = ?, 
                        stPassword = ?, 
                        stSubject = ? 
                        WHERE stFullName = ?";
                
        $stmt = $connection->prepare($update_query);
        $stmt->bind_param("ssssss", $stFullName, $stContactNumber, $stEmail, $stHashedPassword, $stSubject, $originalstName);
        
        if($stmt->execute()) {
            // Update user_acc table using the old email
            $update_user_query = "UPDATE user_acc SET 
                                 user_email = ?, 
                                 user_password = ? 
                                 WHERE user_email = ?";
                        
            $stmt_user = $connection->prepare($update_user_query);
            $stmt_user->bind_param("sss", $stEmail, $stHashedPassword, $old_email);
            
            if($stmt_user->execute()) {
                mysqli_commit($connection);
                $_SESSION['status'] = "Subject Teacher updated successfully.";
            } else {
                throw new Exception("Failed to update user account table");
            }
        } else {
            throw new Exception("Failed to update subject teacher");
        }

    } catch (Exception $e) {
        mysqli_rollback($connection);
        $_SESSION['status'] = "Error: " . $e->getMessage();
    }

} else {
    $_SESSION['status'] = "Invalid request or missing data.";
}

mysqli_close($connection);
header('Location: ../PHPAdmin/adminTeachers.php');
exit();
?>