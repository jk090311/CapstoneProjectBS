<?php include "teacherNavbar.php"; ?>
<?php
// Database connection
$servername = "localhost"; // Replace with your database server name
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "educguarddb";   // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize adviserFullName variable
$adviserFullName = "Teacher"; // Default fallback

// Fetch adviser's full name
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
    <title>Teacher Messages</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/Teacher/adviserUserList.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div id="wrapper">
        <section class="users">
            <header>
                <div class="content">
                    <div class="details">
                        <span><?php echo $adviserFullName; ?></span>
                    </div>
                </div>
            </header>

            <div class="search">
                <span class="text">Select a user to chat</span>
                <input type="text" id="searchInput" placeholder="Enter name to search...">
                <button><i class="fas fa-search"></i></button>
            </div>

            <div class="users-list">
                <?php
                // Fetch users from the database - include student_id for better identification
                $query = "SELECT student_id, first_name, last_name FROM students"; 
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $fullName = htmlspecialchars($row['first_name'] . " " . $row['last_name']);
                        $studentId = htmlspecialchars($row['student_id']);
                        
                        echo '<div class="user" data-name="' . $fullName . '" data-student-id="' . $studentId . '">';
                        echo '<a href="TeacherMessages.php?student_id=' . urlencode($studentId) . 
                             '&first_name=' . urlencode($row['first_name']) . 
                             '&last_name=' . urlencode($row['last_name']) . '">';
                        echo '<div class="user-info">';
                        echo '<span class="full-name">' . $fullName . '</span>';
                        echo '</div>';
                        echo '</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="no-users">No students found</div>';
                }
                $conn->close();
                ?>
            </div>
        </section>
    </div>
    
</body>
</html>