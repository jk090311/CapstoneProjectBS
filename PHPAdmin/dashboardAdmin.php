<?php include "adminNavbar.php"; ?>

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Not logged in, redirect to login page
    header("Location: ../PHPmain/index.php");
    exit();
}


$required_role = "admin"; 
if ($_SESSION['user_role'] != $required_role) {
    
    if ($_SESSION['user_role'] == "admin") {
        header("Location: ../PHPAdmin/dashboardAdmin.php");
    } else if ($_SESSION['user_role'] == "adviser") {
        header("Location: ../PHPAdviser/dashboardTeacher.php");
    } else if ($_SESSION['user_role'] == "student") {
        header("Location: dashboardStudent.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/CapstoneProjectBS/CSS/Admin/adminDashboard.css" rel="stylesheet">
    <title>Dashboard</title>
   
</head>
<body>
    <div class="container">
        <div class="section">
            <h2>Welcome, Admin!</h2>
            <p>Welcome to your dashboard.</p>
        </div>
        <div class="section">
            <!--<h2>Upcoming Events</h2>
            <div class="events">
                Display upcoming events here
                <p>No upcoming events.</p> -->
            </div>
        </div>
        </div>
    </div>
</body>
</html>