<?php 
include "teacherNavbar.php"; 
include "../PHP/dbconnection.php"; // Include your database connection file

// Fetch the section ID or name for the adviser
$adviserEmailAddress = $_SESSION['advisers']; // Assuming adviser ID is stored in session

$query = "SELECT adviserSection FROM advisers WHERE adviserEmailAddress = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $adviserEmailAddress); // Use "s" for string type
    $stmt->execute();
    $result = $stmt->get_result();
    $section = $result->fetch_assoc();

    if ($section) {
        $adviserSection = $section['adviserSection']; // Assuming section ID is stored in the database

        // Fetch students based on the section
        $query = "SELECT lrn, first_name, last_name FROM students WHERE section = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $adviserSection); // Use "s" for string type
        $stmt->execute();
        $students = $stmt->get_result();
    } else {
        $students = null;
    }
} else {
    die("Query preparation failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adviser Student List</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Students in Your Section</h1>
        <?php if ($students && $students->num_rows > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>LRN</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['lrn']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No students found for your section.</p>
        <?php endif; ?>
    </div>
</body>
</html>
