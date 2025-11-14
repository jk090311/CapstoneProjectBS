<?php
// getGrades.php
// Clean implementation: supports ?ajax=1 (fragment) and POST (JSON batch or single-grade)

$isAjax = isset($_GET['ajax']) && $_GET['ajax'] === '1';

// Ensure session exists (teacherNavbar may start a session too)
if (session_status() == PHP_SESSION_NONE) session_start();

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

if (!$isAjax) {
    // include full navbar only for full page requests
    include 'subjectteacherNavbar.php';
}

$conn = new mysqli('localhost', 'root', '', 'educguarddb');
if ($conn->connect_error) die('Connection failed: ' . $conn->connect_error);

// Handle POST submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!$data || !is_array($data)) $data = $_POST;

    // Batch JSON: { student_id, subject_id, grades: { quarter: grade, ... } }
    if (isset($data['student_id']) && isset($data['subject_id']) && isset($data['grades']) && is_array($data['grades'])) {
        $student_id = (int)$data['student_id'];
        $subject_id = (int)$data['subject_id'];
        $grades = $data['grades'];

        $stmt = $conn->prepare("INSERT INTO grades (student_id, subject_id, quarter_id, grade) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE grade = ?");
        $conn->begin_transaction();
        try {
            foreach ($grades as $quarter => $grade) {
                $q = (int)$quarter;
                $g = (int)$grade;
                $stmt->bind_param('iiiii', $student_id, $subject_id, $q, $g, $g);
                if (!$stmt->execute()) throw new Exception('DB error');
            }
            $conn->commit();
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            $stmt->close();
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            if (isset($stmt)) $stmt->close();
            exit;
        }
    }

    // Legacy single-grade: student_id, subject_id, quarter, grade
    if (isset($data['student_id']) && isset($data['subject_id']) && isset($data['quarter']) && isset($data['grade'])) {
        $student_id = (int)$data['student_id'];
        $subject_id = (int)$data['subject_id'];
        $quarter = (int)$data['quarter'];
        $grade = (int)$data['grade'];

        $stmt2 = $conn->prepare("INSERT INTO grades (student_id, subject_id, quarter_id, grade) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE grade = ?");
        $stmt2->bind_param('iiiii', $student_id, $subject_id, $quarter, $grade, $grade);
        $ok = $stmt2->execute();
        header('Content-Type: application/json');
        echo json_encode(['success' => (bool)$ok]);
        $stmt2->close();
        exit;
    }
}

// Validate required GET params for rendering
if (!isset($_GET['subject_id']) || !isset($_GET['subject_name'])) {
    if ($isAjax) echo 'Subject ID and name are required';
    else echo '<p>Subject ID and name are required</p>';
    exit;
}

$subject_id = (int)$_GET['subject_id'];
$subject_name = htmlspecialchars($_GET['subject_name']);

// Get adviser's section if available
$adviser_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;
$adviser_section = null;
if ($adviser_email) {
    $q = $conn->prepare("SELECT adviserSection FROM advisers WHERE adviserEmailAddress = ?");
    $q->bind_param('s', $adviser_email);
    $q->execute();
    $res = $q->get_result();
    if ($res && $res->num_rows) $adviser_section = $res->fetch_assoc()['adviserSection'];
    $q->close();
}

// Fetch students and their grades
// Pagination parameters
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = isset($_GET['per_page']) ? max(5, (int)$_GET['per_page']) : 10;
$offset = ($page - 1) * $per_page;

// Get section name from URL
$section_name = isset($_GET['section']) ? $_GET['section'] : '';

