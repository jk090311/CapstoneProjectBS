<?php
$servername = "localhost";  // Change if necessary
$username = "root";         // Your database username
$password = "";             // Your database password
$database = "attendance_tracking"; // Your actual database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
