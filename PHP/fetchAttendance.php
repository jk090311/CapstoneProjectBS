<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educguarddb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Get date filter from GET parameter, default to today's date
$dateFilter = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Use prepared statement with date filtering
$sql = "SELECT rfid_number, CONCAT(first_name, ' ', last_name) AS name, time_in, time_out, date_logged FROM attendance WHERE date_logged = ? ORDER BY time_in ASC";
$stmt = $conn->prepare($sql);

$data = [];
if ($stmt) {
    $stmt->bind_param("s", $dateFilter);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "Error preparing statement: " . $conn->error]);
    exit;
}

$conn->close();
echo json_encode($data);
?>
