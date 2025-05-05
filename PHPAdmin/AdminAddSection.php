<?php include 'adminNavbar.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sections</title>
    <link rel="stylesheet" href="../CSS/Admin/adminTeachers.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

    <!-- Modal for Adding Section -->
    <div class="modal fade" id="addSection" tabindex="-1" aria-labelledby="addSectionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addSectionLabel">Create New Section</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="../PHPAdmin/sectionRegister.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="section_name" class="form-label">Section Name</label>
                            <input type="text" id="section_name" name="section_name" class="form-control"
                                placeholder="Section Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="section_grade_level" class="form-label">Grade Level</label>
                            <select id="section_grade_level" name="section_grade_level" class="form-control" required>
                                <option value="7">Grade 7</option>
                                <option value="8">Grade 8</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="section_year_start_level" class="form-label">Start Year Level</label>
                            <input type="text" id="section_year_start_level" name="section_year_start_level" class="form-control"
                                placeholder="2024" required>
                        </div>
                        <div class="mb-3">
                            <label for="section_year_end_level" class="form-label">End Year Level</label>
                            <input type="text" id="section_year_end_level" name="section_year_end_level" class="form-control"
                                placeholder="2025" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            <button type="submit" name="sectionRegister" class="btn btn-success">Save Section</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Section -->
    <div class="modal fade" id="editSection" tabindex="-1" aria-labelledby="editSectionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editSectionLabel">Edit Section</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../PHPAdmin/sectionUpdate.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="edit_section_id" name="section_id">
                        <div class="mb-3">
                            <label for="edit_section_name" class="form-label">Section Name</label>
                            <input type="text" id="edit_section_name" name="section_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_section_grade_level" class="form-label">Grade Level</label>
                            <select id="edit_section_grade_level" name="section_grade_level" class="form-control"
                                required>
                                <option value="7">Grade 7</option>
                                <option value="8">Grade 8</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                            </select>
                            <div class="mb-3">
                            <label for="edit_section_year_start_level" class="form-label">Start Year Level</label>
                            <input type="text" id="edit_section_year_start_level" name="section_year_start_level" class="form-control"
                                placeholder="1023" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_section_year_end_level" class="form-label">End Year Level</label>
                            <input type="text" id="edit_section_year_end_level" name="section_year_end_level" class="form-control"
                                placeholder="1021" required>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="sectionUpdate" class="btn btn-success">Update Section</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php
                if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                    ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php
                    unset($_SESSION['status']);
                }
                ?>

                <div class="card">
                    <div class="card-header">
                        <h4>Malinta National High School Sections</h4>
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#addSection">Add Section</button>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Section Name</th>
                                <th scope="col">Grade Level</th>
                                <th scope="col">Year Level</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $connection = mysqli_connect("localhost", "root", "", "educguarddb");
                            if (!$connection) {
                                die("Database connection failed: " . mysqli_connect_error());
                            }
                            $fetch_query = "SELECT * FROM class_section order by section_name ASC";
                            $fetch_query_run = mysqli_query($connection, $fetch_query);

                            if ($fetch_query_run) {
                                if (mysqli_num_rows($fetch_query_run) > 0) {
                                    while ($row = mysqli_fetch_array($fetch_query_run)) {
                                        ?>
                                        <tr>

                                            <td><?php echo $row['section_name'] ?></td>
                                            <td><?php echo $row['section_grade_level'] ?></td>
                                            <td><?php echo $row['section_year_start_level'] . ' - ' . $row['section_year_end_level']; ?></td>
                                            <td>
                                                <!-- Edit Button with Icon -->
                                                <a href="#" class="btn btn-warning btn-sm edit_data"
                                                    data-id="<?php echo $row['section_id']; ?>"
                                                    data-SectionName="<?php echo $row['section_name']; ?>"
                                                    data-GradeLevel="<?php echo $row['section_grade_level']; ?>"
                                                    data-YearStartLevel="<?php echo $row['section_year_start_level']; ?>"
                                                    data-YearEndLevel="<?php echo $row['section_year_end_level']; ?>"
                                                    data-bs-toggle="modal" data-bs-target="#editSection">
                                                    <i class="fas fa-edit"></i> <!-- Font Awesome Edit Icon -->
                                                </a>

                                                <!-- Delete Button with Icon -->
                                                <form action="../PHP/adminSectionDelete.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="section_id"
                                                        value="<?php echo $row['section_id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> <!-- Font Awesome Trash Icon -->
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="3">No Records Found</td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='3'>Error: " . mysqli_error($connection) . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>


    <script src="../JS/adminSection.js"></script>
</body>

</html>