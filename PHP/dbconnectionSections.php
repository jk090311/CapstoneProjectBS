<?php
$servername = "sql211.infinityfree.com";
$username = "if0_40275155";
$password = "EduGuard202526";
$dbname = "if0_40275155_eduguarddb";


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connections
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
