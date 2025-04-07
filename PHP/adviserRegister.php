<<<<<<< HEAD
id="submitBtn" name="adviserRegister"
=======
>>>>>>> 83d09df69ff489831a8ab36bbd0a83ca4b1e2d81
<?php
session_start();

// Database connection
$connection = mysqli_connect("localhost", "root", "", "educguarddb");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

<<<<<<< HEAD
if (isset($_POST['adviserRegister'])) {
    // Get form data
=======
// Check if the form is submitted
if (isset($_POST['adviserRegister'])) {
    // Get form data safely
>>>>>>> 83d09df69ff489831a8ab36bbd0a83ca4b1e2d81
    $adviserFullName = mysqli_real_escape_string($connection, $_POST['adviserFullName']);
    $adviserContactNumber = mysqli_real_escape_string($connection, $_POST['adviserContactNumber']);
    $adviserGrLvl = mysqli_real_escape_string($connection, $_POST['adviserGrLvl']);
    $adviserEmailAddress = mysqli_real_escape_string($connection, $_POST['adviserEmailAddress']);
    $adviserPassword = mysqli_real_escape_string($connection, $_POST['adviserPassword']);
    $adviserSection = mysqli_real_escape_string($connection, $_POST['adviserSection']);

<<<<<<< HEAD


    // Insert into advisers table
    $insert_adviser_query = "INSERT INTO advisers (adviserFullName, adviserContactNumber, adviserGrLvl, adviserEmailAddress, adviserPassword, adviserSection)
                         VALUES ('$adviserFullName', '$adviserContactNumber', '$adviserGrLvl', '$adviserEmailAddress', '$adviserPassword', '$adviserSection')";
$insert_adviser_query_run = mysqli_query($connection, $insert_adviser_query);

// Insert into user_acc table
$insert_user_query = "INSERT INTO user_acc (user_email, user_password, user_role) 
                      VALUES ('$adviserEmailAddress', '$adviserPassword', 'adviser')";
$insert_user_query_run = mysqli_query($connection, $insert_user_query);

    // Check if both queries were successful
    if ($insert_adviser_query_run && $insert_user_query_run) {
        $_SESSION['status'] = "Adviser Profile and Account Added Successfully";
        $_SESSION['show_part'] = 2; // Set session variable to show Part 2
    } else {
        $_SESSION['status'] = "Error: " . mysqli_error($connection);
        $_SESSION['show_part'] = 1; // Default to Part 1 if the query fails
    }

=======
    // Validate that adviserSection is not empty
    if (empty($adviserSection)) {
        $_SESSION['status'] = "Error: Section cannot be empty. Please select a valid section.";
        header('Location: ../PHPmain/adminTeachers.php');
        exit;
    }

    // Hash password for security
    $hashedPassword = password_hash($adviserPassword, PASSWORD_DEFAULT);

    // Insert into advisers table
    $insert_adviser_query = "INSERT INTO advisers (adviserFullName, adviserContactNumber, adviserGrLvl, adviserEmailAddress, adviserPassword, adviserSection)
                             VALUES ('$adviserFullName', '$adviserContactNumber', '$adviserGrLvl', '$adviserEmailAddress', '$hashedPassword', '$adviserSection')";
    $insert_adviser_query_run = mysqli_query($connection, $insert_adviser_query);

    // Insert into user_acc table
    $insert_user_query = "INSERT INTO user_acc (user_email, user_password, user_role) 
                          VALUES ('$adviserEmailAddress', '$hashedPassword', 'adviser')";
    $insert_user_query_run = mysqli_query($connection, $insert_user_query);

    // Check if both queries were successful
    if ($insert_adviser_query_run && $insert_user_query_run) {
        $_SESSION['status'] = "Adviser Profile and Account Added Successfully";
        $_SESSION['show_part'] = 2; // Show Part 2
    } else {
        $_SESSION['status'] = "Error: " . mysqli_error($connection);
        $_SESSION['show_part'] = 1; // Show Part 1 if query fails
    }

>>>>>>> 83d09df69ff489831a8ab36bbd0a83ca4b1e2d81
    // Redirect back to the adminTeachers.php page
    header('Location: ../PHPmain/adminTeachers.php');
    exit;
}

// Close the connection
mysqli_close($connection);
<<<<<<< HEAD
?>
=======
?>
>>>>>>> 83d09df69ff489831a8ab36bbd0a83ca4b1e2d81
