<?php include "adminNavbar.php"; ?>
<?php
session_start();
include "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sectionName = $_POST['section_name'];
    $sectionDescription = $_POST['section_description'];

    if (!empty($sectionName) && !empty($sectionDescription)) {
        $conn = dbConnect();
        $stmt = $conn->prepare("INSERT INTO sections (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $sectionName, $sectionDescription);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Section added successfully!";
        } else {
            $_SESSION['message'] = "Error adding section: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        $_SESSION['message'] = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Section</title>
    <link rel="stylesheet" href="../CSS/Admin/adminTeachers.css">
</head>

<body>
    <h1>Add New Section</h1>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>
    <form method="POST" action="">
        <label for="section_name">Section Name:</label>
        <input type="text" id="section_name" name="section_name" required>
        
        <label for="section_description">Section Description:</label>
        <textarea id="section_description" name="section_description" required></textarea>
        
        <button type="submit">Add Section</button>
    </form>
</body>

</html>