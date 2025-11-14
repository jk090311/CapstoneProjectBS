<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: ../PHPmain/index.php");
    exit();
}

// Check if user is an adviser
if ($_SESSION['user_role'] != 'adviser') {
    $_SESSION['status'] = "Error: Unauthorized access.";
    header("Location: ../PHPAdviser/studentList.php");
    exit();
}

// Database connection
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get adviser's section
$adviser_email = $_SESSION['user_email'];
$adviser_section_query = "SELECT adviserSection FROM advisers WHERE adviserEmailAddress = '$adviser_email'";
$adviser_result = mysqli_query($connection, $adviser_section_query);
$adviser_data = mysqli_fetch_assoc($adviser_result);

if (!$adviser_data || !isset($adviser_data['adviserSection'])) {
    $_SESSION['status'] = "Error: Adviser section not found.";
    header("Location: ../PHPAdviser/studentList.php");
    exit();
}

$adviser_section = $adviser_data['adviserSection'];

// Get section details (grade level and year)
$section_query = "SELECT section_grade_level, section_year_start_level FROM class_section WHERE section_name = '$adviser_section'";
$section_result = mysqli_query($connection, $section_query);
$section_data = mysqli_fetch_assoc($section_result);

if (!$section_data) {
    $_SESSION['status'] = "Error: Section details not found.";
    header("Location: ../PHPAdviser/studentList.php");
    exit();
}

$grade_level = $section_data['section_grade_level'];
$year_start = $section_data['section_year_start_level'];

// Check if file was uploaded
if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] != 0) {
    $_SESSION['status'] = "Error: Please upload a valid Excel file.";
    header("Location: ../PHPAdviser/studentList.php");
    exit();
}

$file = $_FILES['excelFile']['tmp_name'];
$file_ext = strtolower(pathinfo($_FILES['excelFile']['name'], PATHINFO_EXTENSION));

// Validate file extension
if (!in_array($file_ext, ['xlsx', 'xls', 'csv'])) {
    $_SESSION['status'] = "Error: Only Excel (.xlsx, .xls) or CSV (.csv) files are allowed.";
    header("Location: ../PHPAdviser/studentList.php");
    exit();
}

// Try to use PhpSpreadsheet if available, otherwise use alternative method
$usePhpSpreadsheet = false;
$spreadsheet_path = '../vendor/autoload.php';

if (file_exists($spreadsheet_path)) {
    require $spreadsheet_path;
    $usePhpSpreadsheet = class_exists('PhpOffice\PhpSpreadsheet\IOFactory');
}

$success_count = 0;
$error_count = 0;
$errors = [];

try {
    if ($usePhpSpreadsheet) {
        // Use PhpSpreadsheet library
        $IOFactory = 'PhpOffice\PhpSpreadsheet\IOFactory';
        
        $spreadsheet = $IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        // Skip header row
        array_shift($rows);
        
        foreach ($rows as $index => $row) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }
            
            $result = processStudentRow($row, $connection, $adviser_section, $grade_level, $year_start);
            if ($result['success']) {
                $success_count++;
            } else {
                $error_count++;
                $errors[] = "Row " . ($index + 2) . ": " . $result['message'];
            }
        }
    } else {
        // Fallback: Use SimpleXML for XLSX or manual parsing for XLS or CSV
        if ($file_ext == 'csv') {
            $rows = readCSV($file);
        } elseif ($file_ext == 'xlsx') {
            $rows = readXLSX($file);
        } else {
            // For .xls files, try reading as CSV or HTML table
            $rows = readXLS($file);
        }
        
        if (empty($rows)) {
            throw new Exception("Could not read Excel file. Please install PhpSpreadsheet library or use XLSX format.");
        }
        
        // Skip header row
        array_shift($rows);
        
        foreach ($rows as $index => $row) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }
            
            $result = processStudentRow($row, $connection, $adviser_section, $grade_level, $year_start);
            if ($result['success']) {
                $success_count++;
            } else {
                $error_count++;
                $errors[] = "Row " . ($index + 2) . ": " . $result['message'];
            }
        }
    }
    
    // Set success message
    $message = "Batch upload completed! ";
    $message .= "Successfully added: $success_count students. ";
    if ($error_count > 0) {
        $message .= "Failed: $error_count students. ";
        if (count($errors) > 0) {
            $message .= "<br><small>Errors: " . implode("<br>", array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= "<br>... and " . (count($errors) - 5) . " more errors.";
            }
            $message .= "</small>";
        }
    }
    
    $_SESSION['status'] = $message;
    
} catch (Exception $e) {
    $_SESSION['status'] = "Error: " . $e->getMessage();
}

mysqli_close($connection);
header("Location: ../PHPAdviser/studentList.php");
exit();

