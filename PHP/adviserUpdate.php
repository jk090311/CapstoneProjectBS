<?php
session_start();

// Use the CORRECT database connection credentials
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if(isset($_POST['adviserFullName']) && $_POST['action_type'] === 'edit') {
    // Get form data
    $originalAdviserName = mysqli_real_escape_string($connection, $_POST['originalAdviserName']);
    $adviserFullName = mysqli_real_escape_string($connection, $_POST['adviserFullName']);
    $adviserContactNumber = mysqli_real_escape_string($connection, $_POST['adviserContactNumber']);
    $adviserGrLvl = mysqli_real_escape_string($connection, $_POST['adviserGrLvl']);
    $adviserEmailAddress = mysqli_real_escape_string($connection, $_POST['adviserEmailAddress']);
    $adviserPassword = mysqli_real_escape_string($connection, $_POST['adviserPassword']);
    $adviserSection = mysqli_real_escape_string($connection, $_POST['adviserSection']);
    $adviserSubject = !empty($_POST['adviserSubject']) ? mysqli_real_escape_string($connection, $_POST['adviserSubject']) : NULL;

    // Validate required fields (password and subject are optional for updates)
    if (empty($adviserFullName) || empty($adviserContactNumber) || empty($adviserGrLvl) || empty($adviserEmailAddress) || empty($adviserSection)) {
        $_SESSION['status'] = "Error: One or more required fields are missing.";
        header('Location: ../PHPAdmin/adminTeachers.php');
        exit();
    }

    // Build update query using prepared statements for security
    if (!empty($adviserPassword)) {
        $stmt = mysqli_prepare($connection, "UPDATE advisers SET 
                        adviserFullName = ?, 
                        adviserContactNumber = ?, 
                        adviserGrLvl = ?, 
                        adviserEmailAddress = ?,
                        adviserPassword = ?,
                        adviserSection = ?,
                        adviserSubject = ?
                        WHERE adviserFullName = ?");
        mysqli_stmt_bind_param($stmt, "ssssssss", $adviserFullName, $adviserContactNumber, $adviserGrLvl, 
                              $adviserEmailAddress, $adviserPassword, $adviserSection, $adviserSubject, $originalAdviserName);
    } else {
        $stmt = mysqli_prepare($connection, "UPDATE advisers SET 
                        adviserFullName = ?, 
                        adviserContactNumber = ?, 
                        adviserGrLvl = ?, 
                        adviserEmailAddress = ?,
                        adviserSection = ?,
                        adviserSubject = ?
                        WHERE adviserFullName = ?");
        mysqli_stmt_bind_param($stmt, "sssssss", $adviserFullName, $adviserContactNumber, $adviserGrLvl, 
                              $adviserEmailAddress, $adviserSection, $adviserSubject, $originalAdviserName);
    }
    
    $update_query_run = mysqli_stmt_execute($stmt);

    if($update_query_run) {
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['status'] = "Adviser updated successfully.";
        } else {
            $_SESSION['status'] = "No changes were made or adviser not found.";
        }
    } else {
        $_SESSION['status'] = "Error updating adviser: " . mysqli_error($connection);
    }
    
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['status'] = "Error: Invalid request or missing data.";
}

mysqli_close($connection);
header('Location: ../PHPAdmin/adminTeachers.php');
exit();
?>




