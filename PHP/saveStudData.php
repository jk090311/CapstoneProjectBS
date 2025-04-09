<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "educguarddb";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data with validation
$lrn = isset($_POST['lrn']) ? $_POST['lrn'] : '';
$rfidNo = isset($_POST['rfidNo']) ? $_POST['rfidNo'] : '';
$fName = isset($_POST['fName']) ? $_POST['fName'] : '';
$mName = isset($_POST['mName']) ? $_POST['mName'] : '';
$lName = isset($_POST['lName']) ? $_POST['lName'] : '';
$bDate = isset($_POST['bDate']) ? $_POST['bDate'] : '';
$sex = isset($_POST['sex']) ? $_POST['sex'] : '';
$cNumber = isset($_POST['cNumber']) ? $_POST['cNumber'] : '';
$grLvl = isset($_POST['grLvl']) ? $_POST['grLvl'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$pName = isset($_POST['pName']) ? $_POST['pName'] : '';
$pNum = isset($_POST['pNum']) ? $_POST['pNum'] : '';
$pEmail = isset($_POST['pEmail']) ? $_POST['pEmail'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
$section = isset($_POST['section']) ? $_POST['section'] : '';
$password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';
$user_role = "student"; // Default user role

// Debugging: Check if password is empty
if (empty($password)) {
    die("Password is empty. Please check the form submission.");
}

if (!isset($_POST['section'])) {
    die("Section field is not set in the form submission.");
}

if (empty($_POST['section'])) {
    die("Section is empty. Please check the form submission.");
}

// Use prepared statements to insert data into the students table
$stmt = $conn->prepare("INSERT INTO students (lrn, rfid_number, first_name, middle_name, last_name, birthdate, sex, contact_number, grade_level, address, email, parent_guardian_name, parent_guardian_number, parent_guardian_email, student_username, status, section, student_password) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssssssssssss", $lrn, $rfidNo, $fName, $mName, $lName, $bDate, $sex, $cNumber, $grLvl, $address, $email, $pName, $pNum, $pEmail, $username, $status, $section, $password);

if ($stmt->execute()) {
    // Insert into user_acc table
    $userAccStmt = $conn->prepare("INSERT INTO user_acc (user_email, user_password, user_role) VALUES (?, ?, ?)");
    $userAccStmt->bind_param("sss", $email, $password, $user_role);

    if ($userAccStmt->execute()) {
        echo "<script>alert('Student registered successfully!'); window.location.href='/CapstoneProjectBS/PHPAdmin/studentList.php';</script>";
    } else {
        echo "Error in user_acc table: " . $userAccStmt->error;
    }

    $userAccStmt->close();
} else {
    echo "Error in students table: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
