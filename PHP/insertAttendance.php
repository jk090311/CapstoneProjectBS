<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educguarddb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Ensure POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['rfid_number'])) {
        die("Error: RFID number missing in request!");
    }

    $rfid_number = $conn->real_escape_string($_POST['rfid_number']);
    echo "Received RFID: $rfid_number<br>"; //Debugging

    //Check if student exists in students table
    $studentQuery = "SELECT first_name, last_name FROM student WHERE rfid_number = '$rfid_number'";
    $studentResult = $conn->query($studentQuery);

    if (!$studentResult) {
        die("MySQL Error (students table): " . $conn->error);
    }

    if ($studentResult->num_rows > 0) {
        $student = $studentResult->fetch_assoc();
        $first_name = $student['first_name'];
        $last_name = $student['last_name'];
        echo "Student Found: $first_name $last_name<br>"; // Debugging

        //Check if student already has an active time-in
        $checkExisting = "SELECT id, time_out FROM attendance_list WHERE rfid_number = '$rfid_number' ORDER BY date_logged DESC LIMIT 1";
        $existingResult = $conn->query($checkExisting);

        if (!$existingResult) {
            die("MySQL Error (checking attendance): " . $conn->error);
        }

        if ($existingResult->num_rows > 0) {
            $existingRow = $existingResult->fetch_assoc();
            if ($existingRow['time_out'] === NULL) {
                $updateTimeOut = "UPDATE attendance_list SET time_out = NOW() WHERE id = " . $existingRow['id'];
                if ($conn->query($updateTimeOut) === TRUE) {
                    echo "Time Out Updated!";
                    exit();
                } else {
                    echo "Update Error: " . $conn->error;
                    exit();
                }
            }
        }

        // Insert Time In
        $insertAttendance = "INSERT INTO attendance_list (rfid_number, first_name, last_name, time_in, date_logged) 
                             VALUES ('$rfid_number', '$first_name', '$last_name', NOW(), NOW())";

        if ($conn->query($insertAttendance) === TRUE) {
            echo "Attendance Recorded!";
        } else {
            die("Insert Error: " . $conn->error);
        }
    } else {
        echo "Error: RFID not found in students table!";
    }
} else {
    echo "Invalid Request! Use POST method.";
}

$conn->close();
?>
