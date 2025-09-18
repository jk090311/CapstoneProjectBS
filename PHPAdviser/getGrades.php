<?php include "teacherNavbar.php"?>
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "educguarddb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle grade submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['student_id']) && isset($data['subject_id']) && isset($data['grades'])) {
        $student_id = $data['student_id'];
        $subject_id = $data['subject_id'];
        $grades = $data['grades'];
        
        $stmt = $conn->prepare("INSERT INTO grades (student_id, subject_id, quarter_id, grade) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE grade = ?");
        
        $success = true;
        $conn->begin_transaction();
        
        try {
            foreach ($grades as $quarter => $grade) {
                if (!empty($grade)) {
                    $stmt->bind_param("iiiii", $student_id, $subject_id, $quarter, $grade, $grade);
                    if (!$stmt->execute()) {
                        throw new Exception("Error saving grade for quarter $quarter");
                    }
                }
            }
            $conn->commit();
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
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

if (!isset($_GET['subject_id']) || !isset($_GET['subject_name'])) {
    die("Subject ID and name are required");
}

$subject_id = $_GET['subject_id'];
$subject_name = htmlspecialchars($_GET['subject_name']);

// Fetch students and their grades
$sql = "SELECT s.student_id, s.first_name, s.middle_name, s.last_name,
        MAX(CASE WHEN g.quarter_id = 1 THEN g.grade END) as q1,
        MAX(CASE WHEN g.quarter_id = 2 THEN g.grade END) as q2,
        MAX(CASE WHEN g.quarter_id = 3 THEN g.grade END) as q3,
        MAX(CASE WHEN g.quarter_id = 4 THEN g.grade END) as q4
        FROM students s
        LEFT JOIN grades g ON s.student_id = g.student_id AND g.subject_id = ?
        WHERE s.section = ?
        GROUP BY s.student_id, s.first_name, s.middle_name, s.last_name
        ORDER BY s.last_name, s.first_name";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $subject_id, $adviser_section);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
    .card-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    .student-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0 auto;
    }

    .student-table th,
    .student-table td {
        padding: 12px;
        border: 1px solid #ddd;
    }

    .student-table thead th {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    .grade-input {
        width: 70px;
        padding: 5px;
        text-align: center;
    }

    .action-buttons button {
        margin: 2px;
        padding: 5px 10px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }

    .edit-grade-btn {
        background-color: #ffc107;
    }

    .submit-grades-btn {
        background-color: #28a745;
        color: white;
    }

    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 4px;
        color: white;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .notification.success {
        background-color: #28a745;
    }

    .notification.error {
        background-color: #dc3545;
    }

    .notification.show {
        opacity: 1;
    }

    .subject-header {
        text-align: center;
        margin: 20px 0;
        padding: 10px;
    }
    
    .subject-header h2 {
        color: #333;
        font-size: 24px;
        margin: 0;
    }

    .back-button {
        position: fixed;
        top: 80px;  /* Increased from 20px to move it below the navbar */
        left: 40px;
        padding: 12px 24px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 1000;  /* Ensures the button stays on top */
    }

    .back-button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }

    .grade-pass {
        color: #28a745;
        font-weight: bold;
    }

    .grade-fail {
        color: #dc3545;
        font-weight: bold;
    }
</style>

<a href="reportSystem.php" class="back-button">← Back to Subjects</a>

<div class="subject-header">
    <h2><?php echo $subject_name; ?></h2>
</div>

