<?php
include "teacherNavbar.php"; 

session_start();
// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Not logged in, redirect to login page
    header("Location: ../PHPmain/index.php");
    exit();
}

$required_role = "adviser";
if ($_SESSION['user_role'] != $required_role) {
    if ($_SESSION['user_role'] == "admin") {
        header("Location: ../PHPAdviser/dashboardAdmin.php");
    } else if ($_SESSION['user_role'] == "student") {
        header("Location: dashboardStudent.php");
    }
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educguarddb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch adviser's full name
$adviserFullName = "Teacher"; // Default fallback
if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email'];
    $query = "SELECT adviserFullName, user_email
              FROM advisers A
              INNER JOIN user_acc B
              ON A.adviserEmailAddress = B.user_email
              WHERE B.user_email = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $adviserFullName = htmlspecialchars($row['adviserFullName']); // Sanitize output
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
            <h1>Welcome, Adviser <?php echo $adviserFullName; ?> !</h1>
            <p>Welcome to your dashboard. Here you can find the quick access to various sections.</p>
        </div>

        <?php
        // Count only students and sections for the logged-in adviser's assigned section
        $totalStudents = 0;
        $totalSections = 0;

        // Get the adviser's assigned section (adviserSection column in advisers table)
        $adviserSection = null;
        if (isset($_SESSION['user_email'])) {
            $stmt = $conn->prepare("SELECT adviserSection FROM advisers WHERE adviserEmailAddress = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $_SESSION['user_email']);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res && $res->num_rows > 0) {
                    $adviserSection = $res->fetch_assoc()['adviserSection'];
                }
                $stmt->close();
            }
        }

        if ($adviserSection) {
            // Count students where students.section equals the adviser's section name
            $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM students WHERE section = ?");
            if ($stmt) {
                $stmt->bind_param("s", $adviserSection);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res) { $totalStudents = (int)$res->fetch_assoc()['cnt']; }
                $stmt->close();
            }

            // Count sections that match the adviser's section (usually 1)
            $stmt2 = $conn->prepare("SELECT COUNT(*) AS cnt FROM class_section WHERE section_name = ?");
            if ($stmt2) {
                $stmt2->bind_param("s", $adviserSection);
                $stmt2->execute();
                $res2 = $stmt2->get_result();
                if ($res2) { $totalSections = (int)$res2->fetch_assoc()['cnt']; }
                $stmt2->close();
            }
        }
        ?>

        <div class="cards-row" style="margin-top:12px;margin-bottom:18px;">
            <div class="info-card green">
                <div class="left">
                    <div class="label">TOTAL STUDENTS</div>
                    <div class="value"><?php echo isset($totalStudents) ? intval($totalStudents) : '0'; ?></div>
                </div>
                <div class="icon"><i class="fa-solid fa-user-graduate"></i></div>
            </div>

            <div class="info-card blue">
                <div class="left">
                    <div class="label">TOTAL SECTIONS</div>
                    <div class="value"><?php echo isset($totalSections) ? intval($totalSections) : '0'; ?></div>
                </div>
                <div class="icon"><i class="fa-solid fa-layer-group"></i></div>
            </div>
        </div>

        <h3 style="margin-top:6px;color:#222">Quick Links</h3>
        <ul style="padding-left:18px;color:#0a58ca">
            <li><a href="../PHPAdviser/studentList.php">Students</a></li>
            <li><a href="reportSystem.php">Grades</a></li>
            <li><a href="subjectTeacher.php">Teachers</a></li>
            <li><a href="../PHPAdviser/adviserSubject.php">Subject</a></li>
        </ul>
    </div>
    </main>

</body>
</html>





