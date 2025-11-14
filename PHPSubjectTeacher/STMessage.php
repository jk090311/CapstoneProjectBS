<?php include "subjectTeacherNavbar.php" ?>
<?php
// Ensure a session is active. subjectTeacherNavbar.php already starts the session
// but if this file is loaded directly we guard the call to avoid notices.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: ../PHPmain/index.php");
    exit();
}

$required_role = "subject_teacher";
if ($_SESSION['user_role'] != $required_role) {
    if ($_SESSION['user_role'] == "admin") {
        header("Location: ../PHPAdviser/dashboardAdmin.php");
    } else if ($_SESSION['user_role'] == "adviser") {
        header("Location: ../PHPAdviser/dashboardAdviser.php");
    } else if ($_SESSION['user_role'] == "student") {
        header("Location: ../PHPStudent/dashboardStudent.php");
    }
    exit();
}

// DB connection
$servername = "localhost";$([Environment]::NewLine)$username = "root";$([Environment]::NewLine)$password = "";$([Environment]::NewLine)$dbname = "educguarddb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get Student ID (normalize as integer) - use the same `student_id` identifier as other message pages
$studentId = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : 0;
$firstName = '';
$lastName = '';
$userName = '';
$email = 'No email available';
$gradeLevel = 'Not specified';

