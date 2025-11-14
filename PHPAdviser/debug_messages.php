<?php
// Protected debug page: shows latest messages and session state.
// Place this in PHPAdviser/ and access while logged in as adviser.
session_start();
// Simple auth: only allow if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Forbidden: not logged in";
    exit;
}

// include DB connection (adjust path if needed)
require_once __DIR__ . '/../PHP/dbconnection.php';

// Fetch last 50 messages
$sql = "SELECT msg_id, incoming_msg_id, outgoing_msg_id, msg FROM mesages ORDER BY msg_id DESC LIMIT 50";
$res = $conn->query($sql);
$rows = [];
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $rows[] = $r;
    }
}

// Tail of debug log if exists
$logPath = __DIR__ . '/../logs/send_message_debug.log';
$logTail = '';
if (file_exists($logPath)) {
    $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $tail = array_slice($lines, -50);
    $logTail = implode("\n", $tail);
}

// Simple HTML output
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Debug - Messages</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;padding:16px}pre{background:#f6f8fa;padding:12px;border:1px solid #e1e4e8;overflow:auto}</style>
</head>
<body>
    <h2>Session</h2>
    <pre><?php echo htmlspecialchars(json_encode($_SESSION, JSON_PRETTY_PRINT)); ?></pre>

    <h2>Latest mesages rows (desc)</h2>
    <pre><?php echo htmlspecialchars(json_encode($rows, JSON_PRETTY_PRINT)); ?></pre>

    <h2>send_message debug log (tail)</h2>
    <pre><?php echo htmlspecialchars($logTail); ?></pre>

    <p>Note: This page is for debugging. Remove when done.</p>
</body>
</html>




