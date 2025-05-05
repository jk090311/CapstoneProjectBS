<?php include "teacherNavbar.php"; ?>
<?php session_start(); ?>
<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "educguarddb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch subjects from the database
$sql = "SELECT subject_name, subject_picture, link FROM subjects";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade</title>
    <link rel="stylesheet" href="../CSS/Teacher/reportSystem.css">
</head>
<body>
<div class="background-image"></div>
    
    <header class="header">
        <button class="menu-button">☰</button>
        <img src="eduguard_logo.png" alt="EduGuard" class="logo">
        <h3 style="margin-left: 10px;">EduGuard</h3>
    </header>
    
    <div class="page-content">
        <div class="subject-container">
            <?php
            if ($result->num_rows > 0) {
                // Output data for each subject
                while ($row = $result->fetch_assoc()) {
                    echo '<a href="' . $row['link'] . '" class="subject-box">
                            <div class="subject-content">
                                <img class="subject-img" src="' . (file_exists($row['subject_picture']) ? $row['subject_picture'] : '../Assets/default.jpg') . '">
                                <div class="subject-title">' . $row['subject_name'] . '</div>
                            </div>
                          </a>';
                }
            } else {
                echo "<p>No subjects available.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>