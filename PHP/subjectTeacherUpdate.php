<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if(isset($_POST['stFullName'])) { // Changed from 'subjectTeacherFullName' to match form field name
    // Get form data with correct field names
    $originalstName = mysqli_real_escape_string($connection, $_POST['originalstName']);
    $stFullName = mysqli_real_escape_string($connection, $_POST['stFullName']); // Fixed field name
    $stContactNumber = mysqli_real_escape_string($connection, $_POST['stContactNumber']); // Fixed field name
    $stEmail = mysqli_real_escape_string($connection, $_POST['stEmail']); // Fixed field name
    $stPassword = mysqli_real_escape_string($connection, $_POST['stPassword']); // Fixed field name
    // For update, accept subject IDs in stSubject1/stSubject2 (optional)
    $stSubject1 = isset($_POST['stSubject1']) ? intval($_POST['stSubject1']) : 0;
    $stSubject2 = isset($_POST['stSubject2']) ? intval($_POST['stSubject2']) : 0;
    $stSubject = mysqli_real_escape_string($connection, isset($_POST['stSubject']) ? $_POST['stSubject'] : ''); // legacy

    // Hash the password if it's changed
    $stHashedPassword = password_hash($stPassword, PASSWORD_BCRYPT);

    try {
        mysqli_begin_transaction($connection);

        // First, get the old email for user_acc table update
        $get_old_email_query = "SELECT stEmail FROM subject_teachers WHERE stFullName = ?";
        $stmt_old = $connection->prepare($get_old_email_query);
        $stmt_old->bind_param("s", $originalstName);
        $stmt_old->execute();
        $result = $stmt_old->get_result();
        $old_data = $result->fetch_assoc();
        $old_email = $old_data['stEmail'];

    // Update subject_teachers table
    $secondarySub = ($stSubject2 > 0) ? $stSubject2 : null;
    $update_query = "UPDATE subject_teachers SET 
            stFullName = ?, 
            stContactNumber = ?, 
            stEmail = ?, 
            stPassword = ?, 
            stSubject = ?,
            stSubject2 = ? 
            WHERE stFullName = ?";

    $stmt = $connection->prepare($update_query);
    $stmt->bind_param("sssssis", $stFullName, $stContactNumber, $stEmail, $stHashedPassword, $stSubject, $secondarySub, $originalstName);
        
        if($stmt->execute()) {
            // sync relation table: remove existing and insert selected
            // Get teacher id
            $getIdQ = "SELECT stID FROM subject_teachers WHERE stFullName = ? LIMIT 1";
            $stmtId = $connection->prepare($getIdQ);
            $stmtId->bind_param('s', $stFullName);
            $stmtId->execute();
            $resId = $stmtId->get_result();
            $teacherRow = $resId->fetch_assoc();
            $teacherId = $teacherRow ? intval($teacherRow['stID']) : 0;
            $stmtId->close();

            if ($teacherId > 0) {
                // delete existing relations
                $delStmt = $connection->prepare("DELETE FROM subject_teacher_subjects WHERE subject_teacher_id = ?");
                $delStmt->bind_param('i', $teacherId);
                if (!$delStmt->execute()) {
                    throw new Exception('Failed to clear previous subject relations: '. $delStmt->error);
                }
                $delStmt->close();

                // insert new relations
                $insertRelStmt = mysqli_prepare($connection,
                    "INSERT IGNORE INTO subject_teacher_subjects (subject_teacher_id, subject_id) VALUES (?, ?)");
                if ($insertRelStmt === false) {
                    throw new Exception("Prepare failed for relation insert: " . mysqli_error($connection));
                }
                $newSubs = [];
                if ($stSubject1 > 0) $newSubs[] = $stSubject1;
                if ($stSubject2 > 0 && $stSubject2 !== $stSubject1) $newSubs[] = $stSubject2;
                foreach ($newSubs as $sid) {
                    mysqli_stmt_bind_param($insertRelStmt, 'ii', $teacherId, $sid);
                    if (!mysqli_stmt_execute($insertRelStmt)) {
                        throw new Exception("Relation insert failed: " . mysqli_stmt_error($insertRelStmt));
                    }
                }
                mysqli_stmt_close($insertRelStmt);
            }
            // Update user_acc table using the old email
            $update_user_query = "UPDATE user_acc SET 
                                 user_email = ?, 
                                 user_password = ? 
                                 WHERE user_email = ?";
                        
            $stmt_user = $connection->prepare($update_user_query);
            $stmt_user->bind_param("sss", $stEmail, $stHashedPassword, $old_email);
            
            if($stmt_user->execute()) {
                mysqli_commit($connection);
                $_SESSION['status'] = "Subject Teacher updated successfully.";
            } else {
                throw new Exception("Failed to update user account table");
            }
        } else {
            throw new Exception("Failed to update subject teacher");
        }

    } catch (Exception $e) {
        mysqli_rollback($connection);
        $_SESSION['status'] = "Error: " . $e->getMessage();
    }

} else {
    $_SESSION['status'] = "Invalid request or missing data.";
}

mysqli_close($connection);
header('Location: ../PHPAdmin/adminTeachers.php');
exit();
?>