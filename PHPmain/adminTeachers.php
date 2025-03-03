<?php include "adminNavbar.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet.cs" href="../CSS/Admin/adminTeachers.css">
</head>
<body>
    <div class="container-fluid">
        <div class="teacherBox">
            <h4><strong> Manage Teachers <>/strong></h4>
            <div class="d-flex justify-content-between">
            <div></div>
            <div>
                <label><strong> Search: </strong><input type="text" class="form-control d-inline w-auto"></label>
            </div>
        </div>
        <table class="table table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th>Full Name</th>
                    <th>Contact Number</th>
                    <th>Department</th>
                    <th>Section</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button class="btn-edit">Edit</button>
                        <button class="btn-remove">Remove</button>
                </td>
                </tr>
            </tbody>
        </table>
        <p><strong>Showing 1 to 4 of 4 entries</strong>
        <button class="btn btn-secondary">Add Teachers</button>
    </div>
    </div>

    <script src=""></script>
</body>
</html>