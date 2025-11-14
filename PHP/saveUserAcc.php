<?php
// filepath: c:\xampp\htdocs\CapstoneProjectBS\PHP\saveUserAcc.php

// Database connection
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the submitted data
$email = mysqli_real_escape_string($connection, $_POST['adviserEmailAddress']);
$password = mysqli_real_escape_string($connection, $_POST['adviserPassword']);

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert into user_acc table
$query = "INSERT INTO user_acc (user_email, user_password) VALUES ('$email', '$hashed_password')";

if (mysqli_query($connection, $query)) {
    echo "Data saved successfully!";
} else {
    echo "Error: " . mysqli_error($connection);
}

// Close the connection
mysqli_close($connection);

// Redirect back to the adminTeachers.php page
header("Location: ../PHPmain/adminTeachers.php");
exit();
?>





