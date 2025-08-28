
<?php include "subjectTeacherNavbar.php" ?>

<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educguarddb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch subject teacher's full name
$fullName = "Teacher"; // Default fallback
if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email'];
    $query = "SELECT fullName FROM subject_teachers WHERE email = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $fullName = htmlspecialchars($row['fullName']); // Sanitize output
        }
        $stmt->close();
    } else {
        // Handle query preparation error
        error_log("Database query failed: " . $conn->error);
    }
}
?>


<!DOCTYPE html>
<html lang="en">    