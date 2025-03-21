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

<div class="modal fade" id="addTeacher" tabindex="-1" aria-labelledby="addTeacherLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addTeacherLabel">Create New Teacher Account</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="adviserRegister.php" method="POST">

      <div class="modal-body">
        <div class="form-row">
        <div class="form-group mb-3">
            <label for="adviserFullName" class="form-label" name="adviserFullName">Full Name</label>
            <input type="text" id="adviserFullName" class="form-control" placeholder="Full Name" required>
        </div>
        </div>

        <div class="form-row">
        <div class="form-group mb-3">
            <label for="adviserContactNumber" class="form-label" name="adviserContactNumber">Contact Number</label>
            <input type="tel" id="adviserContactNumber" class="form-control" placeholder="Contact Number" pattern="[0-9]+" required>
        </div>
        

        <div class="form-group mb-3">
            <label for="adviserGrLvl" class="form-label">Grade Level</label>
            <select id="adviserGrLvl" name="adviserGrLvl" required>
                        <option value="7">Grade 7</option>
                        <option value="8">Grade 8</option>
                        <option value="9">Grade 9</option>
                        <option value="10">Grade 10</option>
                    </select>
        </div>

        <div class="form-group mb-3">
            <label for="adviserSection" class="form-label">Section</label>
            <select id="adviserSection" name="adviserSection" required>
                        <option value="Aqua">Aqua</option>
                        <option value="Bronze">Bronze</option>
                        <option value="Crank">Crank</option>
                    </select>
        </div>
        </div>

        <div class="form-row">
        <div class="form-group mb-3">
            <label for="adviserEmail" class="form-label">Email Address</label>
            <input type="email" id="adviserEmail" class="form-control" name="adviserEmail" placeholder="Email" required>
        </div>

        <div class="form-group mb-3">
            <label for="adviserPassword" class="form-label">Password</label>
            <input type="text" id="adviserPassword" class="form-control" name="adviserPassword" placeholder="Password" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="adviserRegister" class="btn btn-primary">Register</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>

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
                    <th>Section</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacher">
 Add Teachers
</button>
    </div>
    </div>

    <script src=""></script>
</body>
</html>