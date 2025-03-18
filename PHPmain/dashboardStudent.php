<?php include "studentNavbar.php"?>

<?php 

session_start();

if(!isset($_SESSION['user_email']))
{
    header("location:../PHPmain/index.php");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="../CSS/homepage.css">
</head>

<body>

    <div id="centerBox">
        <h1 id="welcomeText">
            Welcome Malinta Students!
          </h1>
    </div>
      <script src="../JS/inout.js"></script>

</body>
</html>