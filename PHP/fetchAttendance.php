<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_tracking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT rfid_number, CONCAT(first_name, ' ', last_name) AS name, time_in, time_out, date_logged FROM attendance ORDER BY date_logged DESC, time_in DESC";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();
echo json_encode($data);
?>
