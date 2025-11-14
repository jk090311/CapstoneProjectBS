<?php
header('Content-Type: application/json');

$connection = mysqli_connect("localhost", "root", "", "educguarddb");

if (!$connection) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit();
}

if (!isset($_GET['stID'])) {
    echo json_encode(['success' => false, 'error' => 'Missing teacher ID']);
    exit();
}

$stID = mysqli_real_escape_string($connection, $_GET['stID']);

// Query to get teacher info with all 3 possible subject assignments
$query = "SELECT 
    stGradelvl, stSection, stSubject,
    stGradelvl2, stSection2, stSubject2,
    stGradelvl3, stSection3, stSubject3
FROM subject_teachers
WHERE stID = '$stID'";

$result = mysqli_query($connection, $query);

if (!$result) {
    echo json_encode(['success' => false, 'error' => 'Query failed: ' . mysqli_error($connection)]);
    mysqli_close($connection);
    exit();
}

$assignments = [];

if ($row = mysqli_fetch_assoc($result)) {
    // Add first assignment if it exists
    if (!empty($row['stSubject'])) {
        $assignments[] = [
            'grade_level' => $row['stGradelvl'],
            'section_id' => $row['stSection'],
            'subject_id' => $row['stSubject']
        ];
    }
    
    // Add second assignment if it exists
    if (!empty($row['stSubject2'])) {
        $assignments[] = [
            'grade_level' => $row['stGradelvl2'],
            'section_id' => $row['stSection2'],
            'subject_id' => $row['stSubject2']
        ];
    }
    
    // Add third assignment if it exists
    if (!empty($row['stSubject3'])) {
        $assignments[] = [
            'grade_level' => $row['stGradelvl3'],
            'section_id' => $row['stSection3'],
            'subject_id' => $row['stSubject3']
        ];
    }
}

mysqli_close($connection);

echo json_encode([
    'success' => true,
    'assignments' => $assignments
]);
?>
