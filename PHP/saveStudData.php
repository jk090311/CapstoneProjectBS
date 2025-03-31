<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "attendance_tracking";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$lrn = $_POST['lrn'];
$rfidNo = $_POST['rfidNo'];
$fName = $_POST['fName'];
$mName = $_POST['mName'];
$lName = $_POST['lName'];
$bDate = $_POST['bDate'];
$sex = $_POST['sex'];
$cNumber = $_POST['cNumber'];
$grLvl = $_POST['grLvl'];
$address = $_POST['address'];
$email = $_POST['email'];
$pName = $_POST['pName'];
$pNum = $_POST['pNum'];
$pEmail = $_POST['pEmail'];
$username = $_POST['username'];
$status = $_POST['status'];
$section = $_POST['section'];

// Insert into the database
$sql = "INSERT INTO student (lrn, rfid_number, first_name, middle_name, last_name, birthdate, sex, contact_number, grade_level, address, email, parent_guardian_name, parent_guardian_number, parent_guardian_email, student_username, status, section) 
VALUES ('$lrn', '$rfidNo', '$fName', '$mName', '$lName', '$bDate', '$sex', '$cNumber', '$grLvl', '$address', '$email', '$pName', '$pNum', '$pEmail', '$username', '$status', '$section')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Student registered successfully!'); window.location.href='/CapstoneProjectBS/PHPmain/studentList.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
