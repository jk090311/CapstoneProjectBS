<?php 
include "adminNavbar.php"; 
include "../PHP/insertAttendance.php"; // This might close $conn

//Reopen the connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educguarddb";

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
    <div id="rfidContainer">
        <input type="text" id="rfidInput" placeholder="Scan RFID here" autofocus>
    </div>
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
