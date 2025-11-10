<?php
header("Content-Type: application/json");

$connection = mysqli_connect("sql211.infinityfree.com", "if0_40275155", "EduGuard202526", "if0_40275155_eduguarddb");

if (!$connection) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['grade_level'])) {
    $grade_level = trim($_POST['grade_level']); // Sanitize input

    $query = "SELECT section_name FROM class_section WHERE grade_level = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $grade_level);
    $stmt->execute();
    $result = $stmt->get_result();

    $sections = [];
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row['section_name'];
    }

    echo json_encode($sections);
} else {
    echo json_encode(["error" => "Invalid request"]);
}

mysqli_close($connection);
?>