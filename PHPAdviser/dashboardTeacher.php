<?php
include "teacherNavbar.php"; 



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
$servername = "localhost"; // Replace with your database server name
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "educguarddb";   // Replace with your database name

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
    } else {
        // Handle query preparation error
        error_log("Database query failed: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 800px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            margin-top: 0;
        }
        .quick-links a {
            display: block;
            margin-bottom: 10px;
            color: #007BFF;
            text-decoration: none;
        }
        .quick-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="section">
            <h2>Welcome, Teacher <?php echo $adviserFullName; ?> !</h2>
            <p>Welcome to your dashboard. Here you can find the quick access to various sections.</p>
        </div>
       <!-- <div class="section">
            <h2>Recent Messages</h2>
            <div class="messages">
                 Display recent messages here
                <p>No new messages.</p>
            </div>
        </div>
        <div class="section">
            <h2>Upcoming Events</h2>
            <div class="events">
               Display upcoming events here
                <p>No upcoming events.</p>
            </div>
        </div> -->
        <div class="section">
            <h2>Quick Links</h2>
            <div class="quick-links">
               <!-- <a href="TeacherMessages.php">Messages</a> -->
                <a href="../PHPAdviser/studentList.php">Students</a>
                <a href="reportSystem.php">Grades</a>
                <a href="../PHPAdviser/adviserSubject.php">Subject</a>
            </div>
        </div>
    </div>
</body>
</html>