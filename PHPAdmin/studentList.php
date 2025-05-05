<?php include "adminNavbar.php" ?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Students</title>
    <link rel="stylesheet" href="../CSS/Admin/adminStudents.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Edit Student Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editStudentModalLabel">Edit Student</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="../PHP/studentUpdate.php" method="POST">
                    <!-- Hidden fields to capture original names -->
                    <input type="hidden" id="originalFirstName" name="originalFirstName" value="">
                    <input type="hidden" id="originalMiddleName" name="originalMiddleName" value="">
                    <input type="hidden" id="originalLastName" name="originalLastName" value="">

                    <div class="modal-body">

                        <div class="form-row">
                            <div class="form-group mb-3">
                                <label for="edit_studentFirstName" class="form-label">First Name</label>
                                <input type="text" id="edit_studentFirstName" name="studentFirstName"
                                    class="form-control" placeholder="First Name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="edit_studentMiddleName" class="form-label">Middle Name</label>
                                <input type="text" id="edit_studentMiddleName" name="studentMiddleName"
                                    class="form-control" placeholder="Middle Name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="edit_studentLastName" class="form-label">Last Name</label>
                                <input type="text" id="edit_studentLastName" name="studentLastName" class="form-control"
                                    placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group mb-3">
                                <label for="edit_studentContactNumber" class="form-label">Contact Number</label>
                                <input type="tel" id="edit_studentContactNumber" name="studentContactNumber"
                                    class="form-control" placeholder="Contact Number" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="edit_studentSection" class="form-label">Section</label>
                                <select id="edit_studentSection" name="studentSection" class="form-control" required>
                                    <?php
                                    // Connect to the database
                                    $connection = mysqli_connect("localhost", "root", "", "educguarddb");

                                    // Check connection
                                    if (!$connection) {
                                        die("Connection failed: " . mysqli_connect_error());
                                    }

                                    // Fetch sections from the database
                                    $section_query = "SELECT section_name FROM class_section"; // Replace 'sections' and 'section_name' with your actual table and column names
                                    $section_query_run = mysqli_query($connection, $section_query);

                                    var_dump(mysqli_fetch_assoc($section_query_run));

                                    // Populate the dropdown with sections
                                    if (mysqli_num_rows($section_query_run) > 0) {
                                        while ($section = mysqli_fetch_assoc($section_query_run)) {
                                            echo '<option value="' . $section['section_name'] . '">' . $section['section_name'] . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No Sections Available</option>';
                                    }

                                    // Close the connection
                                    mysqli_close($connection);
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="edit_studentGrLvl" class="form-label">Grade Level</label>
                                <select id="edit_studentGrLvl" name="studentGrLvl" class="form-control" required>
                                    <option value="7">Grade 7</option>
                                    <option value="8">Grade 8</option>
                                    <option value="9">Grade 9</option>
                                    <option value="10">Grade 10</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="edit_studentYrLvl" class="form-label">Year Level</label>
                                <select id="edit_studentYrLvl" name="studentYrLvl" class="form-control" required>
                                    <option value="4">2024-2025</option>
                                    <option value="5">2025-2026</option>
                                    <option value="6">2026-2027</option>
                                    <option value="7">2028-2029</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="edit_submitBtn" name="studentUpdate"
                            class="btn btn-primary">Update</button>
                    </div>
            </div>
            </form>
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

                <div class="card">
                    <div class="card-header">
                        <h4>Malinta National High School Students</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addStudentModal">
                            Add Student
                        </button>

                        <!-- Add Student Modal -->
                        <div class="modal fade" id="addStudentModal" tabindex="-1"
                            aria-labelledby="addStudentModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="addStudentModalLabel">Add Student</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="../PHP/saveStudData.php" method="POST">
                                        <div class="modal-body">
                                            <!-- Part 1 -->
                                            <div class="part" id="part1">
                                                <div class="form-group mb-3">
                                                    <label>First Name:</label>
                                                    <input type="text" name="fName" class="form-control" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Middle Name:</label>
                                                    <input type="text" name="mName" class="form-control" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Last Name:</label>
                                                    <input type="text" name="lName" class="form-control" required>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group mb-3 col-md-6">
                                                        <label>BirthDate:</label>
                                                        <input type="date" id="bDate" name="bDate" class="form-control"
                                                            required>
                                                    </div>
                                                    <div class="form-group mb-3 col-md-6">
                                                        <label>Sex:</label>
                                                        <select id="sex" name="sex" class="form-control" required>
                                                            <option value="none">-</option>
                                                            <option value="M">M</option>
                                                            <option value="F">F</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Contact Number:</label>
                                                <input type="tel" name="cNumber" class="form-control" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="edit_studentSection" class="form-label">Section</label>
                                                <select id="section" name="section" class="form-control" required
                                                    onchange="fetchSectionDetails(this.value)">
                                                    <option value="">Select Section</option>
                                                    <?php
                                                    // Connect to the database
                                                    $connection = mysqli_connect("localhost", "root", "", "educguarddb");

                                                    // Check connection
                                                    if (!$connection) {
                                                        die("Connection failed: " . mysqli_connect_error());
                                                    }

                                                    // Fetch sections from the database
                                                    $section_query = "SELECT section_name FROM class_section";
                                                    $section_query_run = mysqli_query($connection, $section_query);

                                                    // Populate the dropdown with sections
                                                    if (mysqli_num_rows($section_query_run) > 0) {
                                                        while ($section = mysqli_fetch_assoc($section_query_run)) {
                                                            echo '<option value="' . $section['section_name'] . '">' . $section['section_name'] . '</option>';
                                                        }
                                                    } else {
                                                        echo '<option value="">No Sections Available</option>';
                                                    }

                                                    // Close the connection
                                                    mysqli_close($connection);
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Grade Level:</label>
                                                <input type="text" id="section_grade_level" name="section_grade_level"
                                                    class="form-control" readonly>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Year Level:</label>
                                                <input type="text" id="section_year_start_level"
                                                    name="section_year_start_level" class="form-control" readonly>
                                            </div>



                                            <div class="form-group mb-3">
                                                <label>Address:</label>
                                                <input type="text" name="address" class="form-control"
                                                    placeholder="1234 Street, City, Province" required>
                                            </div>
                                            <button type="button" class="btn btn-primary"
                                                onclick="showPart(2)">Next</button>
                                        </div>

                                        <script>
                                            function fetchSectionDetails(section) {
                                                if (section === "") {
                                                    document.getElementById("section_grade_level").value = "";
                                                    document.getElementById("section_year_start_level").value = "";
                                                    return;
                                                }

                                                const xhr = new XMLHttpRequest();
                                                xhr.open("POST", "fetchSectionDetails.php", true);
                                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                                xhr.onreadystatechange = function () {
                                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                                        console.log("Response:", xhr.responseText); // Debugging: Log the response
                                                        try {
                                                            const response = JSON.parse(xhr.responseText);
                                                            document.getElementById("section_grade_level").value = response.grade_level || "";
                                                            document.getElementById("section_year_start_level").value = response.year_level || "";
                                                        } catch (error) {
                                                            console.error("Error parsing JSON:", error);
                                                        }
                                                    }
                                                };
                                                xhr.send("section=" + encodeURIComponent(section));
                                            }
                                        </script>

                                        <!-- Part 2 -->
                                        <div class="part" id="part2" style="display: none;">
                                            <div class="form-group mb-3">
                                                <label>Parent/Guardian Name:</label>
                                                <input type="text" name="pName" class="form-control"
                                                    placeholder="Juan Dela Cruz" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Parent/Guardian Number:</label>
                                                <input type="tel" name="pNum" class="form-control"
                                                    placeholder="123456789" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Parent/Guardian Email:</label>
                                                <input type="email" name="pEmail" class="form-control"
                                                    placeholder="example@gmail.com">
                                            </div>
                                            <button type="button" class="btn btn-secondary"
                                                onclick="showPart(1)">Previous</button>
                                            <button type="button" class="btn btn-primary"
                                                onclick="showPart(3)">Next</button>
                                        </div>

                                        <!-- Part 3 -->
                                        <div class="part" id="part3" style="display: none;">
                                            <div class="form-group mb-3">
                                                <label>LRN:</label>
                                                <input type="text" name="lrn" class="form-control"
                                                    placeholder="123456789" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>RFID Number:</label>
                                                <input type="text" name="rfidNo" class="form-control"
                                                    placeholder="Scan Your RFIDs" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Email:</label>
                                                <input type="email" name="email" class="form-control"
                                                    placeholder="example@gmail.com" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Student Username:</label>
                                                <input type="text" name="username" class="form-control" required>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="text" id="password" name="password" class="form-control"
                                                    placeholder="Password" required>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label>Status:</label>
                                                <select name="status" class="form-control" required>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                            </div>
                                            <button type="button" class="btn btn-secondary"
                                                onclick="showPart(2)">Previous</button>
                                            <button type="submit" class="btn btn-success">Register</button>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- filepath: c:\xampp\htdocs\CapstoneProjectBS\PHPAdmin\studentList.php -->
                    <div class="card-body">
                        <table class="table table-striped table-hover table-bordered text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>LRN</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Contact Number</th>
                                    <th>Grade Level</th>
                                    <th>Section</th>
                                    <th>Year Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $connection = mysqli_connect("localhost", "root", "", "educguarddb");
                                $fetch_query = "SELECT * FROM students";

                                $fetch_query_run = mysqli_query($connection, $fetch_query);

                                if (mysqli_num_rows($fetch_query_run) > 0) {
                                    while ($row = mysqli_fetch_array($fetch_query_run)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['lrn'] ?></td>
                                            <td><?php echo $row['first_name'] ?></td>
                                            <td><?php echo $row['middle_name'] ?></td>
                                            <td><?php echo $row['last_name'] ?></td>
                                            <td><?php echo $row['contact_number'] ?></td>
                                            <td><?php echo $row['grade_level'] ?></td>
                                            <td><?php echo $row['section'] ?></td>
                                            <td><?php echo $row['year_level'] ?></td>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <!-- Edit Button -->
                                                    <a href="#" class="btn btn-warning btn-sm edit_data"
                                                        data-firstname="<?php echo $row['first_name']; ?>"
                                                        data-middlename="<?php echo $row['middle_name']; ?>"
                                                        data-lastname="<?php echo $row['last_name']; ?>"
                                                        data-contact="<?php echo $row['contact_number']; ?>"
                                                        data-grade="<?php echo $row['grade_level']; ?>"
                                                        data-section="<?php echo $row['section']; ?>" data-bs-toggle="modal"
                                                        data-bs-target="#editStudentModal">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Remove Button -->
                                                    <button class="btn btn-danger btn-sm btn-remove"
                                                        data-id="<?php echo $row['first_name']; ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="9">No Records Found</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="../JS/studentList.js"></script>

</body>

</html>