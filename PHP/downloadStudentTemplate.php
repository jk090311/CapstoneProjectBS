<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: ../PHPmain/index.php");
    exit();
}

// Set headers for Excel file download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="student_batch_upload_template.xls"');
header('Cache-Control: max-age=0');

// Create HTML table that Excel can read
echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
echo '<x:Name>Students</x:Name>';
echo '<x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet>';
echo '</x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
echo '</head>';
echo '<body>';
echo '<table border="1">';
echo '<thead>';
echo '<tr style="background-color: #4CAF50; color: white; font-weight: bold;">';
echo '<th>First Name *</th>';
echo '<th>Middle Name *</th>';
echo '<th>Last Name *</th>';
echo '<th>Birth Date * (YYYY-MM-DD)</th>';
echo '<th>Sex * (Male/Female)</th>';
echo '<th>Contact Number *</th>';
echo '<th>Address *</th>';
echo '<th>Parent/Guardian Name *</th>';
echo '<th>Parent/Guardian Number *</th>';
echo '<th>Parent/Guardian Email</th>';
echo '<th>LRN *</th>';
echo '<th>Email *</th>';
echo '<th>Username *</th>';
echo '<th>Password *</th>';
echo '<th>Status * (active/inactive)</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
// Add sample data row
echo '<tr>';
echo '<td>Juan</td>';
echo '<td>Reyes</td>';
echo '<td>Dela Cruz</td>';
echo '<td>2010-05-15</td>';
echo '<td>Male</td>';
echo '<td>09123456789</td>';
echo '<td>123 Main St, Manila</td>';
echo '<td>Maria Dela Cruz</td>';
echo '<td>09187654321</td>';
echo '<td>maria.delacruz@email.com</td>';
echo '<td>123456789012</td>';
echo '<td>juan.delacruz@student.edu</td>';
echo '<td>jdelacruz</td>';
echo '<td>Password123</td>';
echo '<td>active</td>';
echo '</tr>';
// Add empty rows for data entry
for ($i = 0; $i < 20; $i++) {
    echo '<tr>';
    for ($j = 0; $j < 15; $j++) {
        echo '<td></td>';
    }
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';
echo '</body>';
echo '</html>';
exit;
?>




