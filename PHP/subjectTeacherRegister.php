<?php
session_start();

// Database connection
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['subjectTeacherRegister'])) {
    // Sanitize input
    $stFullName = mysqli_real_escape_string($connection, $_POST['stFullName']);
    $stContactNumber = mysqli_real_escape_string($connection, $_POST['stContactNumber']);
    $stEmail = mysqli_real_escape_string($connection, $_POST['stEmail']);
    $stPassword = mysqli_real_escape_string($connection, $_POST['stPassword']);
    // Accept subject IDs from the admin form (stSubject1 required, stSubject2 optional)
    $stSubject1 = isset($_POST['stSubject1']) ? intval($_POST['stSubject1']) : 0;
    $stSubject2 = isset($_POST['stSubject2']) ? intval($_POST['stSubject2']) : 0;

    // Build stSubject as comma-separated subject_id(s)
    $subjectsArr = [];
    if ($stSubject1 > 0) $subjectsArr[] = $stSubject1;
    if ($stSubject2 > 0 && $stSubject2 !== $stSubject1) $subjectsArr[] = $stSubject2;
    $stSubject = implode(',', $subjectsArr);

    // Hash the password
    $stHashedPassword = password_hash($stPassword, PASSWORD_BCRYPT);

    // Start transaction
    mysqli_begin_transaction($connection);

    try {
        // Insert into subject_teachers table
    // For backwards compatibility with the database schema where stSubject and stSubject2 are INTs,
    // store primary and secondary subject ids (secondary may be NULL).
    $primarySub = intval($stSubject1);
    $secondarySub = ($stSubject2 > 0) ? intval($stSubject2) : 'NULL';
    $insert_teacher_query = "INSERT INTO subject_teachers (stFullName, stContactNumber, stEmail, stPassword, stSubject, stSubject2)
        VALUES ('$stFullName', '$stContactNumber', '$stEmail', '$stHashedPassword', $primarySub, " . ($secondarySub === 'NULL' ? 'NULL' : $secondarySub) . ")";
        $insert_teacher_query_run = mysqli_query($connection, $insert_teacher_query);

        if (!$insert_teacher_query_run) {
            throw new Exception("Subject Teacher insert failed: " . mysqli_error($connection));
        }

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

            foreach ($subs as $sid) {
                mysqli_stmt_bind_param($insertRelStmt, 'ii', $teacherId, $sid);
                if (!mysqli_stmt_execute($insertRelStmt)) {
                    throw new Exception("Relation insert failed: " . mysqli_stmt_error($insertRelStmt));
                }
            }
            mysqli_stmt_close($insertRelStmt);
        }

        // Insert into user_acc table
        $insert_user_query = "INSERT INTO user_acc (user_email, user_password, user_role)
                     VALUES ('$stEmail', '$stHashedPassword', 'subject_teacher')";
        $insert_user_query_run = mysqli_query($connection, $insert_user_query);

        if (!$insert_user_query_run) {
            throw new Exception("User account insert failed: " . mysqli_error($connection));
        }

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