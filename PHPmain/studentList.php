<?php include "adminNavbar.php" ?>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../CSS/Admin/adminStudent.css">
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

                <form action="../PHP/studentRegister.php" method="POST">
                    <input type="hidden" id="edit_student_id" name="student_id" value="">

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
                                <label for="edit_studentGrLvl" class="form-label">Grade Level</label>
                                <select id="edit_studentGrLvl" name="studentGrLvl" class="form-control" required>
                                    <option value="7">Grade 7</option>
                                    <option value="8">Grade 8</option>
                                    <option value="9">Grade 9</option>
                                    <option value="10">Grade 10</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="edit_studentSection" class="form-label">Section</label>
                                <select id="edit_studentSection" name="studentSection" class="form-control" required>
                                    <option value="Aqua">Aqua</option>
                                    <option value="Bronze">Bronze</option>
                                    <option value="Crank">Crank</option>
                                </select>
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

    <script>
        document.querySelectorAll('.edit_data').forEach(button => {
            button.addEventListener('click', function () {
                const firstName = this.getAttribute('data-firstname');
                const middleName = this.getAttribute('data-middlename');
                const lastName = this.getAttribute('data-lastname');
                const contact = this.getAttribute('data-contact');
                const grade = this.getAttribute('data-grade');
                const section = this.getAttribute('data-section');

                document.getElementById('edit_studentFirstName').value = firstName;
                document.getElementById('edit_studentMiddleName').value = middleName;
                document.getElementById('edit_studentLastName').value = lastName;
                document.getElementById('edit_studentContactNumber').value = contact;
                document.getElementById('edit_studentGrLvl').value = grade;
                document.getElementById('edit_studentSection').value = section;
            });
        });
    </script>

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
                        <h4>Student</h4>
                        <button type="button" class="btn btn-primary float-end"
                            onclick="window.location.href='studentRegister.php'">Add Student</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Middle Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Contact Number</th>
                                    <th scope="col">Grade Level</th>
                                    <th scope="col">Section</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $connection = mysqli_connect("localhost", "root", "", "account_list");
                                $fetch_query = "SELECT * FROM students";
                                $fetch_query_run = mysqli_query($connection, $fetch_query);

                                if (mysqli_num_rows($fetch_query_run) > 0) {
                                    while ($row = mysqli_fetch_array($fetch_query_run)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['first_name'] ?></td>
                                            <td><?php echo $row['middle_name'] ?></td>
                                            <td><?php echo $row['last_name'] ?></td>
                                            <td><?php echo $row['contact_number'] ?></td>
                                            <td><?php echo $row['grade_level'] ?></td>
                                            <td><?php echo $row['section'] ?></td>
                                            <td>
                                                <a href="#" class="btn btn-warning btn-edit btn-sm edit_data"
                                                    data-id="<?php echo $row['id']; ?>"
                                                    data-firstname="<?php echo $row['first_name']; ?>"
                                                    data-middlename="<?php echo $row['middle_name']; ?>"
                                                    data-lastname="<?php echo $row['last_name']; ?>"
                                                    data-contact="<?php echo $row['contact_number']; ?>"
                                                    data-grade="<?php echo $row['grade_level']; ?>"
                                                    data-section="<?php echo $row['section']; ?>" data-bs-toggle="modal"
                                                    data-bs-target="#editStudentModal">Edit</a>

                                                <button class="btn btn-danger btn-remove btn-sm"
                                                    data-id="<?php echo $row['first_name']; ?>"
                                                    onclick="removeStudent('<?php echo $row['first_name']; ?>')">Remove</button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7">No Records Found</td>
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



    <script>

        document.querySelectorAll('.edit_data').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const firstName = this.getAttribute('data-firstname');
            const middleName = this.getAttribute('data-middlename');
            const lastName = this.getAttribute('data-lastname');
            const contact = this.getAttribute('data-contact');
            const grade = this.getAttribute('data-grade');
            const section = this.getAttribute('data-section');

            document.getElementById('edit_student_id').value = id;
            document.getElementById('edit_studentFirstName').value = firstName;
            document.getElementById('edit_studentMiddleName').value = middleName;
            document.getElementById('edit_studentLastName').value = lastName;
            document.getElementById('edit_studentContactNumber').value = contact;
            document.getElementById('edit_studentGrLvl').value = grade;
            document.getElementById('edit_studentSection').value = section;
        });
    });
    </script>

</body>

</html>