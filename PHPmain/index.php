<?php 
session_start();
error_reporting(0);
$conn = mysqli_connect("localhost", "root", "", "educguarddb");


// Database connection
$conn = mysqli_connect("localhost", "root", "", "educguarddb");

// Check if the form is submitted
if(isset($_POST['login']))
{
    $u_email = mysqli_real_escape_string($conn, $_POST['email']);
    $u_password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
$stmt = mysqli_prepare($conn, "SELECT u.user_id, u.user_email, u.user_password, u.user_role, s.student_id 
    FROM user_acc u 
    LEFT JOIN students s ON u.user_email = s.email 
    WHERE u.user_email = ?");
mysqli_stmt_bind_param($stmt, "s", $u_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $storedHash = $row['user_password']; // Retrieve the stored hash

    // Verify the hashed password
    if (password_verify($u_password, $storedHash)) {
        $_SESSION['user_id'] = $row['user_id']; // Store user ID in session
        $_SESSION['user_email'] = $u_email;
        $_SESSION['user_role'] = $row['user_role'];

        // Store student_id in session if user is a student
        if ($row['user_role'] == "student") {
            $_SESSION['student_id'] = $row['student_id'];
        }

        // Redirect based on user role
        if ($row['user_role'] == "admin") {
            header("location:../PHPAdmin/dashboardAdmin.php");
            exit();
        } else if ($row['user_role'] == "adviser") {
            header("location:../PHPAdviser/dashboardTeacher.php?id=" . $row['user_id']); // Pass user ID in URL
            exit();
        } else if ($row['user_role'] == "student") {
            header("location:../PHPStudent/dashboardStudent.php?id=" . $row['user_id']); // Pass user ID in URL
            exit();
        } else if ($row['user_role'] == "subject_teacher") {
            header("location:../PHPSubjectTeacher/dashboardSubjectTeacher.php?id=" . $row['user_id']); // Pass user ID in URL
            exit();
        }
    } else {
        $_SESSION['message'] = "Invalid email or password.";
        header("location: index.php");
        exit();
    }
} else {
    $_SESSION['message'] = "No account found with this email.";
    header("location: index.php");
    exit();
}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
            <div class="password-container">
                <input type="password" class="inputForm" name="password" id="passwordInput" placeholder="!password123">
                <i class="fa-solid fa-eye" id="togglePassword"></i>
            </div>
            <br><br>
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

<script src="/CapstoneProjectBSBackup/JS/login.js"></script>
</body>
</html>