$sql = "SELECT s.student_id, s.first_name, s.middle_name, s.last_name,
        MAX(CASE WHEN g.quarter_id = 1 THEN g.grade END) as q1,
        MAX(CASE WHEN g.quarter_id = 2 THEN g.grade END) as q2,
        MAX(CASE WHEN g.quarter_id = 3 THEN g.grade END) as q3,
        MAX(CASE WHEN g.quarter_id = 4 THEN g.grade END) as q4
        FROM students s
        LEFT JOIN grades g ON s.student_id = g.student_id AND g.subject_id = ?
        WHERE s.section = ?
        GROUP BY s.student_id, s.first_name, s.middle_name, s.last_name, s.section
        ORDER BY s.section, s.last_name, s.first_name
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('isii', $subject_id, $section_name, $per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// compute total rows for pagination (without limit)
$count_sql = "SELECT COUNT(DISTINCT s.student_id) as total 
             FROM students s 
             LEFT JOIN grades g ON s.student_id = g.student_id AND g.subject_id = ? 
             WHERE s.section = ?";
$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param('is', $subject_id, $section_name);
$count_stmt->execute();
$count_res = $count_stmt->get_result();
$total_rows = ($count_res && $count_res->num_rows) ? (int)$count_res->fetch_assoc()['total'] : 0;
$count_stmt->close();
$total_pages = $per_page > 0 ? (int)ceil($total_rows / $per_page) : 1;

// Render: full page (with styles and JS) when not ajax, or only the fragment when ajax
?>
<style>
    .card-container {
        max-width: 1180px;
        margin: 18px auto;
        padding: 12px;
        background: #ffffff;
        border-radius: 6px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        color: #222;
    }

    .student-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        color: #222
    }

    .student-table th,
    .student-table td {
        padding: 8px 10px;
        border-bottom: 1px solid #e6eef2;
        vertical-align: middle
    }

    .student-table thead th {
        background: #fff;
        font-weight: 700;
        color: #222;
        border-bottom: 2px solid #e9eef2
    }

    .student-table tbody tr:nth-child(odd) {
        background: #fff
    }

    .student-table tbody tr:nth-child(even) {
        background: #fbfcfd
    }

    .grade-input {
        width: 84px;
        padding: 8px;
        text-align: center;
        border: 1px solid #d0d7de;
        border-radius: 6px;
        background: #fff;
        font-size: 14px
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: center
    }

    .edit-grade-btn {
        background: #f0ad4e;
        border: none;
        padding: 6px 10px;
        border-radius: 4px;
        color: #fff;
        font-weight: 600;
        cursor: pointer
    }

    .submit-grades-btn {
        background: #28a745;
        color: #fff;
        border: none;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer
    }

    .edit-grade-btn.small,
    .submit-grades-btn.small {
        padding: 4px 8px;
        font-size: 12px
    }

    .grade-pass {
        color: #28a745;
        font-weight: 700
    }

    .grade-fail {
        color: #dc3545;
        font-weight: 700
    }

    #notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 18px;
        border-radius: 4px;
        color: #fff;
        display: none;
        z-index: 2000
    }

    #notification.show {
        display: block
    }

    #notification.success {
        background: #28a745
    }

    #notification.error {
        background: #dc3545
    }

    /* Pagination controls */
    .pagination {
        display: flex;
        gap: 8px;
        justify-content: center;
        margin-top: 12px
    }

    .pagination .pagination-btn {
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid #d7e6e6;
        background: #fff;
        cursor: pointer
    }

    .pagination .pagination-btn[style] {
        background: #e6f7f6;
        font-weight: 800
    }
</style>

<?php if (!$isAjax): ?>
    <div class="subject-header">
        <h2><?php echo $subject_name ?></h2>
        <p>Section: <?php echo htmlspecialchars($section_name); ?></p>
    </div>
<?php endif; ?>

<div class="card-container">
    <?php
    // Table output (fragment or full page)
    ?>
    <table class="student-table">
        <thead>
            <tr>
                <th>Student Names</th>
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
            <?php if ($result && $result->num_rows > 0): while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']); ?></td>
                        <?php for ($q = 1; $q <= 4; $q++): ?>
                            <td>
                                <?php $val = isset($row['q' . $q]) && $row['q' . $q] !== null ? (int)round($row['q' . $q]) : ''; ?>
                                <input type="number" step="1" class="grade-input" data-student-id="<?php echo $row['student_id'] ?>" data-quarter="<?php echo $q ?>" value="<?php echo $val ?>" min="60" max="100" <?php echo $val !== '' ? 'disabled' : '' ?> />
                            </td>
                        <?php endfor; ?>
                        <td><span class="final-grade"><?php if (isset($row['q1'], $row['q2'], $row['q3'], $row['q4'])) {
                                                            $final = (int)round(($row['q1'] + $row['q2'] + $row['q3'] + $row['q4']) / 4);
                                                            echo '<span class="' . ($final >= 75 ? 'grade-pass' : 'grade-fail') . '">' . $final . '</span>';
                                                        } ?></span></td>
                        <td><?php if (isset($row['q1'], $row['q2'], $row['q3'], $row['q4'])) {
                                echo $final >= 75 ? 'PASSED' : 'FAILED';
                            } else echo 'PENDING'; ?></td>
                        <td class="action-buttons">
                            <button class="edit-grade-btn" data-student-id="<?php echo $row['student_id'] ?>">Edit</button>
                            <button class="submit-grades-btn" data-student-id="<?php echo $row['student_id'] ?>" data-subject-id="<?php echo $subject_id ?>">Submit</button>
                        </td>
                    </tr>
                <?php endwhile;
            else: ?>
                <tr>
                    <td colspan="8">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($total_pages > 1): ?>
        <div class="pagination" style="display:flex; gap:8px; justify-content:center; margin-top:12px;">
            <?php if ($page > 1): ?>
                <button class="pagination-btn" data-page="<?php echo $page - 1 ?>">← Prev</button>
            <?php endif; ?>

            <?php
            // show a small window of pages
            $start = max(1, $page - 2);
            $end = min($total_pages, $page + 2);
            for ($p = $start; $p <= $end; $p++):
            ?>
                <button class="pagination-btn" data-page="<?php echo $p ?>" <?php echo $p === $page ? 'style="font-weight:800; background:#e6f7f6;"' : '' ?>><?php echo $p ?></button>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <button class="pagination-btn" data-page="<?php echo $page + 1 ?>">Next →</button>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!$isAjax): ?>
