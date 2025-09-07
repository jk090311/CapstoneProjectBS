<?php
include 'dbconnectionSubjects.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id = $_POST["subject_id"];
    $subject_name = $_POST["editSubjectName"];
    $file_name = "";

    // Check if a new picture is uploaded
    if (!empty($_FILES["editSubjectPicture"]["name"])) {
        $target_dir = "../Uploads/";
        $file_name = basename($_FILES["editSubjectPicture"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed_types)) {
            die("Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        // Move uploaded file to server directory
        if (!move_uploaded_file($_FILES["editSubjectPicture"]["tmp_name"], $target_file)) {
            die("Error uploading file. Check folder permissions or path.");
        }
    }

    // Update the database
    if (!empty($file_name)) {
        $sql = "UPDATE subjects SET subject_name='$subject_name', subject_picture='$file_name' WHERE subject_id='$subject_id'";
    } else {
        $sql = "UPDATE subjects SET subject_name='$subject_name' WHERE subject_id='$subject_id'";
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Subject updated successfully!";
        header("Location: ../PHPAdviser/adviserSubject.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>