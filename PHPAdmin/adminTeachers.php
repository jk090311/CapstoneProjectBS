<?php include "adminNavbar.php" ?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Teachers</title>
    <link rel="stylesheet" href="../CSS/Admin/adminTeachers.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

    <div class="modal fade" id="addTeacher" tabindex="-1" aria-labelledby="addTeacherLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addTeacherLabel">Create New Teacher Account</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- First Form: Full Name, Contact Number, Grade Level, and Section -->
                <form action="../PHP/adviserRegister.php" method="POST">
                    <input type="hidden" id="action_type" name="action_type" value="add">
                    <input type="hidden" id="adviser_id" name="adviser_id" value="">
                    <div class="modal-body">
                        <div class="part" id="part1">
                            <div class="form-row">
                                <div class="form-group mb-3">
                                    <label for="adviserFullName" class="form-label">Full Name</label>
                                    <input type="text" id="adviserFullName" name="adviserFullName" class="form-control"
                                        placeholder="Full Name" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group mb-3">
                                    <label for="adviserContactNumber" class="form-label">Contact Number</label>
                                    <input type="tel" id="adviserContactNumber" name="adviserContactNumber"
                                        class="form-control" placeholder="Contact Number" value="" required>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="adviserGrLvl" class="form-label">Grade Level</label>
                                <select id="adviserGrLvl" name="adviserGrLvl" class="form-control" required>
                                    <option value="">Select Grade Level</option>
                                    <option value="7">Grade 7</option>
                                </select>

                                <label for="adviserSection" class="form-label">Section</label>
                                <select id="adviserSection" name="adviserSection" class="form-control" required>
                                    <option value="">Select Section</option>
                                    <?php
                                    // Connect to the database
                                    $connection = mysqli_connect("localhost", "root", "", "educguarddb");

                                    // Check connection
                                    if (!$connection) {
                                        die("Connection failed: " . mysqli_connect_error());
                                    }

                                    // Fetch sections from the database
                                    $query = "SELECT section_name FROM class_section order by section_name ASC";
                                    $query_run = mysqli_query($connection, $query);

                                    // Loop through the results and create <option> tags
                                    if (mysqli_num_rows($query_run) > 0) {
                                        while ($row = mysqli_fetch_assoc($query_run)) {
                                            echo '<option value="' . htmlspecialchars($row['section_name']) . '">' . htmlspecialchars($row['section_name']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No Sections Available</option>';
                                    }

                                    // Close the database connection
                                    mysqli_close($connection);
                                    ?>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="showPart(2)">Next</button>
                            </div>
                        </div>

                        <!-- Second Form: Email Address and Password -->
                        <div class="part" id="part2" style="display: none;">

                            <div class="modal-body">
                                <h5 class="mb-3">Account Details</h5>
                                <div class="form-row">
                                    <div class="form-group mb-3">
                                        <label for="adviserEmailAddress" class="form-label">Email Address</label>
                                        <input type="text" id="adviserEmailAddress" name="adviserEmailAddress"
                                            class="form-control" placeholder="Email Address" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group mb-3">
                                        <label for="adviserPassword" class="form-label">Password</label>
                                        <input type="text" id="adviserPassword" name="adviserPassword"
                                            class="form-control" placeholder="Password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="showPart(1)">Previous</button>
                                <button type="submit" id="submitBtn" name="adviserRegister"
                                    class="btn btn-primary">Register</button>
                            </div>
                        </div>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    </div>

    <div class="container-fluid">
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

                <div class="card-header">
                    <h4>Malinta National High School Teachers</h4>
                    <div class="button-container">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacher">Add Adviser</button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSubjectTeacher">Add Subject Teacher</button>
                    </div>
                </div>
                <!-- Add Subject Teacher Modal -->
                <div class="modal fade" id="addSubjectTeacher" tabindex="-1" aria-labelledby="addSubjectTeacherLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="addSubjectTeacherLabel">Create New Subject Teacher
                                    Account</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="../PHP/subjectTeacherRegister.php" method="POST">
                                <input type="hidden" name="action_type" value="add">
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="subjectTeacherFullName" class="form-label">Full Name</label>
                                        <input type="text" id="subjectTeacherFullName" name="stFullName" class="form-control" placeholder="Full Name" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="subjectTeacherContactNumber" class="form-label">Contact
                                            Number</label>
                                        <input type="tel" id="subjectTeacherContactNumber" name="stContactNumber" class="form-control"
                                            placeholder="Contact Number" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="subjectTeacherEmailAddress" class="form-label">Email Address</label>
                                        <input type="email" id="subjectTeacherEmailAddress" name="stEmail" class="form-control"
                                            placeholder="Email Address" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="subjectTeacherPassword" class="form-label">Password</label>
                                        <input type="text" id="subjectTeacherPassword" name="stPassword" class="form-control" placeholder="Password" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="subjectTeacherSubject" class="form-label">Subject</label>
                                        <input type="text" id="subjectTeacherSubject" name="stSubject" class="form-control" placeholder="Subject" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="subjectTeacherRegister"
                                        class="btn btn-success">Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Full Name</th>
                                <th scope="col">Contact Number</th>
                                <th scope="col">Email Address</th>
                                <th scope="col">Password</th>
                                <th scope="col">Grade Level / Subject</th>
                                <th scope="col">Section</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $connection = mysqli_connect("localhost", "root", "", "educguarddb");

                            // Show Advisers
                            $fetch_query = "SELECT * FROM advisers ORDER BY adviserFullName ASC";
                            $fetch_query_run = mysqli_query($connection, $fetch_query);

                            if (mysqli_num_rows($fetch_query_run) > 0) {
                                while ($row = mysqli_fetch_array($fetch_query_run)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['adviserFullName'] ?></td>
                                        <td><?php echo $row['adviserContactNumber'] ?></td>
                                        <td><?php echo $row['adviserEmailAddress'] ?></td>
                                        <td>***********</td> <!-- Do not display plain passwords -->
                                        <td><?php echo $row['adviserGrLvl'] ?></td>
                                        <td><?php echo $row['adviserSection'] ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <!-- Edit Button with Icon -->
                                                <a href="#" class="btn btn-warning btn-edit btn-sm edit_data"
                                                    data-name="<?php echo $row['adviserFullName']; ?>"
                                                    data-contact="<?php echo $row['adviserContactNumber']; ?>"
                                                    data-email="<?php echo $row['adviserEmailAddress']; ?>"
                                                    data-grade="<?php echo $row['adviserGrLvl']; ?>"
                                                    data-section="<?php echo $row['adviserSection']; ?>"
                                                    data-password="<?php echo $row['adviserPassword']; ?>"
                                                    data-bs-toggle="modal" data-bs-target="#addTeacher">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- Remove Button with Icon -->
                                                <button class="btn btn-danger btn-remove btn-sm"
                                                    onclick="removeAdviser('<?php echo $row['adviserFullName']; ?>')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7">No Adviser Records Found</td>
                                </tr>
                                <?php
                            }

                            // Show Subject Teachers
                            $fetch_subject_query = "SELECT * FROM subject_teachers ORDER BY stFullName ASC";
                            $fetch_subject_query_run = mysqli_query($connection, $fetch_subject_query);

                            if (mysqli_num_rows($fetch_subject_query_run) > 0) {
                                while ($row = mysqli_fetch_array($fetch_subject_query_run)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['stFullName'] ?></td>
                                        <td><?php echo $row['stContactNumber'] ?></td>
                                        <td><?php echo $row['stEmail'] ?></td>
                                        <td>***********</td> <!-- Do not display plain passwords -->
                                        <td><?php echo $row['stSubject'] ?></td>
                                        <td>-</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <!-- Edit Button with Icon -->
                                                <a href="#" class="btn btn-warning btn-edit btn-sm edit_data"
                                                    data-name="<?php echo $row['stFullName']; ?>"
                                                    data-contact="<?php echo $row['stContactNumber']; ?>"
                                                    data-email="<?php echo $row['stEmail']; ?>"
                                                    data-subject="<?php echo $row['stSubject']; ?>"
                                                    data-password="<?php echo $row['stPassword']; ?>" data-bs-toggle="modal"
                                                    data-bs-target="#addSubjectTeacher">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- Remove Button with Icon -->
                                                <button class="btn btn-danger btn-remove btn-sm"
                                                    onclick="removeSubjectTeacher('<?php echo $row['stFullName']; ?>')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7">No Subject Teacher Records Found</td>
                                </tr>
                                <?php
                            }

                            mysqli_close($connection);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    </div>



    <script src="../JS/adviserList.js"></script>

</body>

</html>

<!-- Edit Subject Teacher Modal -->
<div class="modal fade" id="editSubjectTeacher" tabindex="-1" aria-labelledby="editSubjectTeacherLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editSubjectTeacherLabel">Edit Subject Teacher Account</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../PHP/subjectTeacherUpdate.php" method="POST">
                <input type="hidden" name="action_type" value="add">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="subjectTeacherFullName">Full Name</label>
                        <input type="text" class="form-control" id="subjectTeacherFullName" name="subjectTeacherFullName" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="subjectTeacherContactNumber">Contact Number</label>
                        <input type="text" class="form-control" id="subjectTeacherContactNumber" name="subjectTeacherContactNumber" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="subjectTeacherEmailAddress">Email Address</label>
                        <input type="email" class="form-control" id="subjectTeacherEmailAddress" name="subjectTeacherEmailAddress" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="subjectTeacherPassword">Password</label>
                        <input type="password" class="form-control" id="subjectTeacherPassword" name="subjectTeacherPassword" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="subjectTeacherSubject">Subject</label>
                        <input type="text" class="form-control" id="subjectTeacherSubject" name="subjectTeacherSubject" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>