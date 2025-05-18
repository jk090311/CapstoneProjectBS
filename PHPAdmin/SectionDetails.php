<?php include "adminNavbar.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Students</title>
    <link rel="stylesheet" href="../CSS/Teacher/adviserStudents.css">
    <link rel="stylesheet" href="../CSS/Admin/sectionDetailss.css"> <!-- Updated CSS file -->
</head>

<body>
    <div class="container">
        <h1>Student List</h1>
        <?php
        // Database connection
        include "../PHP/dbconnection.php"; // Ensure this file contains your DB connection setup

        // Handle RFID insertion
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['rfid_number'])) {
            $student_id = intval($_POST['student_id']);
            $rfid_number = htmlspecialchars($_POST['rfid_number']);

            $update_query = "UPDATE students SET rfid_number = ? WHERE id = ?";
            if ($stmt = $conn->prepare($update_query)) {
                $stmt->bind_param("si", $rfid_number, $student_id);
                if ($stmt->execute()) {
                    echo "<p class='success'>RFID number updated successfully!</p>";
                } else {
                    echo "<p class='error'>Error updating RFID number: " . htmlspecialchars($stmt->error) . "</p>";
                }
                $stmt->close();
            } else {
                echo "<p class='error'>Error preparing the query: " . htmlspecialchars($conn->error) . "</p>";
            }
        }

        // Get the section ID from the request (e.g., via GET or POST)
        $section_id = isset($_GET['section_id']) ? intval($_GET['section_id']) : 0;

        if ($section_id > 0) {
            // Query to get the adviser and students in the section
            $query = "
                SELECT 
                    A.adviserFullName, 
                    A.advisersection, 
                    A.adviserGrlvl, 
                    B.section_name, 
                    B.section_grade_level, 
                    C.student_id AS student_id,
                    C.section, 
                    C.first_name,
                    C.middle_name,
                    C.last_name,
                    C.sex,
                    C.rfid_number
                FROM advisers A
                INNER JOIN class_section B ON A.advisersection = B.section_name
                INNER JOIN students C ON C.section = B.section_name
                WHERE B.section_id = ? 
                ORDER BY C.sex ASC, C.last_name ASC";

            // Prepare and execute the query
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("i", $section_id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Fetch and display the results
                if ($result->num_rows > 0) {
                    // Display adviser information
                    $row = $result->fetch_assoc();
                    echo "<div class='adviser-info'>";
                    echo "<h2>Adviser: " . htmlspecialchars($row['adviserFullName']) . "</h2>";
                    echo "</div>";

                    // Reset result pointer
                    $result->data_seek(0);

                    // Separate male and female students
                    $male_students = [];
                    $female_students = [];
                    while ($row = $result->fetch_assoc()) {
                        if ($row['sex'] === 'Male') {
                            $male_students[] = $row;
                        } else if ($row['sex'] === 'Female') {
                            $female_students[] = $row;
                        }
                    }

                    // Display male students
                    echo "<h3>Male Students:</h3>";
                    if (count($male_students) > 0) {
                        echo "<table>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>#</th>";
                        echo "<th>Last Name</th>";
                        echo "<th>First Name</th>";
                        echo "<th>Middle Name</th>";
                        echo "<th>RFID Number</th>";
                        echo "<th>Action</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        $counter = 1;
                        foreach ($male_students as $student) {
                            echo "<tr>";
                            echo "<td>" . $counter++ . "</td>";
                            echo "<td>" . htmlspecialchars($student['last_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($student['first_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($student['middle_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($student['rfid_number']) . "</td>";
                            echo "<td>
                                <form method='POST'>
                                    <input type='hidden' name='student_id' value='" . $student['student_id'] . "'>
                                    <input type='text' name='rfid_number' placeholder='Enter RFID'>
                                    <button type='submit'>Update</button>
                                </form>
                              </td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<p class='no-data'>No male students found.</p>";
                    }

                    // Display female students
                    echo "<h3>Female Students:</h3>";
                    if (count($female_students) > 0) {
                        echo "<table>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>#</th>";
                        echo "<th>Last Name</th>";
                        echo "<th>First Name</th>";
                        echo "<th>Middle Name</th>";
                        echo "<th>RFID Number</th>";
                        echo "<th>Action</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        $counter = 1;
                        foreach ($female_students as $student) {
                            echo "<tr>";
                            echo "<td>" . $counter++ . "</td>";
                            echo "<td>" . htmlspecialchars($student['last_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($student['first_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($student['middle_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($student['rfid_number']) . "</td>";
                            echo "<td>
                                <form method='POST'>
                                    <input type='hidden' name='student_id' value='" . $student['student_id'] . "'>
                                    <input type='text' name='rfid_number' placeholder='Enter RFID'>
                                    <button type='submit'>Update</button>
                                </form>
                              </td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<p class='no-data'>No female students found.</p>";
                    }
                } else {
                    echo "<p class='no-data'>No students found for this section.</p>";
                }
                $stmt->close();
            } else {
                echo "<p class='error'>Error preparing the query: " . htmlspecialchars($conn->error) . "</p>";
            }
        } else {
            echo "<p class='error'>Invalid section ID.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>

</html>