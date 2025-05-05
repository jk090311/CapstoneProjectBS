<?php
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

if (isset($_POST['sectionUpdate'])) {
    $section_id = $_POST['section_id'];
    $section_name = $_POST['section_name'];
    $section_grade_level = $_POST['section_grade_level'];
    $section_year_start_level = $_POST['section_year_start_level'];
    $section_year_end_level = $_POST['section_year_end_level'];

    $update_query = "UPDATE class_section SET section_name='$section_name', section_grade_level='$section_grade_level', section_year_start_level='$section_year_start_level',section_year_end_level='$section_year_end_level' WHERE section_id='$section_id'";
    $update_query_run = mysqli_query($connection, $update_query);

    if ($update_query_run) {
        $_SESSION['status'] = "Section updated successfully!";
        header("Location: ../PHPAdmin/AdminAddSection.php");
    } else {
        $_SESSION['status'] = "Failed to update section.";
        header("Location: ../PHPAdmin/AdminAddSection.php");
    }
}
?>