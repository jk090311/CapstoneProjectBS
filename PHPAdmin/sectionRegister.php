<?php 
session_start();
ob_start(); // Start output buffering

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection = mysqli_connect("localhost", "root", "", "educguarddb");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['sectionRegister'])) {
    $section_name = $_POST['section_name'];
    $section_grade_level = $_POST['section_grade_level'];
    $section_year_start_level = $_POST['section_year_start_level'];
    $section_year_end_level = $_POST['section_year_end_level'];

    // Insert query to add the section to the database
    $insert_query = "INSERT INTO class_section (section_name, section_grade_level, section_year_start_level, section_year_end_level) 
                     VALUES ('$section_name', '$section_grade_level', '$section_year_start_level', '$section_year_end_level')";
    $insert_query_run = mysqli_query($connection, $insert_query);

    if ($insert_query_run) {
        $_SESSION['status'] = "Section Added Successfully";
    } else {
        $_SESSION['status'] = "Section Not Added: " . mysqli_error($connection);
    }

    header('Location: ../PHPAdmin/AdminAddSection.php');
    exit;
}
?>





