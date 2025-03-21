<?php 
session_start();

$connection = mysqli_connect("localhost", "root", "", "adviser_list");

if(isset($_POST['adviserRegister']))
{
    $adviserFullName = $_POST['adviserFullName'];
    $adviserContactNumber = $_POST['adviserContactNumber'];
    $adviserGrLvl = $_POST['adviserGrLvl'];
    $adviserSection = $_POST['adviserSection'];
    $adviserEmail = $_POST['adviserEmail'];
    $adviserPassword = $_POST['adviserPassword'];

    $insert_query = "INSERT INTO advisers(adviserFullName, adviserContactNumber, adviserGrLvl, adviserSection, adviserEmail, adviserPassword)
     VALUES ('$adviserFullName', '$adviserContactNumber', '$adviserGrLvl', '$adviserSection', '$adviserEmail', '$adviserPassword')";
    $insert_query_run = mysqli_query($connection, $insert_query);

    if($insert_query_run)
    {
        $_SESSION['status'] = "Adviser Profile Added";
        header('Location: ../PHPmain/adminTeachers.php');
    }
    else
    {
        $_SESSION['status'] = "Adviser Profile Not Added";
    }

}
?>