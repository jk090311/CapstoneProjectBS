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
    .info-row{display:flex;gap:20px;margin-top:20px;flex-wrap:wrap}
    .info-card{flex:1;min-width:260px;padding:18px;border-radius:8px;color:#fff;display:flex;align-items:center;justify-content:space-between;box-shadow:0 2px 6px rgba(0,0,0,0.08)}
    .info-left{display:flex;flex-direction:column}
    .info-left .label{font-size:12px;letter-spacing:1px;text-transform:uppercase;opacity:.9}
    .info-left .big{font-size:28px;font-weight:700;margin-top:8px}
    .info-icon{font-size:42px;opacity:.95}
    .info-green{background:#2e8b57}
    .info-orange{background:#ff9800}
    .info-blue{background:#1e90ff}
    .info-purple{background:#7b5cff}
    @media(max-width:720px){.info-card{min-width:200px}}
    </style>

    <div class="container">
        <div class="section">
            <h2>Welcome, Admin!</h2>
            <p>Welcome to your dashboard.</p>
        </div>
        <!-- Info cards (wide rectangular style) -->
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
