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
    <link rel="stylesheet" href="../CSS/Admin/adminTeachers.css">
</head>

<body>

    <div class="modal fade" id="addTeacher" tabindex="-1" aria-labelledby="addTeacherLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addTeacherLabel">Create New Teacher Account</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="../PHP/adviserRegister.php" method="POST">
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group mb-3">
                                <label for="adviserFullName" class="form-label">Full Name</label>
                                <input type="text" id="adviserFullName" name="adviserFullName" class="form-control" placeholder="Full Name" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group mb-3">
                                <label for="adviserContactNumber" class="form-label">Contact Number</label>
                                <input type="tel" id="adviserContactNumber" name="adviserContactNumber" class="form-control" placeholder="Contact Number" pattern="[0-9]+" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="adviserGrLvl" class="form-label">Grade Level</label>
                                <select id="adviserGrLvl" name="adviserGrLvl" class="form-control" required>
                                    <option value="7">Grade 7</option>
                                    <option value="8">Grade 8</option>
                                    <option value="9">Grade 9</option>
                                    <option value="10">Grade 10</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="adviserSection" class="form-label">Section</label>
                                <select id="adviserSection" name="adviserSection" class="form-control" required>
                                    <option value="Aqua">Aqua</option>
                                    <option value="Bronze">Bronze</option>
                                    <option value="Crank">Crank</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group mb-3">
                                <label for="adviserEmail" class="form-label">Email Address</label>
                                <input type="email" id="adviserEmail" name="adviserEmail" class="form-control" placeholder="Email" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="adviserPassword" class="form-label">Password</label>
                                <input type="text" id="adviserPassword" name="adviserPassword" class="form-control" placeholder="Password" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="adviserRegister" class="btn btn-primary">Register</button>
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
                        <strong>Hey !</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['status']);
                }
                ?>

                <div class="card">
                    <div class="card-header">
                        <h4>Teachers</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacher">Add Teacher</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Contact Number</th>
                                    <th scope="col">Grade Level</th>
                                    <th scope="col">Section</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $connection = mysqli_connect("localhost", "root", "", "adviser_list");
                                $fetch_query = "SELECT * FROM advisers";
                                $fetch_query_run = mysqli_query($connection, $fetch_query);

                                if (mysqli_num_rows($fetch_query_run) > 0) {
                                    while ($row = mysqli_fetch_array($fetch_query_run)) {
                                ?>
                                        <tr>
                                            <td><?php echo $row['adviserFullName'] ?></td>
                                            <td><?php echo $row['adviserContactNumber'] ?></td>
                                            <td><?php echo $row['adviserGrLvl'] ?></td>
                                            <td><?php echo $row['adviserSection'] ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-edit btn-sm">Edit</button>
                                                <button class="btn btn-danger btn-remove btn-sm">Remove</button>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr colspan="4">No Records Found </tr>
                                <?php
                                }

                                ?>
                            </tbody>
                        </table>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src=""></script>
</body>

</html>