// Get student info from DB
if (!empty($studentId)) {
    $query = "SELECT first_name, last_name, email, grade_level FROM students WHERE student_id = ? LIMIT 1";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
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

// Handle message send (non-AJAX fallback)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['messages'])) {
    $outgoing_id = isset($_POST['outgoing_id']) && ctype_digit(strval($_POST['outgoing_id'])) ? (int)$_POST['outgoing_id'] : 0;
    $incoming_id = isset($_POST['incoming_id']) && ctype_digit(strval($_POST['incoming_id'])) ? (int)$_POST['incoming_id'] : 0;
    $message = trim($_POST['messages']);

    if (!empty($message) && $incoming_id > 0 && $outgoing_id > 0) {
        $insert = $conn->prepare("INSERT INTO mesages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)");
        if ($insert) {
            $insert->bind_param("iis", $incoming_id, $outgoing_id, $message);
            $insert->execute();
            $insert->close();
        }
    }

    // Prevent form resubmission - redirect back to this page
    header("Location: STMessage.php?id=" . urlencode($incoming_id));
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
    <link rel="stylesheet" href="../CSS/messages-common.css">
</head>

<body>
    <div class="messages-page container">
        <aside class="messages-sidebar">
            <div class="adviser-list">
                <h5>Students</h5>
                <div class="list-group">
                    <?php
                    // List all students so subject teachers can message any student (uses student_id)
                    $sq = $conn->prepare("SELECT student_id, first_name, last_name FROM students ORDER BY first_name ASC");
                    if ($sq) {
                        $sq->execute();
                        $resS = $sq->get_result();
                        if ($resS && $resS->num_rows > 0) {
                            while ($s = $resS->fetch_assoc()) {
                                $sid = (int)$s['student_id'];
                                $name = htmlspecialchars(trim($s['first_name'] . ' ' . $s['last_name']));
                                $active = ($sid === $studentId) ? ' active' : '';
                                echo "<a href=\"STMessage.php?id={$sid}\" class=\"list-group-item list-group-item-action{$active}\">{$name}</a>";
                            }
                        } else {
                            echo '<div class="list-group-item">No students found</div>';
                        }
                        $sq->close();
                    } else {
                        echo '<div class="list-group-item">No students found</div>';
                    }
                    ?>
                </div>
            </div>
        </aside>
    <section class="messages-panel">
        <div class="card shadow compact">
            <header class="card-header d-flex align-items-center">
                <div>
                    <h5 class="mb-0"><?php echo $userName ? $userName : 'Conversation'; ?></h5>
                </div>
            </header>

            <div class="chat-box" id="chat-box">
                <?php
                if (!empty($studentId) && isset($_SESSION['unique_id'])) {
                    $query = "SELECT * FROM mesages WHERE 
                    (incoming_msg_id = ? AND outgoing_msg_id = ?) OR 
                    (incoming_msg_id = ? AND outgoing_msg_id = ?)
                    ORDER BY msg_id ASC";

                    if ($stmt = $conn->prepare($query)) {
                        // use integer binding for ids
                        $stmt->bind_param("iiii", $studentId, $_SESSION['unique_id'], $_SESSION['unique_id'], $studentId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            $msgContent = htmlspecialchars($row['msg']);
                            $mid = (int)$row['msg_id'];
                            $created = isset($row['created_at']) ? htmlspecialchars($row['created_at']) : date('Y-m-d H:i');
                            $avatar = '../Assets/user_9477892.png';
                            if ($row['outgoing_msg_id'] === $_SESSION['unique_id']) {
                                echo "<div class='outgoing-msg' data-msg-id='{$mid}'>"
                                    . "<img class='msg-avatar' src='{$avatar}' alt='me'>"
                                    . "<div class='message-content'><div class='message-text'>{$msgContent}</div><div class='message-time'>{$created}</div></div>"
                                    . "</div>";
                            } else {
                                echo "<div class='incoming-msg' data-msg-id='{$mid}'>"
                                    . "<img class='msg-avatar' src='{$avatar}' alt='user'>"
                                    . "<div class='message-content'><div class='message-text'>{$msgContent}</div><div class='message-time'>{$created}</div></div>"
                                    . "</div>";
                            }
                        }
                        $stmt->close();
                    }
                }
                ?>
            </div>

            <form method="POST" action="STMessage.php?id=<?php echo $studentId; ?>" class="typing-area fixed">
                <input type="hidden" name="outgoing_id" value="<?php echo htmlspecialchars($_SESSION['unique_id'] ?? ''); ?>">
                <input type="hidden" name="incoming_id" value="<?php echo $studentId; ?>">
                <input type="text" name="messages" class="input-field" placeholder="Type a message here..." required>
                <button type="submit"><i class="fab fa-telegram-plane"></i></button>
            </form>
        </section>
    </div>

    <script src="../JS/teacherMessages.js"></script>
    <script src="../JS/teacherChat.js"></script>
        <script>
        (function(){
            const chatBox = document.querySelector('.chat-box');
            const form = document.querySelector('form.typing-area') || document.querySelector('.typing-area');
            if(!chatBox || !form) return;
            const input = form.querySelector('input[name="messages"]') || form.querySelector('.input-field');
            const outgoingId = '<?php echo $_SESSION['unique_id'] ?? ''; ?>';
            const incomingId = '<?php echo $studentId; ?>';
            let lastMsgId = 0;

                const avatarUrl = <?php echo json_encode('../Assets/user_9477892.png'); ?>;
                function appendMessage(text, cls, id, timestamp){
                    const row = document.createElement('div');
                    row.className = cls;
                    if(id) row.setAttribute('data-msg-id', id);

                    const img = document.createElement('img');
                    img.className = 'msg-avatar';
                    img.src = avatarUrl;
                    img.alt = 'user';

                    const content = document.createElement('div');
                    content.className = 'message-content';

                    const textDiv = document.createElement('div');
                    textDiv.className = 'message-text';
                    textDiv.textContent = text;

                    const timeDiv = document.createElement('div');
                    timeDiv.className = 'message-time';
                    timeDiv.textContent = timestamp || new Date().toISOString().replace('T',' ').slice(0,19);

                    content.appendChild(textDiv);
                    content.appendChild(timeDiv);

                    row.appendChild(img);
                    row.appendChild(content);

                    chatBox.appendChild(row);
                    chatBox.scrollTop = chatBox.scrollHeight;
                }

            form.addEventListener('submit', function(e){
                e.preventDefault();
                const message = (input && input.value || '').trim();
                if(!message) return;
                    const ts = new Date().toISOString().replace('T',' ').slice(0,19);
                    appendMessage(message, 'outgoing-msg', null, ts);
                if(input) input.value='';

                const fd = new FormData();
                fd.append('incoming_id', incomingId);
                fd.append('message', message);
                fetch('../PHPAdviser/sendMessage.php', {method:'POST', body: fd}).then(r=>r.json()).then(data=>{
                    if(data && data.success) lastMsgId = Math.max(lastMsgId, Number(data.message.msg_id||0));
                }).catch(()=>{});
            });

            function poll(){
                const params = new URLSearchParams({user_a: outgoingId, user_b: incomingId, since_id: lastMsgId});
                fetch('../PHPAdviser/getMessages.php?'+params.toString()).then(r=>r.json()).then(data=>{
                    if(!data || !data.success) return;
                    data.messages.forEach(m=>{
                        if(Number(m.msg_id) <= lastMsgId) return;
                        if(String(m.outgoing_msg_id) === String(outgoingId)) appendMessage(m.msg,'outgoing-msg', m.msg_id);
                        else appendMessage(m.msg,'incoming-msg', m.msg_id);
                        lastMsgId = Math.max(lastMsgId, Number(m.msg_id||0));
                    });
                }).catch(()=>{});
            }

            poll(); setInterval(poll, 2000);
        })();
        </script>
</body>

</html>





