<?php include "adminNavbar.php"; ?>

<?php 

session_start();

if(!isset($_SESSION['user_email']))
{
    header("location:../PHPmain/index.php");
}
else if($_SESSION['user_role'] == "adviser")
{
    header("location:../PHPmain/index.php");
}
else if($_SESSION['user_role'] == "student")
{
    header("location:../PHPmain/index.php");
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
            <p>Welcome to your dashboard. Here you can find the latest updates and quick access to various sections.</p>
        </div>
        <div class="section">
            <h2>Upcoming Events</h2>
            <div class="events">
                <!-- Display upcoming events here -->
                <p>No upcoming events.</p>
            </div>
        </div>
        </div>
    </div>
</body>
</html>