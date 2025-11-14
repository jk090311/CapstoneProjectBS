<?php include "adminNavbar.php" ?>
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Not logged in, redirect to login page
    header("Location: ../PHPmain/index.php");
    exit();
}


$required_role = "admin"; 
if ($_SESSION['user_role'] != $required_role) {
    
    if ($_SESSION['user_role'] == "admin") {
        header("Location: ../PHPAdmin/dashboardAdmin.php");
    } else if ($_SESSION['user_role'] == "adviser") {
        header("Location: ../PHPAdviser/dashboardTeacher.php");
    } else if ($_SESSION['user_role'] == "student") {
        header("Location: dashboardStudent.php");
    }
    exit();
}

include "../PHP/dbconnection.php"; // Include your database connection file

// Fetch sections and their assigned advisers from the database
$query = "select *
FROM advisers RIGHT JOIN class_section 
on advisers.adviserSection = class_section.section_name 
ORDER BY class_section.section_name ASC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Students</title>
    <link rel="stylesheet" href="../CSS/Teacher/adviserStudents.css">
    <link rel="stylesheet" href="../CSS/Admin/sectionBox.css"> <!-- New CSS file -->
</head>

<body>
    <div class="section-container">
        <h1>List of Sections</h1>
        <div class="section-grid">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
    
                    echo '<a href="sectionDetails.php?section_id=' . $row['section_id'] . '" class="section-link">';
                    echo '<div class="section-box">';
                    echo '<h2>' . $row['section_name'] . '</h2>';
                    echo '<p>Grade Level: ' . $row['section_grade_level'] . '</p>';
                    echo '<p>Year level: ' . $row['section_year_start_level'] .' - ' . $row['section_year_end_level']. '</p>';
                    echo '<p>Adviser: ' . (!empty($row['adviserFullName']) ? $row['adviserFullName'] : 'Not Assigned') . '</p>';
                    echo '</div>';
                    echo '</a>';
                }
            } else {
                echo '<p>No sections found</p>';
            }
            ?>
        </div>
    </div>
</body>

</html>