</div> <!-- .card-container -->
<div id="notification" class="notification"></div>
<script>
    (function() {
        function showNotification(msg, type) {
            const el = document.getElementById('notification');
            if (!el) return;
            el.textContent = msg;
            el.className = 'notification show ' + type;
            setTimeout(() => el.className = 'notification', 3000);
        }

        function updateFinalGrade(row) {
            const inputs = row.querySelectorAll('.grade-input');
            let sum = 0,
                count = 0;
            inputs.forEach(i => {
                if (i.value) {
                    sum += parseInt(i.value);
                    count++;
                }
            });
            const target = row.querySelector('.final-grade');
            if (target) target.textContent = count ? Math.round(sum / count) : '';
        }

        document.querySelectorAll('.edit-grade-btn').forEach(btn => btn.addEventListener('click', function() {
            const row = this.closest('tr');
            row.querySelectorAll('.grade-input').forEach(i => i.disabled = false);
            this.textContent = 'Editing...';
            this.style.backgroundColor = '#dc3545';
            this.style.color = '#fff';
        }));

        document.querySelectorAll('.grade-input').forEach(i => i.addEventListener('input', function() {
            const row = this.closest('tr');
            updateFinalGrade(row);
            const v = parseInt(this.value);
            if (v < 60 || v > 100) {
                this.style.backgroundColor = '#ffebee';
                showNotification('Grade must be between 60 and 100', 'error');
            } else this.style.backgroundColor = '#fff';
        }));

        document.querySelectorAll('.submit-grades-btn').forEach(btn => btn.addEventListener('click', function() {
            const studentId = this.dataset.studentId;
            const subjectId = this.dataset.subjectId;
            const row = this.closest('tr');
            const inputs = row.querySelectorAll('.grade-input');
            const grades = {};
            inputs.forEach(i => {
                if (i.value) grades[i.dataset.quarter] = i.value;
            });
            let ok = true;
            Object.values(grades).forEach(g => {
                if (g < 60 || g > 100) ok = false;
            });
            if (!ok) {
                showNotification('Grades must be between 0 and 100', 'error');
                return;
            }
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    student_id: studentId,
                    subject_id: subjectId,
                    grades: grades
                })
            }).then(r => r.json()).then(j => {
                if (j.success) {
                    showNotification('Grades submitted', 'success');
                    setTimeout(() => location.reload(true), 500);
                } else {
                    // showNotification('Error: ' + (j.error || 'unknown'), 'error');
                    console.error('Error: ' + (j.error || 'unknown'));
                    window.location.reload(true);
                }
            }).catch(e => {
                // showNotification('Error: ' + e, 'error');
                console.error('Error: ' + e);
                window.location.reload(true);
            }    
        );
        }));

        // Add click handlers for pagination buttons
        document.querySelectorAll('.pagination-btn').forEach(btn => btn.addEventListener('click', function(e) {
            e.preventDefault();
            const page = this.dataset.page;
            const url = new URL(window.location.href);
            url.searchParams.set('page', page);
            window.location.href = url.toString();
        }));
    })();
</script>
<?php endif; ?>

<?php
$stmt->close();
$conn->close();
?>





