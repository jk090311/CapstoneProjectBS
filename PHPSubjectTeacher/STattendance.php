<?php 
include "subjectTeacherNavbar.php"; 
include "../PHP/insertAttendance.php"; // This might close $conn
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: ../PHPmain/index.php");
    exit();
}

$required_role = "subject_teacher";
if ($_SESSION['user_role'] != $required_role) {
    if ($_SESSION['user_role'] == "admin") {
        header("Location: ../PHPAdviser/dashboardAdmin.php");
    } else if ($_SESSION['user_role'] == "adviser") {
        header("Location: ../PHPAdviser/dashboardAdviser.php");
    } else if ($_SESSION['user_role'] == "student") {
        header("Location: ../PHPStudent/dashboardStudent.php");
    }
    exit();
}
//Reopen the connection
$servername = "sql211.infinityfree.com";
$username = "if0_40275155";
$password = "EduGuard202526";
$dbname = "if0_40275155_eduguarddb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/CapstoneProjectBS/CSS/attendanceTracking.css" rel="stylesheet">
    <title>Attendance</title>
</head>
<body>

<div class="datetimeBox">
    <p class="datetimeAlign" id="clock">Loading</p>
    <p class="datetimeAlign" id="date">Loading</p>
</div>

<div id="inputContainer">   
    <div id="dateContainer">
        <input type="date" id="filterDate" onchange="loadAttendanceData()">
    </div>
</div>

<div id="attendanceContainer">
    <table id="attendanceTable">
        <tr>
            <th>RFID Number</th>
            <th>Name</th>
            <th>Section</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Date Logged</th>
        </tr>
        <!-- Data will be loaded via JavaScript -->
    </table>
</div>

<script src="/CapstoneProjectBS/JS/rfidScan.js"></script>
<script src="/CapstoneProjectBS/JS/AttendanceTracking.js"></script>

</body>
</html>
