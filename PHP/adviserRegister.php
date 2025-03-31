<?php 
session_start();

$connection = mysqli_connect("localhost", "root", "", "adviser_list");

if(isset($_POST['adviserRegister']))
{
    $adviserFullName = $_POST['adviserFullName'];
    $adviserContactNumber = $_POST['adviserContactNumber'];
    $adviserGrLvl = $_POST['adviserGrLvl'];
    $adviserSection = $_POST['adviserSection'];

    $insert_query = "INSERT INTO adviser(adviserFullName, adviserContactNumber, adviserGrLvl, adviserSection)
     VALUES ('$adviserFullName', '$adviserContactNumber', '$adviserGrLvl', '$adviserSection')";
    $insert_query_run = mysqli_query($connection, $insert_query);

    if($insert_query_run)
    {
        $_SESSION['status'] = "Adviser Profile Added";
    }
    else
    {
        $_SESSION['status'] = "Adviser Profile Not Added";
    }

    header('Location: ../PHPmain/adminTeachers.php');
    exit;
}
