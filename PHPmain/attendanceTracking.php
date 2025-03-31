<?php 
include "adminNavbar.php"; 
include "../PHP/insertAttendance.php"; // This might close $conn

// ✅ Reopen the connection
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
    <link href="/CapstoneProjectBS/CSS/attendanceTrackingg.css" rel="stylesheet">
    <title>Attendance</title>
</head>
<body>

<div class="datetimeBox">
    <p class="datetimeAlign" id="clock">Loading</p>
    <p class="datetimeAlign" id="date">Loading</p>
</div>

<div id="rfidContainer">
    <input type="text" id="rfidInput" placeholder="Scan RFID here" autofocus>
</div>

<div>
    <label for="filterDate">Select Date:</label>
    <input type="date" id="filterDate" onchange="loadAttendance()">
</div>

<div id="attendanceContainer">
    <table id="attendanceTable">
        <tr>
            <th>RFID Number</th>
            <th>Name</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Date Logged</th>
        </tr>
        <?php
        $dateFilter = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
        $query = "SELECT * FROM attendance_list WHERE date_logged = ? ORDER BY time_in ASC";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $dateFilter);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['rfid_number']}</td>
                        <td>{$row['first_name']} {$row['last_name']}</td>
                        <td>{$row['time_in']}</td>
                        <td>{$row['time_out']}</td>
                        <td>{$row['date_logged']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Error preparing statement.</td></tr>";
        }
        ?>
    </table>
</div>

<script src="/CapstoneProjectBS/JS/rfidScan.js"></script>
<script src="/CapstoneProjectBS/JS/AttendanceTracking.js"></script>
<script src="/CapstoneProjectBS/JS/datetime.js"></script>

</body>
</html>
