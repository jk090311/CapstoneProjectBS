<?php
$servername = "localhost"; // Change if needed
$username = "root"; // Default for XAMPP
$password = ""; // Default for XAMPP
$database = "educguarddb";

$conn = new mysqli($servername, $username, $password, $database);

// Check connections
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
