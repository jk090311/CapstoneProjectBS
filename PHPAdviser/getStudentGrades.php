<?php
// getStudentGrades.php
// Returns an HTML fragment for a student's grades across subjects

$isAjax = isset($_GET['ajax']) && $_GET['ajax'] === '1';
if (session_status() == PHP_SESSION_NONE) session_start();

$conn = new mysqli('localhost', 'root', '', 'educguarddb');
if ($conn->connect_error) { if ($isAjax) echo 'DB error'; else die('Connection failed: ' . $conn->connect_error); }

// Accept either student_id (numeric) or lrn (string). Prefer LRN search from UI.
if (isset($_GET['lrn']) && $_GET['lrn'] !== '') {
    $provided_lrn = trim($_GET['lrn']);
    // Resolve LRN to numeric student_id
    $stmt = $conn->prepare('SELECT student_id FROM students WHERE lrn = ? LIMIT 1');
    $stmt->bind_param('s', $provided_lrn);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $student_id = (int) $res->fetch_assoc()['student_id'];
    } else {
        echo '<p>No student found with that LRN.</p>';
        exit;
    }
    $stmt->close();
} elseif (isset($_GET['student_id'])) {
    $student_id = (int) $_GET['student_id'];
} else {
    die($isAjax ? 'No student identifier provided' : 'Error: Student identifier required');
}

// Get student name first
$student_query = "SELECT CONCAT(first_name, ' ', last_name) as student_name FROM students WHERE student_id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param('s', $student_id);
$stmt->execute();
$student_result = $stmt->get_result();
$student_name = ($student_result && $student_result->num_rows > 0) ? $student_result->fetch_assoc()['student_name'] : 'Unknown Student';
$stmt->close();

// Use the improved query from gradeStudent.php
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

$student_id = (int) $student_id;

// Fetch student
$stmt = $conn->prepare('SELECT first_name, middle_name, last_name, lrn FROM students WHERE student_id = ? LIMIT 1');
$stmt->bind_param('i', $student_id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    echo '<p>No student found with that ID.</p>';
    exit;
}
$stu = $res->fetch_assoc();
$fullName = htmlspecialchars(trim($stu['first_name'].' '.$stu['middle_name'].' '.$stu['last_name']));
$stmt->close();

// Fetch subjects and grades for this student
$sql = "SELECT subj.subject_id, subj.subject_name,
    MAX(CASE WHEN g.quarter_id = 1 THEN g.grade END) as q1,
    MAX(CASE WHEN g.quarter_id = 2 THEN g.grade END) as q2,
    MAX(CASE WHEN g.quarter_id = 3 THEN g.grade END) as q3,
    MAX(CASE WHEN g.quarter_id = 4 THEN g.grade END) as q4
    FROM subjects subj
    LEFT JOIN grades g ON subj.subject_id = g.subject_id AND g.student_id = ?
    GROUP BY subj.subject_id, subj.subject_name
    ORDER BY subj.subject_name ASC";

$stmt2 = $conn->prepare($sql);
$stmt2->bind_param('i', $student_id);
$stmt2->execute();
$result = $stmt2->get_result();