// Function to process a single student row
function processStudentRow($row, $connection, $section, $grade_level, $year_start) {
    // Expected columns:
    // 0: First Name, 1: Middle Name, 2: Last Name, 3: Birth Date, 4: Sex, 5: Contact Number,
    // 6: Address, 7: Parent Name, 8: Parent Number, 9: Parent Email, 10: LRN,
    // 11: Email, 12: Username, 13: Password, 14: Status
    
    if (count($row) < 14) {
        return ['success' => false, 'message' => 'Insufficient columns'];
    }
    
    // Sanitize inputs
    $first_name = mysqli_real_escape_string($connection, trim($row[0]));
    $middle_name = mysqli_real_escape_string($connection, trim($row[1]));
    $last_name = mysqli_real_escape_string($connection, trim($row[2]));
    $birth_date = mysqli_real_escape_string($connection, trim($row[3]));
    $sex = mysqli_real_escape_string($connection, trim($row[4]));
    $contact_number = mysqli_real_escape_string($connection, trim($row[5]));
    $address = mysqli_real_escape_string($connection, trim($row[6]));
    $parent_name = mysqli_real_escape_string($connection, trim($row[7]));
    $parent_number = mysqli_real_escape_string($connection, trim($row[8]));
    $parent_email = mysqli_real_escape_string($connection, trim($row[9]));
    $lrn = mysqli_real_escape_string($connection, trim($row[10]));
    $email = mysqli_real_escape_string($connection, trim($row[11]));
    $username = mysqli_real_escape_string($connection, trim($row[12]));
    $password = mysqli_real_escape_string($connection, trim($row[13]));
    $status = isset($row[14]) ? mysqli_real_escape_string($connection, trim($row[14])) : 'active';
    
    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($lrn) || empty($email)) {
        return ['success' => false, 'message' => 'Missing required fields'];
    }
    
    // Check if LRN already exists
    $check_query = "SELECT lrn FROM students WHERE lrn = '$lrn'";
    $check_result = mysqli_query($connection, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        return ['success' => false, 'message' => "LRN $lrn already exists"];
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Start transaction
    mysqli_begin_transaction($connection);
    
    try {
        // Insert into students table
        $insert_student = "INSERT INTO students (
            first_name, middle_name, last_name, birthdate, sex, contact_number,
            address, parent_guardian_name, parent_guardian_number, parent_guardian_email,
            lrn, email, student_username, student_password, grade_level, section, year_level, status
        ) VALUES (
            '$first_name', '$middle_name', '$last_name', '$birth_date', '$sex', '$contact_number',
            '$address', '$parent_name', '$parent_number', '$parent_email',
            '$lrn', '$email', '$username', '$hashed_password', '$grade_level', '$section', '$year_start', '$status'
        )";
        
        if (!mysqli_query($connection, $insert_student)) {
            throw new Exception("Failed to insert student: " . mysqli_error($connection));
        }
        
        // Insert into user_acc table
        $insert_user = "INSERT INTO user_acc (user_email, user_password, user_role) 
                       VALUES ('$email', '$hashed_password', 'student')";
        
        if (!mysqli_query($connection, $insert_user)) {
            throw new Exception("Failed to create user account: " . mysqli_error($connection));
        }
        
        mysqli_commit($connection);
        return ['success' => true, 'message' => 'Student added successfully'];
        
    } catch (Exception $e) {
        mysqli_rollback($connection);
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// Function to read XLSX files without PhpSpreadsheet
function readXLSX($file) {
    $rows = [];
    
    try {
        $zip = new ZipArchive();
        if ($zip->open($file) === TRUE) {
            $xml = $zip->getFromName('xl/sharedStrings.xml');
            $xmlWorksheet = $zip->getFromName('xl/worksheets/sheet1.xml');
            $zip->close();
            
            if (!$xmlWorksheet) {
                return [];
            }
            
            // Parse shared strings
            $strings = [];
            if ($xml) {
                $xmlObj = simplexml_load_string($xml);
                foreach ($xmlObj->si as $si) {
                    $strings[] = (string)$si->t;
                }
            }
            
            // Parse worksheet
            $xmlObj = simplexml_load_string($xmlWorksheet);
            
            foreach ($xmlObj->sheetData->row as $row) {
                $rowData = [];
                foreach ($row->c as $cell) {
                    $value = '';
                    if (isset($cell->v)) {
                        if (isset($cell['t']) && $cell['t'] == 's') {
                            // String from shared strings
                            $value = $strings[(int)$cell->v];
                        } else {
                            $value = (string)$cell->v;
                        }
                    }
                    $rowData[] = $value;
                }
                $rows[] = $rowData;
            }
        }
    } catch (Exception $e) {
        return [];
    }
    
    return $rows;
}

// Function to read XLS files (HTML table format)
function readXLS($file) {
    $rows = [];
    
    try {
        $content = file_get_contents($file);
        
        // Try to parse as HTML table
        if (strpos($content, '<table') !== false) {
            $dom = new DOMDocument();
            @$dom->loadHTML($content);
            $tables = $dom->getElementsByTagName('table');
            
            if ($tables->length > 0) {
                $table = $tables->item(0);
                $trs = $table->getElementsByTagName('tr');
                
                foreach ($trs as $tr) {
                    $rowData = [];
                    $tds = $tr->getElementsByTagName('td');
                    
                    if ($tds->length == 0) {
                        $tds = $tr->getElementsByTagName('th');
                    }
                    
                    foreach ($tds as $td) {
                        $rowData[] = trim($td->nodeValue);
                    }
                    
                    if (!empty($rowData)) {
                        $rows[] = $rowData;
                    }
                }
            }
        }
    } catch (Exception $e) {
        return [];
    }
    
    return $rows;
}

// Function to read CSV files
function readCSV($file) {
    $rows = [];
    
    try {
        if (($handle = fopen($file, 'r')) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $rows[] = $data;
            }
            fclose($handle);
        }
    } catch (Exception $e) {
        return [];
    }
    
    return $rows;
}
?>





