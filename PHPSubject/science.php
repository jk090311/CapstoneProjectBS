<?php include "../PHPmain/teacherNavbar.php"; ?>
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
    <h1>Science Subject</h1>
    <p>Welcome to the Science subject page. Here you will find all the resources and information related to Science.</p>
    
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
                <tr>
                    <td>Student 1</td>
                    <td><input type="number" name="quarter1_student1" min="0" max="99" /></td>
                    <td><input type="number" name="quarter2_student1" min="0" max="99" /></td>
                    <td><input type="number" name="quarter3_student1" min="0" max="99" /></td>
                    <td><input type="number" name="quarter4_student1" min="0" max="99" /></td>
                    <td><input type="number" name="final_grade_student1" min="0" max="99" /></td>
                </tr>
                <tr>
                    <td>Student 2</td>
                    <td><input type="number" name="quarter1_student2" min="0" max="99" /></td>
                    <td><input type="number" name="quarter2_student2" min="0" max="99" /></td>
                    <td><input type="number" name="quarter3_student2" min="0" max="99" /></td>
                    <td><input type="number" name="quarter4_student2" min="0" max="99" /></td>
                    <td><input type="number" name="final_grade_student2" min="0" max="99" /></td>
                </tr>
                <tr>
                    <td>Student 3</td>
                    <td><input type="number" name="quarter1_student3" min="0" max="99" /></td>
                    <td><input type="number" name="quarter2_student3" min="0" max="99" /></td>
                    <td><input type="number" name="quarter3_student3" min="0" max="99" /></td>
                    <td><input type="number" name="quarter4_student3" min="0" max="99" /></td>
                    <td><input type="number" name="final_grade_student3" min="0" max="99" /></td>
                </tr>
                <tr>
                    <td>Student 4</td>
                    <td><input type="number" name="quarter1_student4" min="0" max="99" /></td>
                    <td><input type="number" name="quarter2_student4" min="0" max="99" /></td>
                    <td><input type="number" name="quarter3_student4" min="0" max="99" /></td>
                    <td><input type="number" name="quarter4_student4" min="0" max="99" /></td>
                    <td><input type="number" name="final_grade_student4" min="0" max="99" /></td>
                </tr>
                <!-- Add more students as needed -->
            </tbody>
        </table>
    </div>
</div>
</body>
</html>