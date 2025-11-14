<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// Use existing DB connection file if available
include_once __DIR__ . '/../PHP/dbconnection.php';

// debug log helper (local only)
function dbg_log($line) {
    $logdir = __DIR__ . '/../logs';
    if (!is_dir($logdir)) @mkdir($logdir, 0755, true);
    $file = $logdir . '/send_message_debug.log';
    $ts = date('Y-m-d H:i:s');
    @file_put_contents($file, "[{$ts}] " . $line . "\n", FILE_APPEND);
}

dbg_log('POST: ' . json_encode($_POST));

$currentUser = $_SESSION['unique_id'] ?? null;
// also accept student session id if teacher session isn't present
$studentSession = $_SESSION['student_id'] ?? null;
$outgoing = $currentUser ?: $studentSession; // prefer teacher session, fallback to student
dbg_log('initial outgoing: ' . var_export($outgoing, true));

// If outgoing still not resolved, try to derive it from other session info
if (!$outgoing) {
    // If the logged-in user is an adviser, map their user/email to advisers.ID
    $role = $_SESSION['user_role'] ?? '';
    $email = $_SESSION['user_email'] ?? '';
    if ($role === 'adviser' && $email) {
        $q = $conn->prepare('SELECT ID FROM advisers WHERE adviserEmailAddress = ? LIMIT 1');
        if ($q) {
            $q->bind_param('s', $email);
            $q->execute();
            $r = $q->get_result();
            if ($r && $row = $r->fetch_assoc()) {
                $outgoing = (int)$row['ID'];
            }
            $q->close();
        }
    } elseif ($role === 'subject_teacher' && $email) {
        $q = $conn->prepare('SELECT stID FROM subject_teachers WHERE stEmail = ? LIMIT 1');
        if ($q) {
            $q->bind_param('s', $email);
            $q->execute();
            $r = $q->get_result();
            if ($r && $row = $r->fetch_assoc()) {
                $outgoing = (int)$row['stID'];
            }
            $q->close();
        }
    }
    // As last-resort fallback, use generic user_id if present (not ideal but keeps compatibility)
    if (!$outgoing && !empty($_SESSION['user_id'])) {
        $outgoing = (int)$_SESSION['user_id'];
    }
}
dbg_log('final outgoing: ' . var_export($outgoing, true));

$incoming = $_POST['incoming_id'] ?? null;
$msg = trim($_POST['message'] ?? '');

if (!ctype_digit(strval($incoming)) || $msg === '' || !$outgoing) {
    dbg_log('validation failed - incoming:' . var_export($incoming, true) . ' msg:' . var_export($msg, true) . ' outgoing:' . var_export($outgoing, true));
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$incoming = (int)$incoming;
$outgoing = (int)$outgoing;

// Use a minimal insert that's compatible with older schemas (no is_read column required)
$sql = "INSERT INTO mesages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('iis', $incoming, $outgoing, $msg);
    if ($stmt->execute()) {
        // use connection's insert id
        $insert_id = (int)$conn->insert_id;
        dbg_log('insert executed, insert_id: ' . $insert_id);
        $stmt->close();

        // fetch the inserted row using minimal columns (avoid selecting created_at/is_read which may not exist)
        $sel = $conn->prepare('SELECT msg_id, incoming_msg_id, outgoing_msg_id, msg FROM mesages WHERE msg_id = ?');
        if ($sel) {
            $sel->bind_param('i', $insert_id);
            $sel->execute();
            $res = $sel->get_result();
            $row = $res ? $res->fetch_assoc() : null;
            $sel->close();
            dbg_log('fetched row: ' . json_encode($row));
            echo json_encode(['success' => true, 'message' => $row]);
            exit;
        }
    }
    $stmt->close();
}

dbg_log('insert failed, db_error: ' . $conn->error);
http_response_code(500);
echo json_encode(['error' => 'Insert failed', 'db_error' => $conn->error]);
exit;
?>




