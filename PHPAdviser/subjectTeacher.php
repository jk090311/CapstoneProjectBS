<?php
include "teacherNavbar.php"; 
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: ../PHPmain/index.php");
    exit();
}       

// Database connection
$conn = new mysqli("sql211.infinityfree.com", "if0_40275155", "EduGuard202526", "if0_40275155_eduguarddb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$alert = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_name = $conn->real_escape_string($_POST['teacher_name']);
    $subject = $conn->real_escape_string($_POST['subject']);

    $sql = "INSERT INTO subject_teachers (teacher_name, subject) VALUES ('$teacher_name', '$subject')";
    if ($conn->query($sql) === TRUE) {
        $alert = "<div class='alert alert-success'>Subject teacher added successfully!</div>";
    } else {
        $alert = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Subject Teacher</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .container {
            margin-top: auto;
            margin-bottom: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php echo $alert; ?>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Add Subject Teacher</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="teacher_name" class="form-label">Teacher Name</label>
                            <input type="text" name="teacher_name" id="teacher_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Add Teacher</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>