<?php
// start session if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Use existing DB connection if present, otherwise include the shared connection
if (!isset($conn) || !$conn) {
  // dbconnection.php creates $conn
  require_once __DIR__ . '/../PHP/dbconnection.php';
}
// Fetch adviser's full name
$adviserFullName = "Teacher"; // Default fallback
if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email'];
    $query = "SELECT adviserFullName 
              FROM advisers A
              INNER JOIN user_acc B
              ON A.adviserEmailAddress = B.user_email
              WHERE B.user_email = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $adviserFullName = htmlspecialchars($row['adviserFullName']); // Sanitize output
        }
        $stmt->close();
    } else {
        // Handle query preparation error
        error_log("Database query failed: " . $conn->error);
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../CSS/Teacher/TeacherNavbar.css">
</head>

<body>
  <nav class="navbar bg-body-tertiary fixed-top">
    <div class="container-fluid d-flex align-items-center">
      <div class="d-flex align-items-center gap-2">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
          aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand d-flex align-items-center">
          <img id="imglogo" src="../Assets/111.png" alt="EduGuard Logo">
          <span>EducGuard</span>
        </a>
      </div>
      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="../PHPmain/logout.php">
          <button class="btn btn-danger" type="button">Log Out</button>
        </a>
      </div>
    </div>

    <div class="sidebar offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
      aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title text-white" id="offcanvasNavbarLabel">
          <?php echo $adviserFullName; ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link active text-white" aria-current="page" href="../PHPAdviser/dashboardTeacher.php">
              <img id="iconLeft" src="../Assets/data-analysis_12959229.png" alt="Dashboard Icon">
              Dashboard
            </a>
          </li>
          <!--li class="nav-item">
            <a class="nav-link active text-white " aria-current="page" href="../PHPAdviser/subjectTeacher.php">
            <img id="iconLeft" src="../Assets/student.png">   
            Subject Teachers</a>
          </li -->
          <li class="nav-item">
            <a class="nav-link active text-white " aria-current="page" href="../PHPAdviser/studentList.php">
            <img id="iconLeft" src="../Assets/student.png">   
            Students</a>
          </li>
          <li class="nav-item">
                        <a class="nav-link active text-white " aria-current="page" href="../PHPAdviser/adviserAttendance.php">
                            <img id="iconLeft" src="../Assets/student.png">
                            Attendance</a>
                    </li>
          <li class="nav-item">
            <a class="nav-link active text-white" aria-current="page" href="../PHPAdviser/reportSystem.php">
              <img id="iconLeft" src="../Assets/appointment_18491830.png" alt="Grade Icon">
              Grades
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active text-white" aria-current="page" href="../PHPAdviser/TeacherMessages.php">
              <img id="iconLeft" src="../Assets/appointment_18491830.png" alt="Message Icon">
              Message
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>