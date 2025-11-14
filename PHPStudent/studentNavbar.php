<?php
// Start session only if one isn't already active to avoid duplicate session_start notices
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Add debugging
error_log("Session email: " . (isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'not set'));

// Use existing DB connection if available; otherwise include the shared connection file
if (!isset($conn) || !$conn) {
  // dbconnection.php creates $conn
  require_once __DIR__ . '/../PHP/dbconnection.php';
}
// Fetch student's full name
$studentFullName = "Student"; // Default fallback
if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email'];
    $query = "SELECT CONCAT(S.first_name, ' ', S.last_name) as fullName 
              FROM students S
              INNER JOIN user_acc U
              ON S.email = U.user_email
              WHERE U.user_email = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Add debugging
        error_log("Query executed. Num rows: " . $result->num_rows);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $studentFullName = htmlspecialchars($row['fullName']); 
            // Add debugging
            error_log("Found student name: " . $studentFullName);
        } else {
            error_log("No student found for email: " . $userEmail);
        }
    $stmt->close();
  } else {
    error_log("Query preparation failed: " . $conn->error);
  }
} else {
  error_log("No user email in session");
}

// Do not close $conn here: caller owns the connection lifecycle.
error_log("Final student name value: " . $studentFullName);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/Student/studentNavbar.css">
</head>
<body>
  <nav class="navbar bg-body-tertiary fixed-top">
    <div class="container-fluid d-flex align-items-center">
      <div class="d-flex align-items-center gap-2">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand d-flex align-items-center">
          <img id="imglogo" src="../Assets/111.png">
          <span>EduGuard</span>
        </a>
      </div>
      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
      <a href="../PHPmain/logout.php">
        <button class="btn btn-danger" type="button">Log Out</button>
        </a>
      </div>
    </div>

    <div class="sidebar offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
          <?php echo $studentFullName; ?> <!-- Display student name here -->
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../PHPStudent/dashboardStudent.php">
            <img id="iconLeft" src="../Assets/data-analysis_12959229.png">
            Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../PHPStudent/gradeStudent.php">
            <img id="iconLeft" src="../Assets/report_6896653.png">  
            Grade</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../PHPStudent/StudentMessage.php">
            <img id="iconLeft" src="../Assets/message_4129700.png">   
            Messages</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../PHPAdviser/adviserUserList.php">
            Messages</a>
          </li> -->
        </ul>
      </div>
    </div>
  </nav>
  <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>



</html>




