<?php 
session_start();
include_once "../PHP/dbconnection.php";
$outgoing_id = $_SESSION['unique_id'];
$sql = mysqli_query($conn, "SELECT * FROM students");
$output = "";

if(mysqli_num_rows($sql) == 1){
    $output .= "No users are available to chat";
}else if(mysqli_num_rows($sql) > 0){
   include "data.php";
}
echo $output;
?>