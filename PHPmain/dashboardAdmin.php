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

// Include DB connection (use existing dbconnection.php if available)
require_once __DIR__ . '/../PHP/dbconnection.php';

// Query totals for info cards
$totalStudents = 0;
$totalAdvisers = 0;
$totalSections = 0;
// Safely run queries and handle errors
$res = $conn->query("SELECT COUNT(*) AS cnt FROM students");
if($res){
    $row = $res->fetch_assoc();
    $totalStudents = $row['cnt'];
}
$res2 = $conn->query("SELECT COUNT(*) AS cnt FROM advisers");
if($res2){
    $row2 = $res2->fetch_assoc();
    $totalAdvisers = $row2['cnt'];
}
// sections
$res3 = $conn->query("SELECT COUNT(*) AS cnt FROM class_section");
if($res3){
    $row3 = $res3->fetch_assoc();
    $totalSections = $row3['cnt'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/CapstoneProjectBS/CSS/Admin/adminDashboard.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
    /* Inline styles for admin info cards */
    .info-row{display:flex;gap:20px;margin-top:20px;flex-wrap:wrap}
    .info-card{flex:1;min-width:260px;padding:18px;border-radius:8px;color:#fff;display:flex;align-items:center;justify-content:space-between;box-shadow:0 2px 6px rgba(0,0,0,0.08)}
    .info-left{display:flex;flex-direction:column}
    .info-left .label{font-size:12px;letter-spacing:1px;text-transform:uppercase;opacity:.9}
    .info-left .big{font-size:28px;font-weight:700;margin-top:8px}
    .info-icon{font-size:42px;opacity:.95}
    .info-green{background:#2e8b57}
    .info-orange{background:#ff9800}
    .info-blue{background:#1e90ff}
    @media(max-width:720px){.info-card{min-width:200px}}
    </style>
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
        
        <!-- Info cards (wide rectangular style) -->
        <div class="info-row">
            <div class="info-card info-green">
                <div class="info-left">
                    <div class="label">Total Students</div>
                    <div class="big"><?php echo intval($totalStudents); ?></div>
                </div>
                <div class="info-icon"><i class="fa-solid fa-user-graduate"></i></div>
            </div>

            <div class="info-card info-orange">
                <div class="info-left">
                    <div class="label">Total Advisers</div>
                    <div class="big"><?php echo intval($totalAdvisers); ?></div>
                </div>
                <div class="info-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
            </div>

            <div class="info-card info-blue">
                <div class="info-left">
                    <div class="label">Total Sections</div>
                    <div class="big"><?php echo intval($totalSections); ?></div>
                </div>
                <div class="info-icon"><i class="fa-solid fa-layer-group"></i></div>
            </div>
        </div>
        </div>
    </div>
</body>
</html>




