<?php
$servername = "sql211.infinityfree.com";
$username = "if0_40275155";
$password = "EduGuard202526";
$dbname = "if0_40275155_eduguarddb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Ensure POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['rfid_number'])) {
        die("Error: RFID number missing in request!");
    }

    $rfid_number = $conn->real_escape_string($_POST['rfid_number']);

    // Check if student exists
    $studentQuery = "SELECT first_name, last_name FROM students WHERE rfid_number = '$rfid_number'";
    $studentResult = $conn->query($studentQuery);

    if ($studentResult->num_rows > 0) {
        $student = $studentResult->fetch_assoc();
        $first_name = $student['first_name'];
        $last_name = $student['last_name'];

        // Check for existing attendance
        $checkExisting = "SELECT id, time_out FROM attendance WHERE rfid_number = '$rfid_number' ORDER BY date_logged DESC LIMIT 1";
        $existingResult = $conn->query($checkExisting);

        if ($existingResult->num_rows > 0) {
            $existingRow = $existingResult->fetch_assoc();
            if ($existingRow['time_out'] === NULL) {
                $updateTimeOut = "UPDATE attendance SET time_out = NOW() WHERE id = " . $existingRow['id'];
                $conn->query($updateTimeOut);
                echo "Time Out Updated!";
                exit();
            }
        }

        // Insert new attendance record
        $insertAttendance = "INSERT INTO attendance (rfid_number, first_name, last_name, time_in, date_logged) 
                             VALUES ('$rfid_number', '$first_name', '$last_name', NOW(), NOW())";
        $conn->query($insertAttendance);
        echo "Attendance Recorded!";
    } else {
        echo "Error: RFID not found in students table!";
    }
} else {
    echo "Invalid Request! Use POST method.";
}

$conn->close();
?>
