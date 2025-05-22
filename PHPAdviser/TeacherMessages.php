<?php
// Start the session
session_start();

// Include navbar
include "teacherNavbar.php";

// DB connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educguarddb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get Student ID
$studentId = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
$firstName = 'Unknown';
$lastName = 'User';
$userName = $firstName . ' ' . $lastName;
$email = 'No email available';
$gradeLevel = 'Not specified';

// Get student info from DB
if (!empty($studentId)) {
    $query = "SELECT first_name, last_name, email, grade_level FROM students WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $studentInfo = $result->fetch_assoc();
            $firstName = htmlspecialchars($studentInfo['first_name']);
            $lastName = htmlspecialchars($studentInfo['last_name']);
            $email = htmlspecialchars($studentInfo['email']);
            $gradeLevel = htmlspecialchars($studentInfo['grade_level']);
            $userName = $firstName . ' ' . $lastName;
        }
        $stmt->close();
    }
}

// Handle message send
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['messages'])) {
    $outgoing_id = $_POST['outgoing_id'];
    $incoming_id = $_POST['incoming_id'];
    $message = trim($_POST['messages']);

    if (!empty($message)) {
        $insert = $conn->prepare("INSERT INTO mesages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $incoming_id, $outgoing_id, $message);
        $insert->execute();
        $insert->close();
    }

    // Prevent form resubmission
    header("Location: TeacherMessages.php?id=$incoming_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Teacher Messages</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../CSS/Teacher/designMessages.css">
    <style>
        .chat-box {
            max-height: 500px;
            overflow-y: auto;
            padding: 15px;
            background: #f1f1f1;
        }

        .incoming-msg,
        .outgoing-msg {
            max-width: 70%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 15px;
            clear: both;
        }

        .incoming-msg {
            background: #dfefff;
            float: left;
        }

        .outgoing-msg {
            background: #c1ffc1;
            float: right;
        }

        .typing-area {
            display: flex;
            padding: 10px;
            background: #fff;
            border-top: 1px solid #ccc;
        }

        .typing-area input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            margin-right: 10px;
        }

        .typing-area button {
            background: #007bff;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 0 20px;
        }
    </style>
</head>

<body>
    <div id="wrapper" class="container mt-3">
        <section class="chat-area card shadow">
            <header class="card-header d-flex justify-content-between align-items-center">
                <button onclick="history.back()" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <div>
                    <h5 class="mb-0"><?php echo $userName; ?></h5>
                    <?php if (!empty($studentId)): ?>
                        <small>
                            <strong>ID:</strong> <?php echo $studentId; ?> |
                            <strong>Email:</strong> <?php echo $email; ?> |
                            <strong>Grade:</strong> <?php echo $gradeLevel; ?>
                        </small>
                    <?php endif; ?>
                </div>
            </header>

            <div class="chat-box">
                <?php
                if (!empty($studentId) && isset($_SESSION['unique_id'])) {
                    $query = "SELECT * FROM mesages WHERE 
                    (incoming_msg_id = ? AND outgoing_msg_id = ?) OR 
                    (incoming_msg_id = ? AND outgoing_msg_id = ?)
                    ORDER BY msg_id ASC";

                    if ($stmt = $conn->prepare($query)) {
                        $stmt->bind_param("ssss", $studentId, $_SESSION['unique_id'], $_SESSION['unique_id'], $studentId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            $msgContent = htmlspecialchars($row['msg']);
                            if ($row['outgoing_msg_id'] === $_SESSION['unique_id']) {
                                echo "<div class='outgoing-msg'>$msgContent</div>";
                            } else {
                                echo "<div class='incoming-msg'>$msgContent</div>";
                            }
                        }
                        $stmt->close();
                    }
                }
                ?>
            </div>

            <form method="POST" action="TeacherMessages.php?id=<?php echo $studentId; ?>" class="typing-area">
                <input type="hidden" name="outgoing_id" value="<?php echo htmlspecialchars($_SESSION['unique_id'] ?? ''); ?>">
                <input type="hidden" name="incoming_id" value="<?php echo $studentId; ?>">
                <input type="text" name="messages" class="input-field" placeholder="Type a message here..." required>
                <button type="submit"><i class="fab fa-telegram-plane"></i></button>
            </form>
        </section>
    </div>

    <script src="../JS/teacherMessages.js"></script>
    <script src="../JS/teacherChat.js"></script>
</body>

</html>