<div class="card-container">
    <table class="student-table">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Quarter 1</th>
                <th>Quarter 2</th>
                <th>Quarter 3</th>
                <th>Quarter 4</th>
                <th>Final Grade</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']); ?></td>
                        <?php for ($q = 1; $q <= 4; $q++): ?>
                            <td>
                                <input type="number"
                                    class="grade-input"
                                    data-student-id="<?php echo $row['student_id']; ?>"
                                    data-quarter="<?php echo $q; ?>"
                                    value="<?php echo $row['q' . $q]; ?>"
                                    min="0"
                                    max="100"
                                    <?php echo !empty($row['q' . $q]) ? 'disabled' : ''; ?> />
                            </td>
                        <?php endfor; ?>
                        <td>
                            <span class="final-grade">
                                <?php
                                if (isset($row['q1']) && isset($row['q2']) && isset($row['q3']) && isset($row['q4'])) {
                                    $final_grade = round(($row['q1'] + $row['q2'] + $row['q3'] + $row['q4']) / 4);
                                    echo "<span class='" . ($final_grade >= 75 ? 'grade-pass' : 'grade-fail') . "'>" . $final_grade . "</span>";
                                }
                                ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            if (isset($row['q1']) && isset($row['q2']) && isset($row['q3']) && isset($row['q4'])) {
                                $final_grade = round(($row['q1'] + $row['q2'] + $row['q3'] + $row['q4']) / 4);
                                echo $final_grade >= 75 ? "PASSED" : "FAILED";
                            } else {
                                echo "PENDING";
                            }
                            ?>
                        </td>
                        <td class="action-buttons">
                            <button class="edit-grade-btn" data-student-id="<?php echo $row['student_id']; ?>">Edit</button>
                            <button class="submit-grades-btn" 
                                    data-student-id="<?php echo $row['student_id']; ?>"
                                    data-subject-id="<?php echo $subject_id; ?>">Submit</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div id="notification" class="notification"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function showNotification(message, type) {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.className = `notification ${type}`;
        notification.classList.add('show');
        
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }

    // Function to calculate and update final grade
    function updateFinalGrade(row) {
        const gradeInputs = row.querySelectorAll('.grade-input');
        const finalGradeSpan = row.querySelector('.final-grade');
        let sum = 0;
        let count = 0;
        
        gradeInputs.forEach(input => {
            if (input.value) {
                sum += parseInt(input.value);
                count++;
            }
        });
        
        if (count > 0) {
            const average = Math.round(sum / count);
            finalGradeSpan.textContent = average;
        } else {
            finalGradeSpan.textContent = '';
        }
    }

    document.querySelectorAll('.submit-grades-btn').forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.dataset.studentId;
            const subjectId = this.dataset.subjectId;
            const row = this.closest('tr');
            const gradeInputs = row.querySelectorAll('.grade-input');
            
            const grades = {};
            gradeInputs.forEach(input => {
                const quarter = input.dataset.quarter;
                const grade = input.value;
                if (grade) {
                    grades[quarter] = grade;
                }
            });

            let isValid = true;
            Object.values(grades).forEach(grade => {
                if (grade < 0 || grade > 100) {
                    isValid = false;
                }
            });

            if (!isValid) {
                showNotification('Grades must be between 75 and 100', 'error');
                return;
            }

            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    student_id: studentId,
                    subject_id: subjectId,
                    grades: grades
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Grades submitted successfully', 'success');
                    
                    // Reload the page after a shorter delay (500ms)
                    setTimeout(() => {
                        window.location.reload(true); // Force reload from server
                    }, 500);
                } else {
                    showNotification('Error submitting grades: ' + (data.error || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                showNotification('Error submitting grades: ' + error, 'error');
            });
        });
    });

    // Add event listeners for edit buttons
    document.querySelectorAll('.edit-grade-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const gradeInputs = row.querySelectorAll('.grade-input');
            
            // Enable all grade inputs in the row
            gradeInputs.forEach(input => {
                input.disabled = false;
            });
            
            // Optionally, change the edit button appearance to show it's in edit mode
            this.textContent = 'Editing...';
            this.style.backgroundColor = '#dc3545';
            this.style.color = 'white';
        });
    });

    // Add input event listeners to all grade inputs
    document.querySelectorAll('.grade-input').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            updateFinalGrade(row);
            
            // Validate input range (75-100)
            const value = parseInt(this.value);
            if (value < 0 || value > 100) {
                this.style.backgroundColor = '#ffebee';
                showNotification('Grade must be between 0 and 100', 'error');
            } else {
                this.style.backgroundColor = 'white';
            }
        });
    });
});
</script>

<?php
$stmt->close();
$conn->close();
?>