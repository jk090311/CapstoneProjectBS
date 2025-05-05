<?php
if (isset($_POST['section'])) {
    $section = $_POST['section'];

    // Connect to the database
    $connection = mysqli_connect("localhost", "root", "", "educguarddb");

    // Check connection
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch grade level and year level for the selected section
    $query = "SELECT section_grade_level, section_year_start_level FROM class_section WHERE section_name = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $section);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data); // Return the data as JSON
    } else {
        echo json_encode(['section_grade_level' => '', 'section_year_start_level' => '']); // Return empty values if no data found
    }

    // Close the connection
    $stmt->close();
    $connection->close();
} else {
    echo json_encode(['error' => 'No section provided']); // Handle missing section
}
?>