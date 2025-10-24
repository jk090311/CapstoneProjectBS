<?php
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

include "subjectTeacherNavbar.php";
include "../PHP/dbconnection.php"; // Include your database connection file

// Fetch sections, subject teacher and adviser info
$query = "SELECT DISTINCT
    A.adviserFullName AS adviserFullName1,
    A.adviserGrlvl,
    A.advisersection,
    A.adviserSubject,
    B.section_id,
    B.section_name AS stSection1_name,
    B.section_grade_level, 
    C.stID,
    C.stFullName,
    C.stGradelvl AS stGradelvl1,
    C.stGradelvl2 AS stGradelvl2, 
    C.stGradelvl3 AS stGradelvl3,
    C.stSection AS stSection1,
    C.stSection2 AS stSection2,
    C.stSection3 AS stSection3,
    C.stSubject AS stSubject1,
    C.stSubject2 AS stSubject2,
    C.stSubject3 AS stSubject3,
    D.subject_id AS subject1_id,
    D.subject_name AS subject1_name,
    D.subject_picture AS subject1_picture,
    D2.subject_id AS subject2_id,
    D2.subject_name AS subject2_name,
    D2.subject_picture AS subject2_picture,
    D3.subject_id AS subject3_id,
    D3.subject_name AS subject3_name,
    D3.subject_picture AS subject3_picture,
    B2.section_name AS stSection2_name,
    A2.adviserFullName AS adviserFullName2,
    B3.section_name AS stSection3_name,
    A3.adviserFullName AS adviserFullName3
FROM subject_teachers C
INNER JOIN subjects D ON C.stSubject = D.subject_id
LEFT JOIN subjects D2 ON C.stSubject2 = D2.subject_id
LEFT JOIN subjects D3 ON C.stSubject3 = D3.subject_id
INNER JOIN class_section B ON C.stSection = B.section_id
LEFT JOIN advisers A ON A.advisersection = B.section_name
LEFT JOIN class_section B2 ON C.stSection2 = B2.section_id
LEFT JOIN advisers A2 ON A2.advisersection = B2.section_name
LEFT JOIN class_section B3 ON C.stSection3 = B3.section_id
LEFT JOIN advisers A3 ON A3.advisersection = B3.section_name
WHERE C.stID = '$stID'";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
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
        <h1>List of subjects</h1>
        <div class="section-grid">
           <?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        // Only show if student ID is assigned
        if (!empty($row['stID'])) {

            // Loop through all subjects dynamically
            for ($i = 1; $i <= 10; $i++) { // change 10 to your max number of subjects
                $subjectIdKey = 'subject' . $i . '_id';
                $subjectNameKey = 'subject' . $i . '_name';
                $subjectPictureKey = 'subject' . $i . '_picture';
                $sectionNameKey = 'stSection' . $i . '_name';
                $adviserNameKey = 'adviserFullName' . $i;

                // Only display if subject exists
                if (!empty($row[$subjectIdKey])) {
                    echo '<a href="getGrades.php?subject_id=' . $row[$subjectIdKey] . 
                         '&subject_name=' . urlencode($row[$subjectNameKey]) . 
                         '&section=' . urlencode($row[$sectionNameKey]) . '" class="section-link">';
                    echo '<div class="section-box">';
                    echo '<img src="../uploads/' . htmlspecialchars($row[$subjectPictureKey]) . '" 
                               alt="Section Image" 
                               class="section-image" 
                               style="width:120px; height:120px; object-fit:cover; border-radius:8px;">';
                    echo '<h2>' . htmlspecialchars($row[$subjectNameKey]) . '</h2>';
                    echo '<p>Section: ' . 
                         (!empty($row[$sectionNameKey]) ? htmlspecialchars($row[$sectionNameKey]) : 'Not Assigned') . 
                         '</p>';
                    echo '<p>Adviser: ' . 
                         (!empty($row[$adviserNameKey]) ? htmlspecialchars($row[$adviserNameKey]) : 'Not Assigned') . 
                         '</p>';
                    echo '</div>';
                    echo '</a>';
                }
            }
        }
    }
} else {
    echo '<p>No sections found</p>';
}
?>

        </div>
    </div>
</body>

</html>