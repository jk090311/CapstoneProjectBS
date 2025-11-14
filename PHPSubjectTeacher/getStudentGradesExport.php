<?php
// Streams a CSV download for a student's grades (Excel-friendly)
// Use the central DB connection file used across the project
require_once __DIR__ . '/../PHP/dbconnection.php';

if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    http_response_code(400);
    echo "Missing student_id";
    exit;
}

$student_id = intval($_GET['student_id']);

// dbconnection.php creates a mysqli instance in $conn
$mysqli = $conn;

// Fetch student name (use column names from project)
$stmt = $mysqli->prepare("SELECT first_name, middle_name, last_name FROM students WHERE student_id = ? LIMIT 1");
$stmt->bind_param('i', $student_id);
$stmt->execute();
$res = $stmt->get_result();
$student = $res->fetch_assoc();
$stmt->close();

$fullName = $student ? trim($student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']) : 'Unknown';

// Query subjects and grades for this student (match table/column names)

$sql = "SELECT s.subject_id, s.subject_name,
    MAX(CASE WHEN g.quarter_id = 1 THEN g.grade END) AS q1,
    MAX(CASE WHEN g.quarter_id = 2 THEN g.grade END) AS q2,
    MAX(CASE WHEN g.quarter_id = 3 THEN g.grade END) AS q3,
    MAX(CASE WHEN g.quarter_id = 4 THEN g.grade END) AS q4,
    MAX(CASE WHEN g.quarter_id = 1 THEN g.remarks END) AS r1,
    MAX(CASE WHEN g.quarter_id = 2 THEN g.remarks END) AS r2,
    MAX(CASE WHEN g.quarter_id = 3 THEN g.remarks END) AS r3,
    MAX(CASE WHEN g.quarter_id = 4 THEN g.remarks END) AS r4
FROM subjects s
LEFT JOIN grades g ON g.subject_id = s.subject_id AND g.student_id = ?
GROUP BY s.subject_id, s.subject_name
ORDER BY s.subject_name ASC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Prepare CSV headers
$filename = 'student_grades_' . $student_id . '_' . date('Ymd') . '.csv';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
// UTF-8 BOM for Excel
fputs($output, "\xEF\xBB\xBF");

// Title rows
fputcsv($output, ["Student Name", $fullName]);
fputcsv($output, ["Student ID", $student_id]);
fputcsv($output, []);

// Column headers
fputcsv($output, ['Subject', 'Q1', 'Q2', 'Q3', 'Q4', 'Final Grade', 'Remarks']);

$gwaSum = 0;
$count = 0;
$finals = [];
while ($row = $result->fetch_assoc()) {
    // treat null quarters as missing
    $q1 = isset($row['q1']) && $row['q1'] !== null ? (int) round($row['q1']) : '';
    $q2 = isset($row['q2']) && $row['q2'] !== null ? (int) round($row['q2']) : '';
    $q3 = isset($row['q3']) && $row['q3'] !== null ? (int) round($row['q3']) : '';
    $q4 = isset($row['q4']) && $row['q4'] !== null ? (int) round($row['q4']) : '';

    $quarters = array_filter([$q1, $q2, $q3, $q4], function($v){ return $v !== '' && $v !== null; });
    $final = count($quarters) ? (int) round(array_sum($quarters)/count($quarters)) : '';
    $remarks = ($final !== '') ? ($final >= 75 ? 'PASSED' : 'FAILED') : 'PENDING';

    fputcsv($output, [$row['subject_name'], $q1, $q2, $q3, $q4, $final, $remarks]);

    if ($final !== '') {
        $finals[] = (int) $final;
    }
}


// GWA row
$gwa = count($finals) ? (int) round(array_sum($finals)/count($finals)) : '';
fputcsv($output, []);
fputcsv($output, ['GWA', $gwa]);

// WITH HONOR if applicable
if ($gwa !== '' && $gwa >= 90) {
    fputcsv($output, ['WITH HONOR']);
}

fclose($output);
exit;

?>




