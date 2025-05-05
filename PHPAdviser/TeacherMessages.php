<?php include "teacherNavbar.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Messages</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/Teacher/designMessages.css">
</head>

<body>
    <div id="wrapper">
        <div id="left_panel">
            <div style="padding: 10px; ">
                <img id="profile_image" src="../Assets/111.png" alt="">
                <br>
                Name Teacher
                <br>
                <span style="font-size: 12px; opacity: 0.5;">Teacher Email</span>

                <br>
                <br>
                <br>
                <div>
                    <label for="box1">Chat <img src="../Assets/111.png"></label>
                    <label for="box1">Contacts<img src="../Assets/111.png"></label>
                    <label for="box1" >Settings<img src="../Assets/111.png"></label>
                </div>
            </div>
        </div>
        <div id="right_panel">
            <div id="header"> My Messages
            </div>
            <div id="container" style="display: flex;">
                <div id="inner_left_panel">
                    <input type="checkbox" id="box1" name="checkbox1" value="value1">
                    <!-- Add content for the inner left panel -->
                </div>
                <div id="inner_right_panel">
                    <!-- Add content for the inner right panel -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>