<?php include "../PHPmain/teacherNavbar.php"; ?>
<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "educguarddb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch students from the database
$sql = "SELECT first_name, middle_name, last_name FROM students";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Error in SQL query: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Science</title>
    <link rel="stylesheet" href="../CSS/Teacher/science.css">
</head>
<body>
<div class="background-image"></div>

<header class="header">
    <button class="menu-button">☰</button>
    <img src="../Assets/eduguard_logo.png" alt="EduGuard" class="logo">
    <h3 style="margin-left: 10px;">EduGuard</h3>
</header>

<div class="page-content">
    <h1>Filipino Subject</h1>
    <p>Welcome to the Filipino subject page. Here you will find all the resources and information related to Filipino.</p>
    
    <!-- Box for the list of students -->
    <div class="student-list-box">
        <h2>List of Students</h2>
        <table class="student-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Quarter 1</th>
                    <th>Quarter 2</th>
                    <th>Quarter 3</th>
                    <th>Quarter 4</th>
                    <th>Final Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php 
                                    // Concatenate first_name, middle_name, and last_name
                                    echo htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']); 
                                ?>
                            </td>
                            <td><input type="number" name="quarter1_student<?php echo $row['first_name'] . $row['middle_name'] . $row['last_name']; ?>" min="0" max="99" /></td>
                            <td><input type="number" name="quarter2_student<?php echo $row['first_name'] . $row['middle_name'] . $row['last_name']; ?>" min="0" max="99" /></td>
                            <td><input type="number" name="quarter3_student<?php echo $row['first_name'] . $row['middle_name'] . $row['last_name']; ?>" min="0" max="99" /></td>
                            <td><input type="number" name="quarter4_student<?php echo $row['first_name'] . $row['middle_name'] . $row['last_name']; ?>" min="0" max="99" /></td>
                            <td><input type="number" name="final_grade_student<?php echo $row['first_name'] . $row['middle_name'] . $row['last_name']; ?>" min="0" max="99" /></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
<?php $conn->close(); ?>