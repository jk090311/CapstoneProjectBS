<?php
include 'dbconnectionSubjects.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["adminSubjectPicture"])) {
    $subject_name = $_POST["adminSubjectName"];
    
    // Create URL-friendly link name for the subject page
    $link = "../PHPSubject/" . str_replace(' ', '', ucfirst(strtolower($subject_name))) . ".php";

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
        // Insert into database with link
        $sql = "INSERT INTO subjects (subject_name, subject_picture, link) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $subject_name, $file_name, $link);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Subject added successfully!";
            header("Location:../PHPAdviser/adviserSubject.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error uploading file. Check folder permissions or path.";
    }
}
?>