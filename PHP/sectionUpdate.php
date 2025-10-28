<?php
$connection = mysqli_connect("sql211.infinityfree.com", "if0_40275155", "EduGuard202526", "if0_40275155_eduguarddb");

if (isset($_POST['sectionUpdate'])) {
    $section_id = $_POST['section_id'];
    $section_name = $_POST['section_name'];
    $section_year_level = $_POST['section_year_level'];

    $update_query = "UPDATE class_section SET section_name='$section_name', section_year_level='$section_year_level' WHERE section_id='$section_id'";
    $update_query_run = mysqli_query($connection, $update_query);

    if ($update_query_run) {
        $_SESSION['status'] = "Section updated successfully!";
        header("Location: ../PHPmain/AdminAddSection.php");
    } else {
        $_SESSION['status'] = "Failed to update section.";
        header("Location: ../PHPmain/AdminAddSection.php");
    }
}
?>