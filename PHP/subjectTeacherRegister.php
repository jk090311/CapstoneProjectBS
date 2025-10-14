<?php
session_start();

// Database connection
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['subjectTeacherRegister'])) {
    // Sanitize required inputs
    $stFullName = mysqli_real_escape_string($connection, $_POST['stFullName']);
    $stContactNumber = mysqli_real_escape_string($connection, $_POST['stContactNumber']);
    $stEmail = mysqli_real_escape_string($connection, $_POST['stEmail']);
    $stPassword = $_POST['stPassword'];
    $stGradelvl = mysqli_real_escape_string($connection, $_POST['stGradelvl']);
    $stSection = !empty($_POST['stSection']) ? intval($_POST['stSection']) : NULL;

    // Handle optional fields - convert empty values to NULL
    $stGradelvl2 = !empty($_POST['stGradelvl2']) ? mysqli_real_escape_string($connection, $_POST['stGradelvl2']) : NULL;
    $stSection2 = !empty($_POST['stSection2']) ? intval($_POST['stSection2']) : NULL;
    $stGradelvl3 = !empty($_POST['stGradelvl3']) ? mysqli_real_escape_string($connection, $_POST['stGradelvl3']) : NULL;
    $stSection3 = !empty($_POST['stSection3']) ? intval($_POST['stSection3']) : NULL;

    // Handle subjects - convert empty or "0" values to NULL
    $stSubject1 = !empty($_POST['stSubject1']) ? intval($_POST['stSubject1']) : NULL;
    $stSubject2 = (!empty($_POST['stSubject2']) && $_POST['stSubject2'] !== '0') ? intval($_POST['stSubject2']) : NULL;
    $stSubject3 = (!empty($_POST['stSubject3']) && $_POST['stSubject3'] !== '0') ? intval($_POST['stSubject3']) : NULL;

    // Build stSubject as comma-separated subject_id(s) (if still needed)
    $subjectsArr = [];
    if ($stSubject1 > 0) $subjectsArr[] = $stSubject1;
    if ($stSubject2 > 0 && $stSubject2 !== $stSubject1) $subjectsArr[] = $stSubject2;
    if ($stSubject3 > 0 && $stSubject3 !== $stSubject1 && $stSubject3 !== $stSubject2) $subjectsArr[] = $stSubject3;
    $stSubject = implode(',', $subjectsArr);

    // Hash the password
    $stHashedPassword = password_hash($stPassword, PASSWORD_BCRYPT);

    // Use transaction
    mysqli_begin_transaction($connection);

    try {
        // Prepare insert into subject_teachers with matching placeholders
        $sql = "INSERT INTO subject_teachers 
            (stFullName, stContactNumber, stEmail, stPassword, stGradelvl, stSection, stSubject, stGradelvl2, stSection2, stSubject2, stGradelvl3, stSection3, stSubject3)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $connection->error);
        }

        // Normalize nullable ints to 0 for binding (or keep 0 to represent "not set")
        $primarySub = ($stSubject1 > 0) ? $stSubject1 : 0;
        $secondarySub = ($stSubject2 > 0) ? $stSubject2 : 0;
        $tertiarySub = ($stSubject3 > 0) ? $stSubject3 : 0;

        // Build type string and bind parameters (13 params)
        $types = 'sssssii siisii'; // temporary visual; we'll build with concatenation next
        $types = 'sssss' . 'ii' . 's' . 'ii' . 's' . 'ii'; // results in 13-character type string

        // Bind in the exact column order used in the INSERT
        $bindSuccess = $stmt->bind_param(
            $types,
            $stFullName,         // s
            $stContactNumber,    // s
            $stEmail,            // s
            $stHashedPassword,   // s
            $stGradelvl,         // s
            $stSection,          // i
            $primarySub,         // i -> stSubject (primary)
            $stGradelvl2,        // s
            $stSection2,         // i
            $secondarySub,       // i -> stSubject2
            $stGradelvl3,        // s
            $stSection3,         // i
            $tertiarySub         // i -> stSubject3
        );

        if ($bindSuccess === false) {
            throw new Exception("bind_param failed: " . $stmt->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Subject Teacher insert failed: " . $stmt->error);
        }
        $stmt->close();

        // Insert relation rows for assigned subjects (mapping table)
        $teacherId = mysqli_insert_id($connection);
        if ($teacherId > 0) {
            $insertRelStmt = mysqli_prepare($connection,
                "INSERT IGNORE INTO subject_teacher_subjects (subject_teacher_id, subject_id) VALUES (?, ?)");
            if ($insertRelStmt === false) {
                throw new Exception("Prepare failed for relation insert: " . mysqli_error($connection));
            }

            $subs = [];
            if ($stSubject1 > 0) $subs[] = $stSubject1;
            if ($stSubject2 > 0 && $stSubject2 !== $stSubject1) $subs[] = $stSubject2;
            if ($stSubject3 > 0 && $stSubject3 !== $stSubject1 && $stSubject3 !== $stSubject2) $subs[] = $stSubject3;

            foreach ($subs as $sid) {
                mysqli_stmt_bind_param($insertRelStmt, 'ii', $teacherId, $sid);
                if (!mysqli_stmt_execute($insertRelStmt)) {
                    throw new Exception("Relation insert failed: " . mysqli_stmt_error($insertRelStmt));
                }
            }
            mysqli_stmt_close($insertRelStmt);
        }

        // Insert into user_acc table (use prepared statement to avoid SQL injection)
        $userStmt = $connection->prepare("INSERT INTO user_acc (user_email, user_password, user_role) VALUES (?, ?, ?)");
        if ($userStmt === false) {
            throw new Exception("Prepare failed for user_acc insert: " . $connection->error);
        }
        $role = 'subject_teacher';
        if (!$userStmt->bind_param('sss', $stEmail, $stHashedPassword, $role) || !$userStmt->execute()) {
            throw new Exception("User account insert failed: " . $userStmt->error);
        }
        $userStmt->close();

        // Commit transaction
        mysqli_commit($connection);

        $_SESSION['status'] = "Subject Teacher Profile and Account Added Successfully";
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($connection);
        $_SESSION['status'] = "Error: Account Creation Unsuccessful. " . $e->getMessage();
    }

    header('Location: ../PHPAdmin/adminTeachers.php');
    exit;
}

// Close connection
mysqli_close($connection);
?>
<!--