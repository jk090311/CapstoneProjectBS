<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/Teacher/teacherNavbar.css">
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
    </div>

    <div class="sidebar offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">TEACHER</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../PHPmain/dashboardTeacher.php">
            <img id="iconLeft" src="../Assets/data-analysis_12959229.png">
            Dashboard</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img id="iconLeft" src="../Assets/report_6896653.png">
              Reports
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="../PHPmain/reportSystem.php">Subjects</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../PHPmain/attendanceTracking.php">
            <img id="iconLeft" src="../Assets/appointment_18491830.png">   
            Grade</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>



</html>