<?php include "navbar.php"?>    
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
        <p class="datetimeAlign" id="clock">Loading</h2>
        <p class="datetimeAlign" id="date">Loading</h3>
    </div>

    <div id="rfidContainer">
        <input type="text" id="rfidInput" placeholder="Scan RFID here" autofocus>
    </div>

    <div>
        <div id="attendanceContainer">
        <table id="attendanceTable">
            <tr>
                <th>RFID Number</th>
                <th>Name</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Date Logged</th>
            </tr>
        </table>
        </div>
    </div>
        <script src="/FinalCapstoneWebsite/JS/rfidScan.js"></script>
      <script src="/FinalCapstoneWebsite/JS/AttendanceTracking.js"></script>
</body>
</html>