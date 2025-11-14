<?php
// === adviserRemove.php (Fixed for AJAX Requests) ===
session_start();
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

// Debugging (optional, you can remove this after testing)
file_put_contents('debug.txt', "POST data: " . print_r($_POST, true));

// Check if the adviser name is provided
if (isset($_POST['adviserFullName'])) {
    $adviserFullName = $_POST['adviserFullName'];

    // First, get the adviser's email address
    $select_query = "SELECT adviserEmailAddress FROM advisers WHERE adviserFullName = ?";
    $select_stmt = $connection->prepare($select_query);
    $select_stmt->bind_param("s", $adviserFullName);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $adviserEmail = $row['adviserEmailAddress'];
        
        // Delete from advisers table
        $delete_adviser_query = "DELETE FROM advisers WHERE adviserFullName = ?";
        $stmt_adviser = $connection->prepare($delete_adviser_query);
        $stmt_adviser->bind_param("s", $adviserFullName);
        
        // Delete from user_acc table
        $delete_user_query = "DELETE FROM user_acc WHERE user_email = ? AND user_role = 'adviser'";
        $stmt_user = $connection->prepare($delete_user_query);
        $stmt_user->bind_param("s", $adviserEmail);
        
        if ($stmt_adviser->execute() && $stmt_user->execute()) {
            echo "Adviser successfully removed.";  // Send success message
        } else {
            echo "Failed to remove adviser.";  // Send error message
        }
        
        $stmt_adviser->close();
        $stmt_user->close();
    } else {
        echo "Adviser not found.";  // Error message if adviser doesn't exist
    }
    
    $select_stmt->close();
} else {
    echo "No adviser name provided.";  // Error message if no data was sent
}

$connection->close();




