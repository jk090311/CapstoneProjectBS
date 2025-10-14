<?php
session_start();
// Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: ../PHPmain/index.php');
    exit();
}

// DB connection
require_once __DIR__ . '/../PHP/dbconnection.php';

$studentId = (int)$_SESSION['student_id'];
// Determine conversation partner (could be an adviser or a subject teacher)
$partnerId = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : 0;
$partnerType = isset($_GET['type']) && in_array($_GET['type'], ['adviser','st']) ? $_GET['type'] : 'adviser';

// Fetch partner full name for header display
$partnerName = '';
if ($partnerId) {
    if ($partnerType === 'adviser') {
        $aq2 = $conn->prepare("SELECT adviserFullName FROM advisers WHERE ID = ? LIMIT 1");
        if ($aq2) {
            $aq2->bind_param('i', $partnerId);
            $aq2->execute();
            $r2 = $aq2->get_result();
            if ($r2 && $row2 = $r2->fetch_assoc()) {
                $partnerName = htmlspecialchars($row2['adviserFullName']);
            }
            $aq2->close();
        }
    } else { // subject teacher
        $sq2 = $conn->prepare("SELECT stFullName FROM subject_teachers WHERE stID = ? LIMIT 1");
        if ($sq2) {
            $sq2->bind_param('i', $partnerId);
            $sq2->execute();
            $r2 = $sq2->get_result();
            if ($r2 && $row2 = $r2->fetch_assoc()) {
                $partnerName = htmlspecialchars($row2['stFullName']);
            }
            $sq2->close();
        }
    }
}

// Non-JS fallback: handle direct POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['messages'])) {
    $outgoing_id = $studentId;
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

    // Preserve the partner type in the redirect (default to adviser)
    $redirectType = isset($_POST['partner_type']) && in_array($_POST['partner_type'], ['adviser','st']) ? $_POST['partner_type'] : 'adviser';
    header("Location: StudentMessage.php?id=" . urlencode($incoming_id) . "&type=" . urlencode($redirectType));
    exit();
}

// Include student navbar
include __DIR__ . "/studentNavbar.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Messages</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/Teacher/designMessages.css">
    <link rel="stylesheet" href="../CSS/messages-common.css">
    <link rel="stylesheet" href="../CSS/send-button.css">
    <style>
        .chat-container{max-width:900px;margin:40px auto}
        /* keep minimal per-page tweaks only; bubble styles are in messages-common.css */
        .chat-box{max-height:520px;overflow-y:auto;padding:15px;background:#f7f7f7;border-radius:6px}
    </style>
</head>
<body>
<div class="messages-page">
    <div class="messages-inner">
    <aside class="messages-sidebar">
        <div class="adviser-list">
            <h5>Adviser / Sub Teacher</h5>
            <div class="list-group">
                <?php
                // Unified contacts list: advisers and subject teachers
                $sql = "SELECT ID as person_id, adviserFullName as person_name, 'adviser' as person_type FROM advisers UNION SELECT stID as person_id, stFullName as person_name, 'st' as person_type FROM subject_teachers ORDER BY person_name ASC";
                $res = $conn->query($sql);
                if ($res) {
                    while ($row = $res->fetch_assoc()) {
                        $pid = (int)$row['person_id'];
                        $pname = htmlspecialchars($row['person_name']);
                        $ptype = $row['person_type'] === 'st' ? 'st' : 'adviser';
                        $active = ($pid === $partnerId && $partnerType === $ptype) ? ' active' : '';
                        echo "<a href=\"StudentMessage.php?id={$pid}&type={$ptype}\" class=\"list-group-item list-group-item-action{$active}\">{$pname}</a>";
                    }
                }
                ?>
            </div>
        </div>
    </aside>
    <section class="messages-panel">
                <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><?php echo $partnerId ? ($partnerName ?: 'Conversation') : 'Messages'; ?></h5>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!$partnerId): ?>
                        <p>Select a contact on the left to view the conversation.</p>
                    <?php else: ?>
                        <div class="chat-box" id="chat-box">
                            <?php
                            // Fetch conversation between student and adviser
                            $stmt = $conn->prepare("SELECT msg_id, incoming_msg_id, outgoing_msg_id, msg, is_read, created_at FROM mesages WHERE (incoming_msg_id = ? AND outgoing_msg_id = ?) OR (incoming_msg_id = ? AND outgoing_msg_id = ?) ORDER BY msg_id ASC");
                            if ($stmt) {
                                $stmt->bind_param("iiii", $studentId, $partnerId, $partnerId, $studentId);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($row = $res->fetch_assoc()) {
                                    $msg = htmlspecialchars($row['msg']);
                                    $out = (int)$row['outgoing_msg_id'];
                                    $mid = (int)$row['msg_id'];
                                    $created = isset($row['created_at']) ? htmlspecialchars($row['created_at']) : date('Y-m-d H:i');
                                    // simple placeholder avatar (replace with real user avatar URL if available)
                                    $avatar = '../Assets/user_17827179.png';
                                    if ($out === $studentId) {
                                        echo "<div class=\"outgoing-msg\" data-msg-id=\"{$mid}\">"
                                            . "<img class=\"msg-avatar\" src=\"{$avatar}\" alt=\"me\">"
                                            . "<div class=\"message-content\"><div class=\"message-text\">{$msg}</div><div class=\"message-time\">" . htmlspecialchars($created) . "</div></div>"
                                            . "</div>";
                                    } else {
                                        echo "<div class=\"incoming-msg\" data-msg-id=\"{$mid}\">"
                                            . "<img class=\"msg-avatar\" src=\"{$avatar}\" alt=\"user\">"
                                            . "<div class=\"message-content\"><div class=\"message-text\">{$msg}</div><div class=\"message-time\">" . htmlspecialchars($created) . "</div></div>"
                                            . "</div>";
                                    }
                                }
                                $stmt->close();
                            }
                            ?>
                        </div>

                        <form id="chat-form" method="POST" action="StudentMessage.php?id=<?php echo urlencode($partnerId); ?>&type=<?php echo htmlspecialchars($partnerType); ?>" class="mt-3 typing-area fixed">
                            <input type="hidden" name="incoming_id" id="incoming_id" value="<?php echo htmlspecialchars($partnerId); ?>">
                            <input type="hidden" name="partner_type" id="partner_type" value="<?php echo htmlspecialchars($partnerType); ?>">
                            <div class="input-group">
                                <input type="text" name="messages" id="message-input" class="form-control" placeholder="Type a message here..." required>
                                <button class="send-btn" type="submit" aria-label="Send">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    </div>
