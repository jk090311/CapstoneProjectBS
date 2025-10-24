<?php 
include "studentNavbar.php";
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Not logged in, redirect to login page
    error_log("User not logged in, redirecting to login");
    header("Location: ../PHPmain/index.php");
    exit();
}


$required_role = "student"; 
if ($_SESSION['user_role'] != $required_role) {
    
    if ($_SESSION['user_role'] == "admin") {
        header("Location: ../PHPAdmin/dashboardAdmin.php");
    } else if ($_SESSION['user_role'] == "adviser") {
        header("Location: ../PHPStudent/dashboardStudent.php");
    }
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="../CSS/homepage.css">
</head>

<body>

    <div id="centerBox">
        <h1 id="welcomeText">
            Welcome Malinta Students!
          </h1>
    </div>
      <script src="../JS/inout.js"></script>

</body>
</html>