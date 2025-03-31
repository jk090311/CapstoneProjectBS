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
                    <input type="hidden" id="action_type" name="action_type" value="add">
                    <input type="hidden" id="adviser_id" name="adviser_id" value="">

                    <div class="modal-body">
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

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="submitBtn" name="adviserRegister"
                                class="btn btn-primary">Register</button>
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

                <div class="card">
                    <div class="card-header">
                        <h4>Adviser</h4>
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#addTeacher">Add Adviser</button>
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
                                $fetch_query = "SELECT * FROM adviser";
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
                                                <a href="#" class="btn btn-warning btn-edit btn-sm edit_data"
                                                    data-name="<?php echo $row['adviserFullName']; ?>"
                                                    data-contact="<?php echo $row['adviserContactNumber']; ?>"
                                                    data-grade="<?php echo $row['adviserGrLvl']; ?>"
                                                    data-section="<?php echo $row['adviserSection']; ?>" data-bs-toggle="modal"
                                                    data-bs-target="#addTeacher">Edit</a>

                                                <button class="btn btn-danger btn-remove btn-sm"
                                                    data-id="<?php echo $row['adviserFullName']; ?>"
                                                    onclick="removeAdviser('<?php echo $row['adviserFullName']; ?>')">Remove</button>
                                            </td>
                                            <script>
                                                function removeAdviser(adviserFullName) {
                                                    if (confirm("Are you sure you want to remove this adviser?")) {
                                                        var xhr = new XMLHttpRequest();
                                                        xhr.open("POST", "../PHP/adviserRemove.php", true);
                                                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                                        xhr.onreadystatechange = function () {
                                                            if (xhr.readyState === 4 && xhr.status === 200) {
                                                                alert(xhr.responseText);
                                                                location.reload();
                                                            }
                                                        };
                                                        xhr.send("adviserFullName=" + adviserFullName);
                                                    }
                                                }
                                            </script>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="5">No Records Found</td>
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



    <script src="../JS/adviserList.js"></script>

</body>

</html>