// Build fragment HTML
ob_start();
?>
<div class="card" style="padding:14px;">
    <h3 style="margin-top:0;">Student Grades</h3>
    <div style="margin-bottom:12px; display:flex; justify-content:space-between; align-items:center;"><div><strong>Student:</strong> <?php echo $fullName?> (LRN: <?php echo htmlspecialchars($stu['lrn'] ?? '') ?>)</div><div><button id="exportStudentBtn" data-student-id="<?php echo $student_id?>" data-student-lrn="<?php echo htmlspecialchars($stu['lrn'] ?? '')?>" style="padding:8px 12px; background:#0f7a8a; color:#fff; border-radius:6px; border:none; cursor:pointer;">Export to Excel</button></div></div>
    <div style="overflow:auto;">
    <table class="student-grades-table" style="width:100%; border-collapse:collapse;">
        <thead>
            <tr>
                <th style="text-align:left;padding:10px;border-bottom:1px solid #e6eef2;">Subject</th>
                <th style="padding:10px;border-bottom:1px solid #e6eef2;">First Quarter</th>
                <th style="padding:10px;border-bottom:1px solid #e6eef2;">Second Quarter</th>
                <th style="padding:10px;border-bottom:1px solid #e6eef2;">Third Quarter</th>
                <th style="padding:10px;border-bottom:1px solid #e6eef2;">Fourth Quarter</th>
                <th style="padding:10px;border-bottom:1px solid #e6eef2;">Final Grade</th>
                <th style="padding:10px;border-bottom:1px solid #e6eef2;">Remarks</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $finals = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $q1 = isset($row['q1']) && $row['q1'] !== null ? (int) round($row['q1']) : null;
                $q2 = isset($row['q2']) && $row['q2'] !== null ? (int) round($row['q2']) : null;
                $q3 = isset($row['q3']) && $row['q3'] !== null ? (int) round($row['q3']) : null;
                $q4 = isset($row['q4']) && $row['q4'] !== null ? (int) round($row['q4']) : null;
                $quarters = [$q1,$q2,$q3,$q4];
                $present = array_filter($quarters, function($v){ return $v !== null && $v !== ''; });
                $final = count($present) ? (int) round(array_sum($present)/count($present)) : null;
                if ($final !== null) $finals[] = $final;
                $remarks = ($final !== null) ? ($final >= 75 ? 'PASSED' : 'FAILED') : 'PENDING';
                echo '<tr>';
                echo '<td style="padding:10px;border-bottom:1px solid #f0f5f6;">'.htmlspecialchars($row['subject_name']).'</td>';
                echo '<td style="padding:10px;border-bottom:1px solid #f0f5f6; text-align:center;">'.($q1!==null?number_format($q1,0):'').'</td>';
                echo '<td style="padding:10px;border-bottom:1px solid #f0f5f6; text-align:center;">'.($q2!==null?number_format($q2,0):'').'</td>';
                echo '<td style="padding:10px;border-bottom:1px solid #f0f5f6; text-align:center;">'.($q3!==null?number_format($q3,0):'').'</td>';
                echo '<td style="padding:10px;border-bottom:1px solid #f0f5f6; text-align:center;">'.($q4!==null?number_format($q4,0):'').'</td>';
                echo '<td style="padding:10px;border-bottom:1px solid #f0f5f6; text-align:center; font-weight:700; color:#0b7a43;">'.($final!==null?number_format($final,0):'').'</td>';
                echo '<td style="padding:10px;border-bottom:1px solid #f0f5f6; text-align:center;">'.$remarks.'</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="7" style="padding:12px">No subject grades found.</td></tr>';
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td style="padding:10px; font-weight:700;">General Weighted Average</td>
                <td colspan="4"></td>
                <td style="padding:10px; font-weight:800; color:#0b7a43; text-align:center;"><?php echo (count($finals) ? (int) round(array_sum($finals)/count($finals)) : ''); ?></td>
                <td style="padding:10px; text-align:center; font-weight:700;"><?php echo (count($finals) ? ((int)round(array_sum($finals)/count($finals)) >= 75 ? 'PASSED' : 'FAILED') : ''); ?></td>
            </tr>
            <?php 
            if (count($finals)) {
                $gwa = (int)round(array_sum($finals)/count($finals));
                $academic_honor = "";
                if ($gwa >= 98 && $gwa <= 100) {
                    $academic_honor = "WITH HIGHEST HONOR";
                } elseif ($gwa >= 94 && $gwa <= 97) {
                    $academic_honor = "WITH HIGH HONOR";
                } elseif ($gwa >= 90 && $gwa <= 93) {
                    $academic_honor = "WITH HONOR";
                }
                if ($academic_honor): ?>
                <tr>
                    <td colspan="7" style="padding:10px; text-align:right; font-weight:700; color:#0b7a43;"><?php echo $academic_honor; ?></td>
                </tr>
                <?php endif;
            } ?>
        </tfoot>
    </table>
    </div>
</div>
<?php
$html = ob_get_clean();

echo $html;

$stmt2->close();
$conn->close();

?>