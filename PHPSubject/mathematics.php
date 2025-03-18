<?php include "../PHPmain/teacherNavbar.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Science</title>
    <link rel="stylesheet" href="../CSS/Teacher/science.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetchStudents();
        });

        function fetchStudents() {
            const sectionName = "Science"; // Replace with the actual section name
            fetch(`/CapstoneProjectBS/PHP/fetchStudents.php?section=${sectionName}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector(".student-table tbody");
                    tbody.innerHTML = "";
                    data.forEach(student => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${student.first_name} ${student.middle_name} ${student.last_name}</td>
                            <td><input type="number" name="quarter1_${student.lrn}" /></td>
                            <td><input type="number" name="quarter2_${student.lrn}" /></td>
                            <td><input type="number" name="quarter3_${student.lrn}" /></td>
                            <td><input type="number" name="quarter4_${student.lrn}" /></td>
                            <td><input type="number" name="final_grade_${student.lrn}" /></td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching students:', error));
        }
    </script>
</head>
<body>
<div class="background-image"></div>
<div class="background-overlay"></div>

<header class="header">
    <button class="menu-button">☰</button>
    <img src="../Assets/eduguard_logo.png" alt="EduGuard" class="logo">
    <h3 style="margin-left: 10px;">EduGuard</h3>
</header>

<div class="page-content">
    <h1>Mathematics Subject</h1>
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
                <!-- Student rows will be populated here by JavaScript -->
            </tbody>
        </table>
    </div>
</div>
</body>
</html>