<?php
session_start();
// ensure teacher logged in
if (!isset($_SESSION['unique_id'])) {
    header('Location: ../PHPmain/index.php');
    exit();
}

require_once __DIR__ . '/../PHP/dbconnection.php';

$teacherId = (int)($_SESSION['unique_id'] ?? 0);
$studentId = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle non-JS POST submissions from the typing form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['messages'])) {
    $outgoing_id = $teacherId;
    $incoming_id = isset($_POST['incoming_id']) && ctype_digit($_POST['incoming_id']) ? (int)$_POST['incoming_id'] : 0;
    $message = trim($_POST['messages']);

    if (!empty($message) && $incoming_id > 0) {
        $insert = $conn->prepare("INSERT INTO mesages (incoming_msg_id, outgoing_msg_id, msg, is_read) VALUES (?, ?, ?, 0)");
        if ($insert) {
            $insert->bind_param("iis", $incoming_id, $outgoing_id, $message);
            $insert->execute();
            $insert->close();
        }
    }

    header("Location: TeacherMessages.php?id=" . urlencode($incoming_id));
    exit();
}

// fetch student full name for header display
$studentName = '';
if ($studentId) {
    $sq2 = $conn->prepare("SELECT first_name, last_name FROM students WHERE student_id = ? LIMIT 1");
    if ($sq2) {
        $sq2->bind_param('i', $studentId);
        $sq2->execute();
        $r2 = $sq2->get_result();
        if ($r2 && $srow = $r2->fetch_assoc()) {
            $studentName = htmlspecialchars(trim($srow['first_name'] . ' ' . $srow['last_name']));
        }
        $sq2->close();
    }
}

include __DIR__ . '/teacherNavbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher Messages</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/messages-common.css">
    <link rel="stylesheet" href="../CSS/send-button.css">
</head>
<body>
<div class="messages-page">
    <div class="messages-inner">
    <aside class="messages-sidebar">
        <div class="adviser-list">
            <h5>Students</h5>
            <div class="list-group">
                <?php
                // determine adviser section from session email
                $adviserSection = '';
                $adviserEmail = $_SESSION['user_email'] ?? '';
                if ($adviserEmail) {
                    $q = $conn->prepare("SELECT adviserSection FROM advisers WHERE adviserEmailAddress = ? LIMIT 1");
                    if ($q) {
                        $q->bind_param('s', $adviserEmail);
                        $q->execute();
                        $r = $q->get_result();
                        if ($r && $row = $r->fetch_assoc()) {
                            $adviserSection = $row['adviserSection'];
                        }
                        $q->close();
                    }
                }

                if (!empty($adviserSection)) {
                    $sq = $conn->prepare("SELECT student_id, first_name, last_name FROM students WHERE section = ? ORDER BY first_name ASC");
                    if ($sq) {
                        $sq->bind_param('s', $adviserSection);
                        $sq->execute();
                        $resS = $sq->get_result();
                        while ($s = $resS->fetch_assoc()) {
                            $sid = (int)$s['student_id'];
                            $name = htmlspecialchars(trim($s['first_name'] . ' ' . $s['last_name']));
                            $active = ($sid === $studentId) ? ' active' : '';
                            echo "<a href=\"TeacherMessages.php?id={$sid}\" class=\"list-group-item list-group-item-action{$active}\">{$name}</a>";
                        }
                        $sq->close();
                    } else {
                        echo '<div class="list-group-item">No students found</div>';
                    }
                } else {
                    echo '<div class="list-group-item">No students found</div>';
                }
                ?>
            </div>
        </div>
    </aside>
    <section class="messages-panel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                            <h5 class="mb-0"><?php echo $studentId ? ($studentName ?: 'Conversation') : 'Conversation'; ?></h5>
                        </div>
                </div>
                <div class="card-body">
                    <?php if (!$studentId): ?>
                        <p>Select a student on the left to view conversation.</p>
                    <?php else: ?>
                        <div class="chat-box" id="chat-box">
                            <?php
                            if ($studentId) {
                                $stmt = $conn->prepare("SELECT msg_id, incoming_msg_id, outgoing_msg_id, msg, created_at FROM mesages WHERE (incoming_msg_id = ? AND outgoing_msg_id = ?) OR (incoming_msg_id = ? AND outgoing_msg_id = ?) ORDER BY msg_id ASC");
                                if ($stmt) {
                                    $stmt->bind_param('iiii',$studentId,$teacherId,$teacherId,$studentId);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while($row=$res->fetch_assoc()){
                                        $mid=(int)$row['msg_id'];
                                        $msg=htmlspecialchars($row['msg']);
                                        $created = isset($row['created_at']) ? htmlspecialchars($row['created_at']) : date('Y-m-d H:i');
                                        $avatar = '../Assets/user_9477892.png';
                                        if((int)$row['outgoing_msg_id']=== $teacherId) {
                                            echo "<div class=\"message-row outgoing-msg\" data-msg-id=\"{$mid}\">"
                                                . "<div class=\"message-bubble\">{$msg}<span class=\"message-meta\">".htmlspecialchars($created)."</span></div>"
                                                . "<img class=\"msg-avatar\" src=\"{$avatar}\" alt=\"me\">"
                                                . "</div>";
                                        } else {
                                            echo "<div class=\"message-row incoming-msg\" data-msg-id=\"{$mid}\">"
                                                . "<img class=\"msg-avatar\" src=\"{$avatar}\" alt=\"user\">"
                                                . "<div class=\"message-bubble\">{$msg}<span class=\"message-meta\">".htmlspecialchars($created)."</span></div>"
                                                . "</div>";
                                        }
                                    }
                                    $stmt->close();
                                }
                            }
                            ?>
                        </div>

                        <form id="chat-form" method="POST" action="TeacherMessages.php?id=<?php echo $studentId; ?>" class="typing-area fixed">
                            <input type="hidden" name="outgoing_id" value="<?php echo htmlspecialchars($_SESSION['unique_id'] ?? ''); ?>">
                            <input type="hidden" name="incoming_id" value="<?php echo $studentId; ?>">
                            <input type="text" id="message-input" name="messages" class="input-field form-control" placeholder="Type a message here..." required>
                            <button type="submit" class="send-btn" aria-label="Send">
                                <!-- paper plane icon -->
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    </div>
</div>

