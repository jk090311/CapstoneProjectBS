<?php include "adminNavbar.php"; ?>

<?php 

session_start();

if(!isset($_SESSION['user_email']))
{
    header("location:../PHPmain/index.php");
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 800px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            margin-top: 0;
        }
        .quick-links a {
            display: block;
            margin-bottom: 10px;
            color: #007BFF;
            text-decoration: none;
        }
        .quick-links a:hover {
            text-decoration: underline;
        }
    </style>
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