<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once __DIR__ . '/../PHP/dbconnection.php';

// simple debug logger
$gmLogDir = __DIR__ . '/../logs';
if (!is_dir($gmLogDir)) @mkdir($gmLogDir, 0755, true);
@file_put_contents($gmLogDir . '/get_messages_debug.log', date('Y-m-d H:i:s') . " GET:" . json_encode($_GET) . "\n", FILE_APPEND);

$currentUser = $_SESSION['unique_id'] ?? null;

$userA = $_GET['user_a'] ?? null;
$userB = $_GET['user_b'] ?? null;
$since = isset($_GET['since_id']) && ctype_digit($_GET['since_id']) ? (int)$_GET['since_id'] : 0;

if (!ctype_digit(strval($userA)) || !ctype_digit(strval($userB))) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid users']);
    exit;
}
$userA = (int)$userA;
$userB = (int)$userB;

// Be tolerant of older table schema that may not have is_read/created_at
$query = "SELECT msg_id, incoming_msg_id, outgoing_msg_id, msg
          FROM mesages
          WHERE ((incoming_msg_id = ? AND outgoing_msg_id = ?) OR (incoming_msg_id = ? AND outgoing_msg_id = ?))";

if ($since > 0) $query .= " AND msg_id > ?";

$query .= " ORDER BY msg_id ASC";

if ($stmt = $conn->prepare($query)) {
    if ($since > 0) {
        $stmt->bind_param('iiiii', $userA, $userB, $userB, $userA, $since);
    } else {
        $stmt->bind_param('iiii', $userA, $userB, $userB, $userA);
    }
    $stmt->execute();
    $res = $stmt->get_result();
    $messages = [];
    // We won't attempt to mark-read unless the is_read column exists in the result
    $hasIsRead = false;
    $toMarkRead = [];

    while ($row = $res->fetch_assoc()) {
        $messages[] = $row;
        if (array_key_exists('is_read', $row)) {
            $hasIsRead = true;
            if ($currentUser && (int)$row['incoming_msg_id'] === (int)$currentUser && intval($row['is_read']) === 0) {
                $toMarkRead[] = (int)$row['msg_id'];
            }
        }
    }
    $stmt->close();

    // log fetched messages for debugging
    @file_put_contents($gmLogDir . '/get_messages_debug.log', date('Y-m-d H:i:s') . " RESULT:" . json_encode(array_column($messages, 'msg_id')) . "\n", FILE_APPEND);

    if ($hasIsRead && !empty($toMarkRead)) {
        $placeholders = implode(',', array_fill(0, count($toMarkRead), '?'));
        $types = str_repeat('i', count($toMarkRead));
        $sql = "UPDATE mesages SET is_read = 1 WHERE msg_id IN ($placeholders)";
        $stmt2 = $conn->prepare($sql);
        // dynamic bind
        $stmt2->bind_param($types, ...$toMarkRead);
        $stmt2->execute();
        $stmt2->close();
        foreach ($messages as &$m) {
            if (in_array((int)$m['msg_id'], $toMarkRead, true)) $m['is_read'] = '1';
        }
    }

    echo json_encode(['success' => true, 'messages' => $messages]);
    exit;
}

http_response_code(500);
echo json_encode(['error' => 'Prepare failed']);
exit;
?>
