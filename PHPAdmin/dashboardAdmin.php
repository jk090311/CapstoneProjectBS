<?php include "adminNavbar.php"; ?>

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Not logged in, redirect to login page
    header("Location: ../PHPmain/index.php");
    exit();
}


$required_role = "admin"; 
if ($_SESSION['user_role'] != $required_role) {
    
    if ($_SESSION['user_role'] == "admin") {
        header("Location: ../PHPAdmin/dashboardAdmin.php");
    } else if ($_SESSION['user_role'] == "adviser") {
        header("Location: ../PHPAdviser/dashboardTeacher.php");
    } else if ($_SESSION['user_role'] == "student") {
        header("Location: dashboardStudent.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/CapstoneProjectBS/CSS/Admin/adminDashboard.css" rel="stylesheet">
    <title>Dashboard</title>
   
</head>
<body>
    <?php
    // Include DB connection and query totals
    require_once __DIR__ . '/../PHP/dbconnection.php';
    $totalStudents = 0;
    $totalAdvisers = 0;
    $totalSections = 0; 
    $totalsubject_Teachers = 0;
    $r = $conn->query("SELECT COUNT(*) AS cnt FROM students");
    if($r){ $totalStudents = $r->fetch_assoc()['cnt']; }
    $r2 = $conn->query("SELECT COUNT(*) AS cnt FROM advisers");
    if($r2){ $totalAdvisers = $r2->fetch_assoc()['cnt']; }
    $r3 = $conn->query("SELECT COUNT(*) AS cnt FROM class_section");
    if($r3){ $totalSections = $r3->fetch_assoc()['cnt']; }
     $r3 = $conn->query("SELECT COUNT(*) AS cnt FROM subject_teachers");
    if($r3){ $totalsubject_Teachers = $r3->fetch_assoc()['cnt']; }

    // Attempt to detect a column that indicates honors/with honor in students table
    $withHonor = 0;
    $candidateCols = ['with_honor','with_honors','honors','honor_status','honor','honor_roll'];
    foreach ($candidateCols as $col) {
        $colEsc = $conn->real_escape_string($col);
        $check = $conn->query("SHOW COLUMNS FROM students LIKE '" . $colEsc . "'");
        if ($check && $check->num_rows > 0) {
            // Try counting rows where the column suggests honor (1 or textual markers)
            $q = "SELECT COUNT(*) AS cnt FROM students WHERE (`" . $colEsc . "` = '1' OR LOWER(`" . $colEsc . "`) IN ('yes','with honors','with honor','honor','honors'))";
            $res = $conn->query($q);
            if ($res) {
                $withHonor = (int)$res->fetch_assoc()['cnt'];
            }
            break;
        }
    }
    ?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    body {
        padding-top: 56px;
    }
    .main-content {
        min-height: calc(100vh - 56px);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
    }
    .dashboard-container {
        background: white;
        border-radius: 12px;
        padding: 50px;
        max-width: 900px;
        width: 100%;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    .welcome-header {
        text-align: center;
        margin-bottom: 15px;
    }
    .welcome-header h2 {
        font-size: 32px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }
    .welcome-subtext {
        text-align: center;
        color: #7f8c8d;
        margin-bottom: 40px;
        font-size: 16px;
    }
    .info-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .info-card {
        padding: 30px;
        border-radius: 8px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: transform 0.2s;
    }
    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.2);
    }
    .info-left {
        display: flex;
        flex-direction: column;
    }
    .info-left .label {
        font-size: 11px;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        opacity: 0.95;
        font-weight: 500;
        margin-bottom: 8px;
    }
    .info-left .big {
        font-size: 38px;
        font-weight: 700;
    }
    .info-icon {
        font-size: 50px;
        opacity: 0.9;
    }
    .info-green {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    }
    .info-orange {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    }
    .info-blue {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }
    .info-purple {
        background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
    }
    @media(max-width: 768px) {
        .info-row {
            grid-template-columns: 1fr;
        }
        .dashboard-container {
            padding: 30px 20px;
        }
        .main-content {
            padding-top: 70px;
        }
    }
    </style>

    <div class="main-content">
        <div class="dashboard-container">
            <div class="welcome-header">
                <h2>Welcome, Admin!</h2>
            </div>
            <p class="welcome-subtext">Welcome to your dashboard.</p>
            
            <div class="info-row">
            <div class="info-card info-green">
                <div class="info-left">
                    <div class="label">Total Students</div>
                    <div class="big"><?php echo intval($totalStudents); ?></div>
                </div>
                <div class="info-icon"><i class="fa-solid fa-user-graduate"></i></div>
            </div>

            <div class="info-card info-orange">
                <div class="info-left">
                    <div class="label">Total Advisers</div>
                    <div class="big"><?php echo intval($totalAdvisers); ?></div>
                </div>
                <div class="info-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
            </div>

            <div class="info-card info-blue">
                <div class="info-left">
                    <div class="label">Total Sections</div>
                    <div class="big"><?php echo intval($totalSections); ?></div>
                </div>
                <div class="info-icon"><i class="fa-solid fa-layer-group"></i></div>
            </div>

            <div class="info-card info-purple">
                <div class="info-left">
                    <div class="label">Subject Teacher</div>
                    <div class="big"><?php echo intval($totalsubject_Teachers); ?></div>
                </div>
                <div class="info-icon"><i class="fa-solid fa-award"></i></div>
            </div>
        </div>
        </div>
    </div>
</body>
</html>
