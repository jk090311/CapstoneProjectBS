<?php
include "subjectTeacherNavbar.php"; 
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

// Database connection (keep local connection like other dashboards)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educguarddb";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch teacher full name
$stFullName = "Teacher";
if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email'];
    $q = "SELECT stFullName FROM subject_teachers A INNER JOIN user_acc B ON A.stEmail = B.user_email WHERE B.user_email = ?";
    if ($stmt = $conn->prepare($q)) {
        $stmt->bind_param('s', $userEmail);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $stFullName = htmlspecialchars($row['stFullName']);
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/dashboard-cards.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
</head>
<body>
    <main class="page-main">
    <div class="dashboard-wrap">
        <div class="dashboard-hero">
            <h1>Welcome, Teacher <?php echo $stFullName; ?> !</h1>
            <p>Welcome to your dashboard. Here you can find the quick access to various sections.</p>
        </div>

        <?php
        $totalStudents = 0; $totalSections = 0;
        $r = $conn->query("SELECT COUNT(*) AS cnt FROM students"); if($r) $totalStudents = (int)$r->fetch_assoc()['cnt'];
        $r3 = $conn->query("SELECT COUNT(*) AS cnt FROM class_section"); if($r3) $totalSections = (int)$r3->fetch_assoc()['cnt'];
        ?>

        <div class="cards-row" style="margin-top:12px;margin-bottom:18px;">
            <div class="info-card green">
                <div class="left">
                    <div class="label">TOTAL STUDENTS</div>
                    <div class="value"><?php echo intval($totalStudents); ?></div>
                </div>
                <div class="icon"><i class="fa-solid fa-user-graduate"></i></div>
            </div>

            <div class="info-card blue">
                <div class="left">
                    <div class="label">TOTAL SECTIONS</div>
                    <div class="value"><?php echo intval($totalSections); ?></div>
                </div>
                <div class="icon"><i class="fa-solid fa-layer-group"></i></div>
            </div>
        </div>

        <h3 style="margin-top:6px;color:#222">Quick Links</h3>
        <ul style="padding-left:18px;color:#0a58ca">
         <li><a href="../PHPSubjectTeacher/STSubject.php">Subject Grade</a></li>
            <li><a href="../PHPSubjectTeacher/STattendance.php">Attendance</a></li>
            <li><a href="../PHPSubjectTeacher/STMessage.php">Message</a></li>
        </ul>
    </div>
    </main>

</body>
</html>