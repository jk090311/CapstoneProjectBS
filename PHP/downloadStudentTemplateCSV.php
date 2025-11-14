<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: ../PHPmain/index.php");
    exit();
}

// Set headers for CSV file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="student_batch_upload_template.csv"');
header('Cache-Control: max-age=0');

// Output CSV header
$output = fopen('php://output', 'w');

// Add BOM for Excel UTF-8 support
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// CSV Headers
fputcsv($output, [
    'First Name',
    'Middle Name',
    'Last Name',
    'Birth Date (YYYY-MM-DD)',
    'Sex (Male/Female)',
    'Contact Number',
    'Address',
    'Parent/Guardian Name',
    'Parent/Guardian Number',
    'Parent/Guardian Email',
    'LRN',
    'Email',
    'Username',
    'Password',
    'Status (active/inactive)'
]);

// Sample data row
fputcsv($output, [
    'Juan',
    'Reyes',
    'Dela Cruz',
    '2010-05-15',
    'Male',
    '09123456789',
    '123 Main St, Manila',
    'Maria Dela Cruz',
    '09187654321',
    'maria.delacruz@email.com',
    '123456789012',
    'juan.delacruz@student.edu',
    'jdelacruz',
    'Password123',
    'active'
]);

fclose($output);
exit;
?>




