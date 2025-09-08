<?php
// === adviserRemove.php (Fixed for AJAX Requests) ===
session_start();
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the subject teacher name is provided
if (isset($_POST['stFullName'])) {
    $stFullName = $_POST['stFullName']; // Fixed variable name

    try {
        // Start transaction
        mysqli_begin_transaction($connection);

        // First, delete from user_acc table
        $delete_user_query = "DELETE FROM user_acc WHERE user_email IN 
                            (SELECT stEmail FROM subject_teachers WHERE stFullName = ?)
                            ";
        $stmt_user = $connection->prepare($delete_user_query);
        $stmt_user->bind_param("s", $stFullName);
        $stmt_user->execute();

        // Then delete from subject_teachers table
        $delete_query = "DELETE FROM subject_teachers WHERE stFullName = ?";
        $stmt = $connection->prepare($delete_query);
        $stmt->bind_param("s", $stFullName);
        
        if ($stmt->execute()) {
            // Commit transaction
            mysqli_commit($connection);
            echo "Subject Teacher successfully removed.";
        } else {
            // Rollback on failure
            mysqli_rollback($connection);
            echo "Failed to remove subject teacher: " . $stmt->error;
        }

        $stmt->close();
        $stmt_user->close();

    } catch (Exception $e) {
        mysqli_rollback($connection);
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No subject teacher name provided.";
}

$connection->close();
?>