</div>

<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script>
// scroll chat to bottom
var chatBox = document.getElementById('chat-box');
if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
</script>

<script>
// AJAX send + polling
(function(){
    var chatBox = document.getElementById('chat-box');
    var form = document.getElementById('chat-form');
    if (!chatBox || !form) return;

    var input = document.getElementById('message-input');
    var partnerId = <?php echo json_encode($partnerId); ?>;
    var partnerType = <?php echo json_encode($partnerType); ?>;
    var studentId = <?php echo json_encode($studentId); ?>;
    var lastMsgId = 0;

    // compute lastMsgId from existing messages
    var existing = chatBox.querySelectorAll('[data-msg-id]');
    existing.forEach(function(el){
        var id = parseInt(el.getAttribute('data-msg-id') || '0', 10);
        if (id > lastMsgId) lastMsgId = id;
    });

    const avatarUrl = <?php echo json_encode('../Assets/user_17827179.png'); ?>;
    function appendMessage(text, cls, id, timestamp){
        var row = document.createElement('div');
        row.className = cls;
        if (id) row.setAttribute('data-msg-id', id);

        var img = document.createElement('img');
        img.className = 'msg-avatar';
        img.src = avatarUrl;
        img.alt = 'user';

        var content = document.createElement('div');
        content.className = 'message-content';

        var textDiv = document.createElement('div');
        textDiv.className = 'message-text';
        textDiv.textContent = text;

        var timeDiv = document.createElement('div');
        timeDiv.className = 'message-time';
        timeDiv.textContent = timestamp || new Date().toISOString().replace('T',' ').slice(0,19);

        content.appendChild(textDiv);
        content.appendChild(timeDiv);

        if(String(cls).indexOf('outgoing') !== -1){
            row.appendChild(img);
            row.appendChild(content);
        } else {
            row.appendChild(img);
            row.appendChild(content);
        }

        chatBox.appendChild(row);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    form.addEventListener('submit', function(e){
        e.preventDefault();
        var message = input.value.trim();
        if (!message) return;
        var ts = new Date().toISOString().replace('T',' ').slice(0,19);
        appendMessage(message, 'outgoing-msg', null, ts);
        input.value = '';

    var fd = new FormData();
    fd.append('incoming_id', partnerId);
    fd.append('partner_type', partnerType);
    fd.append('message', message);

        fetch('../PHPAdviser/sendMessage.php', { method: 'POST', body: fd })
        .then(function(r){ return r.json(); })
        .then(function(data){
            if(data && data.success && data.message) {
                lastMsgId = Math.max(lastMsgId, Number(data.message.msg_id || 0));
            }
        }).catch(function(err){ console.error('send error', err); });
    });

    function poll(){
    var params = new URLSearchParams({ user_a: studentId, user_b: partnerId, since_id: lastMsgId });
        fetch('../PHPAdviser/getMessages.php?' + params.toString())
        .then(function(r){ return r.json(); })
        .then(function(data){
            if (!data || !data.success) return;
            data.messages.forEach(function(m){
                if (Number(m.msg_id) <= lastMsgId) return;
                if (String(m.outgoing_msg_id) === String(studentId)) {
                    appendMessage(m.msg, 'outgoing-msg', m.msg_id);
                } else {
                    appendMessage(m.msg, 'incoming-msg', m.msg_id);
                }
                lastMsgId = Math.max(lastMsgId, Number(m.msg_id || 0));
            });
        }).catch(function(err){ console.error('poll error', err); });
    }

    poll();
    setInterval(poll, 2000);
})();
</script>
</body>
</html>
