<?php
include "studentNavbar.php";
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Not logged in, redirect to login page
    error_log("User not logged in, redirecting to login");
    header("Location: ../PHPmain/index.php");
    exit();
}


$required_role = "student"; 
if ($_SESSION['user_role'] != $required_role) {
    
    if ($_SESSION['user_role'] == "admin") {
        header("Location: ../PHPAdmin/dashboardAdmin.php");
    } else if ($_SESSION['user_role'] == "adviser") {
        header("Location: ../PHPStudent/dashboardStudent.php");
    }
    exit();
}
// Database connection 
$conn = new mysqli("localhost", "root", "", "educguarddb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student ID from session
$student_id = $_SESSION['student_id'];

// Replace the existing SQL query with this corrected version
$sql = "SELECT 
    s.subject_id, 
    s.subject_name,
    MAX(CASE WHEN g.quarter_id = 1 THEN g.grade END) as q1,
    MAX(CASE WHEN g.quarter_id = 2 THEN g.grade END) as q2,
    MAX(CASE WHEN g.quarter_id = 3 THEN g.grade END) as q3,
    MAX(CASE WHEN g.quarter_id = 4 THEN g.grade END) as q4
FROM 
    subjects s
LEFT JOIN 
    grades g ON s.subject_id = g.subject_id AND g.student_id = ?
WHERE 
    g.student_id = ? OR g.student_id IS NULL
GROUP BY 
    s.subject_id, s.subject_name
ORDER BY 
    s.subject_name ASC";

// Update the bind_param to include two parameters

$stmt = $conn->prepare($sql);
$prepared_ok = $stmt !== false;
$bind_ok = $stmt->bind_param("ii", $student_id, $student_id);
$executed_ok = $stmt->execute();

echo "<script>";
echo "console.log('Debug: Student ID = ' + " . json_encode($student_id) . ");";
echo "console.log('Debug: SQL Prepared Successfully: ' + " . json_encode($prepared_ok ? 'Yes' : 'No') . ");";
echo "console.log('Debug: SQL Error (prepare) = ' + " . json_encode($conn->error) . ");";
echo "console.log('Debug: Execution Success: ' + " . json_encode($executed_ok ? 'Yes' : 'No') . ");";
echo "console.log('Debug: SQL Error (execute) = ' + " . json_encode($stmt->error) . ");";


// Optional: Final SQL
$debug_sql = str_replace('?', '%s', $sql);
$debug_sql = vsprintf($debug_sql, array_map(function ($v) use ($conn) {
    return "'" . $conn->real_escape_string($v) . "'";
}, [$student_id, $student_id]));
echo "console.log('Debug: Final SQL = ' + " . json_encode($debug_sql) . ");";
echo "</script>";


$result = $stmt->get_result();

// Add debug output
echo "<!-- Debug: Student ID = " . htmlspecialchars($student_id) . " -->";
echo "<!-- Debug: SQL Error = " . $conn->error . " -->";
if ($result) {
    echo "<!-- Debug: Number of rows = " . $result->num_rows . " -->";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grades</title>
    <style>
        /* Table styles with white background for all cells */
        table {
            width: 80%;
            /* Reduced from 100% to allow centering */
            border-collapse: collapse;
            margin: 100px auto 20px auto;
            /* Added top margin to move table down below navbar */
            background-color: white;
            /* Set the entire table background to white */
            position: relative;
            /* Added positioning context */
            z-index: 10;
            /* Ensure table appears above other elements */
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            background-color: white;
            /* Explicitly set all cells to white */
        }

        th {
            font-weight: bold;
            background-color: white;
            /* Override the gray background for headers */
        }

        /* Remove the striped row styling that was incomplete */
        /* The tr:nth-child() selector was empty and not doing anything */

        tr:hover {
            background-color: #f0f0f0;
        }

        .subject-name {
            text-align: left;
            font-weight: bold;
            background-color: white;
            /* Ensure subject names have white background */
        }

        /* Add this to ensure any existing backgrounds are covered */
        tr,
        tbody,
        thead {
            background-color: white !important;
        }

        /* This will help override any inline styles or high-specificity selectors */
        table tr td,
        table tr th,
        table tbody tr td,
        table tbody tr th,
        table thead tr td,
        table thead tr th {
            background-color: white !important;
        }

        /* Add style for final grade column */
        .final-grade {
            font-weight: bold;
            background-color: white !important;
        }

        .gwa-row {
            background-color: #f8f8f8 !important;
            font-weight: bold;
        }

        .gwa-label {
            text-align: right;
            padding-right: 20px;
        }

        .gwa-value {
            font-size: 1.1em;
            color: #2c3e50;
        }

        .grade-pass {
            color: #28a745;
        }

        .grade-fail {
            color: #dc3545;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>First Quarter</th>
                <th>Second Quarter</th>
                <th>Third Quarter</th>
                <th>Fourth Quarter</th>
                <th>Final Grade</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $total_grades = 0;
                $subject_count = 0;

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='subject-name'>";
                    echo htmlspecialchars($row['subject_name']) . "</td>";

                    // Display quarterly grades
                    for ($q = 1; $q <= 4; $q++) {
                        $grade = $row["q$q"];
                        $grade_class = $grade >= 75 ? 'grade-pass' : 'grade-fail';
                        echo "<td class='$grade_class'>" . ($grade ? number_format($grade, 2) : '-') . "</td>";
                    }

                    // Calculate and display final grade
                    if ($row['q1'] && $row['q2'] && $row['q3'] && $row['q4']) {
                        $final_grade = round(($row['q1'] + $row['q2'] + $row['q3'] + $row['q4']) / 4);
                        $total_grades += $final_grade;
                        $subject_count++;
                        $grade_class = $final_grade >= 75 ? 'grade-pass' : 'grade-fail';
                        echo "<td class='final-grade $grade_class'>" . $final_grade . "</td>";
                        echo "<td>" . ($final_grade >= 75 ? 'PASSED' : 'FAILED') . "</td>";
                    } else {
                        echo "<td class='final-grade'>-</td>";
                        echo "<td>PENDING</td>";
                    }
                    echo "</tr>";
                }

                // Calculate GWA by dividing sum of final grades by total number of subjects
                $total_subjects = $result->num_rows; // Get total number of subjects
                $final_total = 0;
                
                // Get the sum of all final grades
                $result->data_seek(0); // Reset result pointer to start
                while ($row = $result->fetch_assoc()) {
                    if ($row['q1'] && $row['q2'] && $row['q3'] && $row['q4']) {
                        $final_grade = round(($row['q1'] + $row['q2'] + $row['q3'] + $row['q4']) / 4);
                        $final_total += $final_grade; // Add each final grade to the total
                    }
                }
                
                if ($final_total > 0) {
                    $gwa = round($final_total / $total_subjects, 2); // Round to 2 decimal places
                    echo "<tr class='gwa-row'>";
                    echo "<td class='gwa-label'>General Weighted Average</td>";
                    // Add empty cells for quarters
                    echo "<td></td><td></td><td></td><td></td>";
                    // Display GWA in the Final Grade column
                    $gwa_class = $gwa >= 75 ? 'grade-pass' : 'grade-fail';
                    echo "<td class='gwa-value $gwa_class'>" . $gwa . "</td>";
                    
                    // Check if all subjects have final grades
                    $all_subjects_completed = true;
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()) {
                        if (!($row['q1'] && $row['q2'] && $row['q3'] && $row['q4'])) {
                            $all_subjects_completed = false;
                            break;
                        }
                    }
                    
                    // Only show remarks if all subjects are completed
                    if ($all_subjects_completed) {
                        echo "<td>" . ($gwa >= 75 ? 'PASSED' : 'FAILED') . "</td>";
                    } else {
                        echo "<td></td>"; // Empty cell if not all subjects are completed
                    }
                    echo "</tr>";
                    
                    // Add new row with academic honors only if all subjects are completed
                    echo "<tr>";
                    echo "<td colspan='6'></td>";
                    if ($all_subjects_completed) {
                        // Determine academic honor based on GWA
                        $academic_honor = "";
                        if ($gwa >= 98 && $gwa <= 100) {
                            $academic_honor = "WITH HIGHEST HONOR";
                        } elseif ($gwa >= 94 && $gwa <= 97) {
                            $academic_honor = "WITH HIGH HONOR";
                        } elseif ($gwa >= 90 && $gwa <= 93) {
                            $academic_honor = "WITH HONOR";
                        } elseif ($gwa >= 75 && $gwa <= 89) {
                            $academic_honor = "PASSED";
                        } else {
                            $academic_honor = "FAILED";
                        }
                        echo "<td>" . $academic_honor . "</td>";
                    } else {
                        echo "<td></td>"; // Empty cell if not all subjects are completed
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No grades available</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>





