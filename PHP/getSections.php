<?php
// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get selected grade level
$gradeLevel = $_POST['gradeLevel'];

// Fetch sections for the selected grade level
$query = "SELECT section_name FROM class_section WHERE grade_level = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $gradeLevel);
$stmt->execute();
$result = $stmt->get_result();

// Output section options
while ($row = $result->fetch_assoc()) {
    echo '<option value="' . htmlspecialchars($row['section_name']) . '">' . htmlspecialchars($row['section_name']) . '</option>';
}

// Close connection
$stmt->close();
$connection->close();
?>
