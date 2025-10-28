<?php
$servername = "sql211.infinityfree.com";
$username = "if0_40275155";
$password = "EduGuard202526";
$dbname = "if0_40275155_eduguarddb";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data with validation
$lrn = isset($_POST['lrn']) ? trim($_POST['lrn']) : '';
$fName = isset($_POST['fName']) ? trim($_POST['fName']) : '';
$mName = isset($_POST['mName']) ? trim($_POST['mName']) : '';
$lName = isset($_POST['lName']) ? trim($_POST['lName']) : '';
$bDate = isset($_POST['bDate']) ? trim($_POST['bDate']) : '';
$sex = isset($_POST['sex']) ? trim($_POST['sex']) : '';
$cNumber = isset($_POST['cNumber']) ? trim($_POST['cNumber']) : '';
$grLvl = isset($_POST['grLvl']) ? trim($_POST['grLvl']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$pName = isset($_POST['pName']) ? trim($_POST['pName']) : '';
$pNum = isset($_POST['pNum']) ? trim($_POST['pNum']) : '';
$pEmail = isset($_POST['pEmail']) ? trim($_POST['pEmail']) : '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$status = isset($_POST['status']) ? trim($_POST['status']) : '';
$section = isset($_POST['section']) ? trim($_POST['section']) : '';
$password = isset($_POST['password']) && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
$user_role = "student"; // Default user role

// Validate required fields
if (!$password) {
    die("Password is required and cannot be empty."); 
}

if (empty($section)) {
    die("Section is required and cannot be empty.");
}

// Use prepared statements to insert data into the students table
$stmt = $conn->prepare("INSERT INTO students (lrn, first_name, middle_name, last_name, birthdate, sex, contact_number, grade_level, address, email, parent_guardian_name, parent_guardian_number, parent_guardian_email, student_username, student_password, status, section) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssssssssss", $lrn, $fName, $mName, $lName, $bDate, $sex, $cNumber, $grLvl, $address, $email, $pName, $pNum, $pEmail, $username, $password, $status, $section);

if ($stmt->execute()) {
    // Insert into user_acc table
    $userAccStmt = $conn->prepare("INSERT INTO user_acc (user_email, user_password, user_role) VALUES (?, ?, ?)");
    $userAccStmt->bind_param("sss", $email, $password, $user_role);

    if ($userAccStmt->execute()) {
        echo "<script>alert('Student registered successfully!'); window.location.href='/CapstoneProjectBS/PHPAdviser/studentList.php';</script>";
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