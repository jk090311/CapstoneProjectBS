<?php
include 'dbconnectionSubjects.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["adminSubjectPicture"])) {
    $subject_name = $_POST["adminSubjectName"];

    // File upload settings
    $target_dir = "../Uploads/";
    $file_name = basename($_FILES["adminSubjectPicture"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is valid
    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_types)) {
        die("Only JPG, JPEG, PNG & GIF files are allowed.");
    }

    // Move uploaded file to server directory
    if (move_uploaded_file($_FILES["adminSubjectPicture"]["tmp_name"], $target_file)) {
        // Insert into database
        $sql = "INSERT INTO subject (subject_name, subject_picture) VALUES ('$subject_name', '$file_name')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success_message'] = "Subject added successfully!";
            header("Location: ../PHPmain/adminSubject.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error uploading file. Check folder permissions or path.";
    }
}
?>