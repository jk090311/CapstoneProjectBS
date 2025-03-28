<?php 
session_start();
error_reporting(0);
$conn = mysqli_connect("localhost", "root", "", "educguarddb");

    if(isset($_POST['login']))
    {
        $u_email = $_POST['email'];

        $u_password = $_POST['password'];

        $sql = "SELECT * FROM user_acc WHERE user_email = '".$u_email."'AND user_password = '".$u_password."'";

        $result = mysqli_query($conn, $sql);

        $row = mysqli_fetch_array($result);

        if($row['user_role']=="admin")
        {   
            $_SESSION['user_email'] = $u_email;

            $_SESSION['user_role'] = "admin";
            
            header("location:dashboardAdmin.php");
        }
        else if ($row['user_role']=="adviser")
        {
            $_SESSION['user_email'] = $u_email;

            $_SESSION['user_role'] = "adviser";

            header("location:./dashboardTeacher.php");
        }
        else if ($row['user_role']=="student")
        {
            $_SESSION['user_email'] = $u_email;

            $_SESSION['user_role'] = "student";

            header("location:dashboardStudent.php");
        }
        else
        {
            $_SESSION['message']="Invalid email or password";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/CapstoneProjectBS/CSS/index.css" rel="stylesheet">
    <title>Login</title>
</head>

<body>
<header>
    <h1>Welcome to Capstone Project</h1>

</header>
<div class="container">
    <div id="loginBox">
    <div class="circle"> </div> 
        <h1 id="loginText">Login</h1>
        <p class="formFormat">Login your account</p>

        <div id="warningPopup" class="warning-popup">
            <?php echo $_SESSION['message']; ?>
        </div>

        <form class="formFormat" action="" method="POST">
            <label>Email</label><br>
            <input type="text" class="inputForm" name="email" placeholder="example@gmail.com"><br><br>
            <label>Password</label><br>
            <input type="password" class="inputForm" name="password" placeholder="!password123"><br><br>
            <input type="submit" id="loginButton" name="login" value="Login">
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2025 Capstone Project. All rights reserved.</p>
</footer>

<script>
    // Show warning popup if there's a message
    <?php if(isset($_SESSION['message']) && !empty($_SESSION['message'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('warningPopup');
            popup.style.display = 'block';
            
            // Reset animation
            popup.style.animation = 'none';
            void popup.offsetWidth; // Trigger reflow
            popup.style.animation = 'slideDown 0.5s, fadeOut 0.5s 2.5s forwards';
            
            // Hide popup after animation completes
            setTimeout(() => {
                popup.style.display = 'none';
            }, 3000);
        });
    <?php 
        // Clear the message after showing it
        unset($_SESSION['message']);
    endif; ?>
</script>

<script src="/FinalCapstoneWebsite/JS/login.js"></script>
</body>
</html>