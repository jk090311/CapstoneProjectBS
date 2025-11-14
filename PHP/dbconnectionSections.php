<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educguarddb";


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connections
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>




