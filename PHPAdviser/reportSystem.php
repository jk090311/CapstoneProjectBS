<?php include "teacherNavbar.php" ?>
<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "educguarddb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get adviser's section
$adviser_email = $_SESSION['user_email'];
$adviser_section_query = "SELECT adviserSection FROM advisers WHERE adviserEmailAddress = ?";
$stmt = $conn->prepare($adviser_section_query);
$stmt->bind_param("s", $adviser_email);
$stmt->execute();
$adviser_result = $stmt->get_result();
$adviser_section = ($adviser_result && $adviser_result->num_rows > 0) ? $adviser_result->fetch_assoc()['adviserSection'] : null;
$stmt->close();

// Handle grade submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id']) && isset($_POST['quarter']) && isset($_POST['grade']) && isset($_POST['subject_id'])) {
    $student_id = $_POST['student_id'];
    $quarter_id = $_POST['quarter'];
    $grade = $_POST['grade'];
    $subject_id = $_POST['subject_id'];

    try {
        // First check if grade already exists
        $check_sql = "SELECT grade_id FROM grades 
                     WHERE student_id = ? 
                     AND subject_id = ? 
                     AND quarter_id = ?";

        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("iii", $student_id, $subject_id, $quarter_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $check_stmt->close();

        if ($check_result->num_rows > 0) {
            // Update existing grade
            $update_sql = "UPDATE grades 
                          SET grade = ? 
                          WHERE student_id = ? 
                          AND subject_id = ? 
                          AND quarter_id = ?";

            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("diii", $grade, $student_id, $subject_id, $quarter_id);
        } else {
            // Insert new grade
            $insert_sql = "INSERT INTO grades (student_id, subject_id, quarter_id, grade) 
                          VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("iiid", $student_id, $subject_id, $quarter_id, $grade);
        }

        $success = $stmt->execute();
        $stmt->close();

        echo json_encode(['success' => $success]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Management</title>
    <link rel="stylesheet" href="../CSS/Teacher/reportSystem.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="background-image"></div>
    
    <header class="header">
        <button class="menu-button">☰</button>
        <img src="eduguard_logo.png" alt="EduGuard" class="logo">
        <h3 style="margin-left: 10px;">EduGuard</h3>
    </header>
    
    <div class="page-content">
        <div id="subjects-list" class="subject-container">
            <?php
            // Fetch all subjects
            $query = "SELECT * FROM subjects ORDER BY subject_name ASC";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $subjectId = $row['subject_id'];
                $subjectName = $row['subject_name'];
                $subjectPicture = $row['subject_picture'];
                
                echo "<div class='subject-box' data-subject-id='{$subjectId}' data-subject-name='{$subjectName}'>
                    <div class='subject-content'>
                        <img class='subject-img' src='../Uploads/{$subjectPicture}' alt='{$subjectName}'>
                        <div class='subject-title'>{$subjectName}</div>
                    </div>
                </div>";
            }
            ?>
        </div>

        <div id="grading-section" class="grading-section">
            <h2 id="subject-heading"></h2>
            <div id="grades-table"></div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.subject-box').on('click', function() {
                const subjectId = $(this).data('subject-id');
                const subjectName = $(this).data('subject-name');
                // Redirect to getGrades.php with both subject ID and name parameters
                window.location.href = `getGrades.php?subject_id=${subjectId}&subject_name=${encodeURIComponent(subjectName)}`;
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>