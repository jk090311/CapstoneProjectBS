<?php include 'adminNavbar.php'; ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Subject</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/Admin/adminNavbar.css"> <!-- Link to custom CSS -->
</head>

<body>
    <div class="container custom-margin"> <!-- Adjusted margin -->
        <!-- Success Message -->
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                {$_SESSION['success_message']}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
            unset($_SESSION['success_message']); // Clear the message after displaying it
        }
        ?>
        <div class="d-flex justify-content-center">
            <div class="card w-75 p-4"> <!-- Added padding -->
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Subjects</h2>
                </div>
                <div class="card-body">
                    <!-- Add Subject Button -->
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#addSubject">
                        Add Subject
                    </button>

                    <div class="scrollable-table">
                        <table class="table table-bordered table-hover table-striped text-center">
                            <!-- Table content -->
                        </table>
                    </div>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-bordered table-hover table-striped text-center">
                            <!-- Improved table -->
                            <thead>
                                <tr>
                                    <th>Subject Name</th>
                                    <th>Picture</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch subjects from the database
                                include '../PHP/dbconnectionSubjects.php'; // Ensure you have a database connection file
                                mysqli_select_db($conn, 'educguarddb') or die("Database not found!");
                                $query = "SELECT * FROM subjects";
                                $result = mysqli_query($conn, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                        <td>{$row['subject_name']}</td>
                        <td><img src='../Uploads/{$row['subject_picture']}' width='50' height='50'></td>
                        <td>
                            <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editSubject{$row['id']}'>Edit</button>
                            <form action='../PHP/adminSubjectDelete.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='subject_id' value='{$row['id']}'>
                                <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                            </form>
                        </td>
                      </tr>";

                                    // Edit Modal for each subject
                                    echo "
                <div class='modal fade' id='editSubject{$row['id']}' tabindex='-1' aria-labelledby='editSubjectLabel{$row['id']}' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h1 class='modal-title fs-5' id='editSubjectLabel{$row['id']}'>Edit Subject</h1>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <form action='../PHP/adminSubjectUpdate.php' method='POST' enctype='multipart/form-data'>
                                <input type='hidden' name='subject_id' value='{$row['id']}'>
                                
                                <div class='modal-body'>
                                    <div class='mb-3'>
                                        <label for='editSubjectName{$row['id']}' class='form-label'>Subject Name</label>
                                        <input type='text' id='editSubjectName{$row['id']}' name='editSubjectName' class='form-control' value='{$row['subject_name']}' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='editSubjectPicture{$row['id']}' class='form-label'>Subject Picture</label>
                                        <input type='file' id='editSubjectPicture{$row['id']}' name='editSubjectPicture' class='form-control'>
                                        <small>Leave blank to keep the current picture.</small>
                                    </div>
                                </div>
                                <div class='modal-footer'>
                                    <button type='submit' class='btn btn-success'>Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Adding Subject -->
        <div class="modal fade" id="addSubject" tabindex="-1" aria-labelledby="addSubjectLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 text-center" id="addSubjectLabel">Create New Subject</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../PHP/adminSubjectAdd.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action_type" value="add">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="adminSubjectName" class="form-label">Subject Name</label>
                                <input type="text" id="adminSubjectName" name="adminSubjectName" class="form-control"
                                    placeholder="Science" required>
                            </div>
                            <div class="mb-3">
                                <label for="adminSubjectPicture" class="form-label">Subject Picture</label>
                                <input type="file" id="adminSubjectPicture" name="adminSubjectPicture"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save Subject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>