<script>
(function(){
    const chatBox=document.getElementById('chat-box');
    const form=document.getElementById('chat-form');
    if(!chatBox||!form) return;
    const input=document.getElementById('message-input');
    const teacherId = <?php echo json_encode($teacherId); ?>;
    const studentId = <?php echo json_encode($studentId); ?>;
    let lastMsgId=0;
    // populate lastMsgId
    document.querySelectorAll('[data-msg-id]').forEach(el=>{ lastMsgId=Math.max(lastMsgId, Number(el.getAttribute('data-msg-id')||0));});
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

        // avatar on right for outgoing, left for incoming
        if(String(cls).indexOf('outgoing') !== -1){
            row.appendChild(content);
            row.appendChild(img);
        } else {
            row.appendChild(img);
            row.appendChild(content);
        }

        chatBox.appendChild(row);
        chatBox.scrollTop = chatBox.scrollHeight;
    }
    form.addEventListener('submit',function(e){
        e.preventDefault();
        const m=(input.value||'').trim();
        if(!m) return;
        const ts = new Date().toISOString().replace('T',' ').slice(0,19);
        appendMessage(m,'outgoing-msg',null,ts);
        input.value='';
        const fd=new FormData();fd.append('incoming_id',studentId);fd.append('message',m);
        fetch('../PHPAdviser/sendMessage.php',{method:'POST',body:fd}).then(r=>r.json()).then(d=>{if(d&&d.success) lastMsgId=Math.max(lastMsgId, Number(d.message.msg_id||0));}).catch(()=>{});
    });
    function poll(){const params=new URLSearchParams({user_a:teacherId,user_b:studentId,since_id:lastMsgId});fetch('../PHPAdviser/getMessages.php?'+params.toString()).then(r=>r.json()).then(data=>{if(!data||!data.success) return;data.messages.forEach(m=>{if(Number(m.msg_id)<=lastMsgId) return; if(String(m.outgoing_msg_id)===String(teacherId)) appendMessage(m.msg,'outgoing-msg',m.msg_id); else appendMessage(m.msg,'incoming-msg',m.msg_id); lastMsgId=Math.max(lastMsgId, Number(m.msg_id||0));});}).catch(()=>{});}poll();setInterval(poll,2000);
})();
</script>
</body>
</html>




