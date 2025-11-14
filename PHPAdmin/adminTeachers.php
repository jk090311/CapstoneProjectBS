<?php include "adminNavbar.php" ?>
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Not logged in, redirect to login page
    header("Location: ../PHPmain/index.php");
    exit();
}


$required_role = "admin"; 
if ($_SESSION['user_role'] != $required_role) {
    
    if ($_SESSION['user_role'] == "admin") {
        header("Location: ../PHPAdmin/dashboardAdmin.php");
    } else if ($_SESSION['user_role'] == "adviser") {
        header("Location: ../PHPAdviser/dashboardTeacher.php");
    } else if ($_SESSION['user_role'] == "student") {
        header("Location: dashboardStudent.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Teachers</title>
    <link rel="stylesheet" href="../CSS/Admin/AadminTeachers.css">
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
                                        class="form-control" placeholder="Contact Number" value="" required
                                        minlength="11" maxlength="11" pattern="\d{11}" inputmode="numeric"
                                        oninput="this.value = this.value.replace(/\D/g,'');">
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

                                    // Fetch sections that are NOT already assigned to an adviser
                                    // (Assumes advisers.adviserSection stores section_name)
                                    $query = "
                                  SELECT cs.section_name
                                    FROM class_section cs
                                    LEFT JOIN advisers a ON a.adviserSection = cs.section_name
                                    WHERE a.adviserSection IS NULL
                                    ORDER BY cs.section_name ASC";

                                    $query_run = mysqli_query($connection, $query);

                                    // Loop through the results and create <option> tags
                                    if ($query_run && mysqli_num_rows($query_run) > 0) {
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

                                <label for="adviserSubject" class="form-label">Subject</label>
                                <select id="adviserSubject" name="adviserSubject" class="form-control" required>
                                    <option value="">Select Subject</option>
                                    <?php
                                    // Connect to the database
                                    $connection2 = mysqli_connect("localhost", "root", "", "educguarddb");

                                    // Check connection
                                    if (!$connection2) {
                                        die("Connection failed: " . mysqli_connect_error());
                                    }

                                    // Fetch all subjects
                                    $subject_query = "SELECT subject_id, subject_name FROM subjects ORDER BY subject_name ASC";
                                    $subject_query_run = mysqli_query($connection2, $subject_query);

                                    // Loop through the results and create <option> tags
                                    if ($subject_query_run && mysqli_num_rows($subject_query_run) > 0) {
                                        while ($row = mysqli_fetch_assoc($subject_query_run)) {
                                            echo '<option value="' . htmlspecialchars($row['subject_name']) . '">' . htmlspecialchars($row['subject_name']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No Subjects Available</option>';
                                    }

                                    // Close the database connection
                                    mysqli_close($connection2);
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
                                        <input type="email" id="adviserEmailAddress" name="adviserEmailAddress"
                                            class="form-control" placeholder="Email Address" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group mb-3">
                                        <label for="adviserPassword" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="text" id="adviserPassword" name="adviserPassword"
                                                class="form-control" placeholder="Password" required readonly>
                                            <button type="button" class="btn btn-outline-secondary" onclick="generateAdviserPassword()" title="Generate New Password">
                                                <i class="fas fa-sync-alt"></i> Generate
                                            </button>
                                        </div>
                                        <small class="text-muted">Password is auto-generated. Click "Generate" for a new one.</small>
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
            
            </form>
        </div>
    </div>
    </div>
    </div>

    <!-- Edit Adviser Modal -->
    <div class="modal fade" id="editAdviser" tabindex="-1" aria-labelledby="editAdviserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editAdviserLabel">Edit Adviser Account</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../PHP/adviserUpdate.php" method="POST" id="editAdviserForm">
                    <input type="hidden" name="action_type" value="edit">
                    <input type="hidden" name="originalAdviserName" id="edit_originalAdviserName">
                    
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="edit_adviserFullName" class="form-label">Full Name</label>
                            <input type="text" id="edit_adviserFullName" name="adviserFullName" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_adviserContactNumber" class="form-label">Contact Number</label>
                            <input type="tel" id="edit_adviserContactNumber" name="adviserContactNumber"
                                class="form-control" required minlength="11" maxlength="11" pattern="\d{11}" 
                                inputmode="numeric" oninput="this.value = this.value.replace(/\D/g,'');">
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_adviserGrLvl" class="form-label">Grade Level</label>
                            <select id="edit_adviserGrLvl" name="adviserGrLvl" class="form-control" required>
                                <option value="">Select Grade Level</option>
                                <option value="7">Grade 7</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_adviserSection" class="form-label">Section</label>
                            <select id="edit_adviserSection" name="adviserSection" class="form-control" required>
                                <option value="">Select Section</option>
                                <?php
                                $conn = mysqli_connect("localhost", "root", "", "educguarddb");
                                if ($conn) {
                                    $query = "SELECT section_name FROM class_section ORDER BY section_name ASC";
                                    $result = mysqli_query($conn, $query);
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . htmlspecialchars($row['section_name']) . '">' . htmlspecialchars($row['section_name']) . '</option>';
                                        }
                                    }
                                    mysqli_close($conn);
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_adviserSubject" class="form-label">Subject</label>
                            <select id="edit_adviserSubject" name="adviserSubject" class="form-control">
                                <option value="">Select Subject</option>
                                <?php
                                $conn = mysqli_connect("localhost", "root", "", "educguarddb");
                                if ($conn) {
                                    $query = "SELECT subject_id, subject_name FROM subjects ORDER BY subject_name ASC";
                                    $result = mysqli_query($conn, $query);
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . htmlspecialchars($row['subject_name']) . '">' . htmlspecialchars($row['subject_name']) . '</option>';
                                        }
                                    }
                                    mysqli_close($conn);
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_adviserEmailAddress" class="form-label">Email Address</label>
                            <input type="email" id="edit_adviserEmailAddress" name="adviserEmailAddress" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_adviserPassword" class="form-label">Password (Optional)</label>
                            <input type="password" id="edit_adviserPassword" name="adviserPassword" class="form-control" placeholder="Leave blank to keep current password">
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="adviserRegister" class="btn btn-primary">Update Adviser</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Handle edit adviser button clicks - inline script to ensure it runs
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('edit_adviser') || e.target.closest('.edit_adviser')) {
                const button = e.target.classList.contains('edit_adviser') ? e.target : e.target.closest('.edit_adviser');
                
                // Get data attributes
                const name = button.getAttribute('data-name');
                const contact = button.getAttribute('data-contact');
                const email = button.getAttribute('data-email');
                const grade = button.getAttribute('data-grade');
                const section = button.getAttribute('data-section');
                const subject = button.getAttribute('data-subject');
                
                console.log('Edit clicked:', { name, contact, email, grade, section, subject });
                
                // Populate the edit form
                document.getElementById('edit_adviserFullName').value = name || '';
                document.getElementById('edit_adviserContactNumber').value = contact || '';
                document.getElementById('edit_adviserEmailAddress').value = email || '';
                document.getElementById('edit_adviserPassword').value = ''; // Clear password field
                document.getElementById('edit_adviserGrLvl').value = grade || '';
                document.getElementById('edit_adviserSection').value = section || '';
                document.getElementById('edit_adviserSubject').value = subject || '';
                document.getElementById('edit_originalAdviserName').value = name || '';
                
                console.log('Form populated successfully');
            }
        });
    </script>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <!-- made the column wider and centered -->
            <div class="col-12 col-lg-11 mx-auto">
                <?php
                if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                    $alertClass = strpos($_SESSION['status'], 'Error') !== false ? 'alert-danger' : 'alert-success';
                ?>
                    <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['status']);
                }
                ?>

                <div class="card-header d-flex align-items-center justify-content-between py-4">
                    <h4 class="mb-0" style="font-size:1.9rem; font-weight:700;">Malinta National High School Teachers
                    </h4>
                    <div class="button-container">
                        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                            data-bs-target="#addTeacher">Add Adviser</button>
                        <button type="button" class="btn btn-success btn-lg ms-2" data-bs-toggle="modal"
                            data-bs-target="#addSubjectTeacher">Add Subject Teacher</button>
                    </div>
                </div>

                <!-------------------------------------------------------------------------------------------------------------------------------->

                <!-- Add Subject Teacher Modal (Part 1: personal & account, Part 2: grade/section/subjects) -->
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

                                <!-- Part 1: Personal info and account details -->
                                <div class="modal-body part" id="stPart1">
                                    <div class="form-group mb-3">
                                        <label for="subjectTeacherFullName" class="form-label">Full Name</label>
                                        <input type="text" id="subjectTeacherFullName" name="stFullName"
                                            class="form-control" placeholder="Full Name" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="subjectTeacherContactNumber" class="form-label">Contact
                                            Number</label>
                                        <input type="tel" id="subjectTeacherContactNumber" name="stContactNumber"
                                            class="form-control" placeholder="Contact Number" required
                                            minlength="11" maxlength="11" pattern="\d{11}" title="Enter 11 digits"
                                            inputmode="numeric"
                                            oninput="this.value = this.value.replace(/\D/g,'');">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="subjectTeacherEmailAddress" class="form-label">Email
                                            Address</label>
                                        <input type="email" id="subjectTeacherEmailAddress" name="stEmail"
                                            class="form-control" placeholder="Email Address" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="subjectTeacherPassword" class="form-label">Password</label>
                                        <input type="text" id="subjectTeacherPassword" name="stPassword"
                                            class="form-control" placeholder="Password" required>
                                    </div>

                                    <!-- error container -->
                                    <div id="stPart1Errors" class="mb-3" aria-live="polite"></div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary"
                                            onclick="validateStPart1AndNext()">Next</button>
                                    </div>
                                </div>

                                <!-- Part 2: Grade level, section, subjects -->
                                <div class="modal-body part" id="stPart2" style="display:none;">
                                    <!--  first grade level-->

                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">

                                                <label for="stGradelvl" class="form-label">Grade Level</label>
                                                <select id="stGradelvl" name="stGradelvl" class="form-control"
                                                    required>
                                                    <option value="">Select Grade Level</option>
                                                    <option value="7">Grade 7</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- first Section -->
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <?php
                                                // Open connection to fetch grade/section and subjects for selects
                                                $stConn = mysqli_connect("localhost", "root", "", "educguarddb");
                                                if (!$stConn) {
                                                    echo '<div class="alert alert-danger">Database connection error. Cannot load selects.</div>';
                                                } else {

                                                    $secQ = "SELECT section_id, section_name FROM class_section ORDER BY section_name ASC";
                                                    $secR = mysqli_query($stConn, $secQ);
                                                    echo '<div class="form-group mb-3">
                                                <label for="subjectTeacherSection" class="form-label">Section</label>
                                                <select id="subjectTeacherSection" name="stSection" class="form-control" required>';
                                                    echo '<option value="">Select Section</option>';
                                                    if ($secR && mysqli_num_rows($secR) > 0) {
                                                        while ($s = mysqli_fetch_assoc($secR)) {
                                                            $secId = (int) $s['section_id'];
                                                            $secName = htmlspecialchars($s['section_name']);
                                                            echo '<option value="' . $secId . '">' . $secName . '</option>';
                                                        }
                                                    } else {
                                                        echo '<option value="">No Sections Available</option>';
                                                    }
                                                    echo '</select></div>';
                                                    // Subjects: fetch from subjects table
                                                    $subQ = "SELECT subject_id, subject_name FROM subjects ORDER BY subject_name ASC";
                                                    $subR = mysqli_query($stConn, $subQ);
                                                    $subjectOptions = '';
                                                    if ($subR && mysqli_num_rows($subR) > 0) {
                                                        while ($rowS = mysqli_fetch_assoc($subR)) {
                                                            $safeName = htmlspecialchars($rowS['subject_name']);
                                                            $sid = (int) $rowS['subject_id'];
                                                            $subjectOptions .= '<option value="' . $sid . '">' . $safeName . '</option>';
                                                        }
                                                    } else {
                                                        $subjectOptions = '<option value="">No Subjects Available</option>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>

                                        <!-- first Subject -->

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="subjectTeacherSubject1" class="form-label">Subject
                                                    1</label>
                                                <?php
                                                // Fetch subjects to populate dropdowns
                                                $subConn = mysqli_connect("localhost", "root", "", "educguarddb");
                                                if (!$subConn) {
                                                    echo '<select class="form-control" id="subjectTeacherSubject1" name="stSubject1"><option value="">DB error</option></select>';
                                                } else {
                                                    $subQ = "SELECT subject_id, subject_name FROM subjects ORDER BY subject_name ASC";
                                                    $subR = mysqli_query($subConn, $subQ);
                                                    $subjectOptions = '';
                                                    if ($subR && mysqli_num_rows($subR) > 0) {
                                                        while ($srow = mysqli_fetch_assoc($subR)) {
                                                            $safeName = htmlspecialchars($srow['subject_name']);
                                                            $sid = (int) $srow['subject_id'];
                                                            $subjectOptions .= '<option value="' . $sid . '">' . $safeName . '</option>';
                                                        }
                                                    } else {
                                                        $subjectOptions = '<option value="">No Subjects Available</option>';
                                                    }
                                                    // First select (required)
                                                    echo '<select id="subjectTeacherSubject1" name="stSubject1" class="form-control" required>';
                                                    echo '<option value="">Select Subject</option>';
                                                    echo $subjectOptions;
                                                    echo '</select>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-------------------------------------------------------------------------------------->
                                    <div class="mb-3">
                                        <label for="additionalRow" class="form-label fw-bold">ADD MORE ID
                                            APPLICABLE</label>
                                    </div>
                                    <!--  second grade level-->
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">

                                                <label for="stGradelvl2" class="form-label">Grade Level</label>
                                                <select id="stGradelvl2" name="stGradelvl2" class="form-control">
                                                    <option value="">Optional</option>
                                                    <option value="7">Grade 7</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- second Section -->
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <?php
                                                // Open connection to fetch grade/section and subjects for selects
                                                $stConn = mysqli_connect("localhost", "root", "", "educguarddb");
                                                if (!$stConn) {
                                                    echo '<div class="alert alert-danger">Database connection error. Cannot load selects.</div>';
                                                } else {

                                                    $secQ = "SELECT section_id, section_name FROM class_section ORDER BY section_name ASC";
                                                    $secR = mysqli_query($stConn, $secQ);
                                                    echo '<div class="form-group mb-3">
                                                <label for="subjectTeacherSection" class="form-label">Section</label>
                                                <select id="subjectTeacherSection" name="stSection2" class="form-control" >';
                                                    echo '<option value="">Optional</option>';
                                                    if ($secR && mysqli_num_rows($secR) > 0) {
                                                        while ($s = mysqli_fetch_assoc($secR)) {
                                                            $secId = (int) $s['section_id'];
                                                            $secName = htmlspecialchars($s['section_name']);
                                                            echo '<option value="' . $secId . '">' . $secName . '</option>';
                                                        }
                                                    } else {
                                                        echo '<option value="">No Sections Available</option>';
                                                    }
                                                    echo '</select></div>';
                                                    // Subjects: fetch from subjects table
                                                    $subQ = "SELECT subject_id, subject_name FROM subjects ORDER BY subject_name ASC";
                                                    $subR = mysqli_query($stConn, $subQ);
                                                    $subjectOptions = '';
                                                    if ($subR && mysqli_num_rows($subR) > 0) {
                                                        while ($rowS = mysqli_fetch_assoc($subR)) {
                                                            $safeName = htmlspecialchars($rowS['subject_name']);
                                                            $sid = (int) $rowS['subject_id'];
                                                            $subjectOptions .= '<option value="' . $sid . '">' . $safeName . '</option>';
                                                        }
                                                    } else {
                                                        $subjectOptions = '<option value="">No Subjects Available</option>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>


                                        <!-- second Subject -->
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="subjectTeacherSubject2" class="form-label">Subject 2
                                                </label>
                                                <?php
                                                if (isset($subjectOptions)) {
                                                    echo '<select id="subjectTeacherSubject2" name="stSubject2" class="form-control">';
                                                    echo '<option value="0">Optional</option>';  // Changed to value="0" for proper NULL handling
                                                    echo $subjectOptions;
                                                    echo '</select>';
                                                } else {
                                                    // In case the previous DB connection failed, show a disabled select
                                                    echo '<select id="subjectTeacherSubject2" name="stSubject2" class="form-control"><option value="0">No Subjects Available</option></select>';
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  third grade level-->
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">

                                                <label for="stGradelvl3" class="form-label">Grade Level</label>
                                                <select id="stGradelvl3" name="stGradelvl3" class="form-control">
                                                    <option value="">Optional</option>
                                                    <option value="7">Grade 7</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- third Section -->
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <?php
                                                // Open connection to fetch grade/section and subjects for selects
                                                $stConn = mysqli_connect("localhost", "root", "", "educguarddb");
                                                if (!$stConn) {
                                                    echo '<div class="alert alert-danger">Database connection error. Cannot load selects.</div>';
                                                } else {

                                                    $secQ = "SELECT section_id, section_name FROM class_section ORDER BY section_name ASC";
                                                    $secR = mysqli_query($stConn, $secQ);
                                                    echo '<div class="form-group mb-3">
                                                <label for="subjectTeacherSection" class="form-label">Section</label>
                                                <select id="subjectTeacherSection" name="stSection3" class="form-control" >';
                                                    echo '<option value="">Optional</option>';
                                                    if ($secR && mysqli_num_rows($secR) > 0) {
                                                        while ($s = mysqli_fetch_assoc($secR)) {
                                                            $secId = (int) $s['section_id'];
                                                            $secName = htmlspecialchars($s['section_name']);
                                                            echo '<option value="' . $secId . '">' . $secName . '</option>';
                                                        }
                                                    } else {
                                                        echo '<option value="">No Sections Available</option>';
                                                    }
                                                    echo '</select></div>';
                                                    // Subjects: fetch from subjects table
                                                    $subQ = "SELECT subject_id, subject_name FROM subjects ORDER BY subject_name ASC";
                                                    $subR = mysqli_query($stConn, $subQ);
                                                    $subjectOptions = '';
                                                    if ($subR && mysqli_num_rows($subR) > 0) {
                                                        while ($rowS = mysqli_fetch_assoc($subR)) {
                                                            $safeName = htmlspecialchars($rowS['subject_name']);
                                                            $sid = (int) $rowS['subject_id'];
                                                            $subjectOptions .= '<option value="' . $sid . '">' . $safeName . '</option>';
                                                        }
                                                    } else {
                                                        $subjectOptions = '<option value="">No Subjects Available</option>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>

                                        <!-- third subject -->

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="subjectTeacherSubject3" class="form-label">Subject 3
                                                </label>
                                                <?php
                                                if (isset($subjectOptions)) {
                                                    echo '<select id="subjectTeacherSubject3" name="stSubject3" class="form-control">';
                                                    echo '<option value="">Optional</option>';
                                                    echo $subjectOptions;
                                                    echo '</select>';
                                                } else {
                                                    // In case the previous DB connection failed, show a disabled select
                                                    echo '<select id="subjectTeacherSubject3" name="stSubject3" class="form-control"><option value="">No Subjects Available</option></select>';
                                                }
                                                // Close DB connection if opened
                                                if (isset($stConn) && $stConn) {
                                                    mysqli_close($stConn);
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            onclick="showSubjectPart(1)">Previous</button>
                                        <button type="submit" name="subjectTeacherRegister"
                                            class="btn btn-success">Register</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    function showSubjectPart(step) {
                        document.getElementById('stPart1').style.display = (step === 1) ? 'block' : 'none';
                        document.getElementById('stPart2').style.display = (step === 2) ? 'block' : 'none';
                        // clear errors when returning to part1
                        if (step === 1) {
                            const errDiv = document.getElementById('stPart1Errors');
                            if (errDiv) errDiv.innerHTML = '';
                        }
                    }

                    function validateStPart1AndNext() {
                        const errors = [];
                        const name = document.getElementById('subjectTeacherFullName').value.trim();
                        const contact = document.getElementById('subjectTeacherContactNumber').value.trim();
                        const email = document.getElementById('subjectTeacherEmailAddress').value.trim();
                        const password = document.getElementById('subjectTeacherPassword').value.trim();
                        const errDiv = document.getElementById('stPart1Errors');

                        if (!name) {
                            errors.push("Full name is required.");
                        }
                        if (!/^\d{11}$/.test(contact)) {
                            errors.push("Contact number must be exactly 11 digits.");
                        }
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(email)) {
                            errors.push("Enter a valid email address.");
                        }
                        if (password.length < 6) {
                            errors.push("Password must be at least 6 characters.");
                        }

                        if (errors.length > 0) {
                            if (errDiv) {
                                errDiv.innerHTML = '<div class="alert alert-danger"><ul>' + errors.map(e => '<li>' + e + '</li>').join('') + '</ul></div>';
                            } else {
                                alert(errors.join("\n"));
                            }
                            return false;
                        }

                        // no errors -> proceed
                        if (errDiv) errDiv.innerHTML = '';
                        showSubjectPart(2);
                        return true;
                    }
                </script>

                <div class="card-body">
                    <!-- make table responsive and larger: increase min-width and font-size -->
                    <div class="table-responsive">
                        <table class="table table-bordered" style="min-width:1200px; font-size:1.05rem;">
                            <thead>
                                <tr>
                                    <th scope="col">Teacher Role</th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Contact Number</th>
                                    <th scope="col">Email Address</th>
                                    <th scope="col">Grade Level</th>
                                    <th scope="col">Subject</th>
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
                                            <td>Adviser</td>
                                            <td><?php echo $row['adviserFullName'] ?></td>
                                            <td><?php echo $row['adviserContactNumber'] ?></td>
                                            <td><?php echo $row['adviserEmailAddress'] ?></td>
                                            <td><?php echo $row['adviserGrLvl'] ?></td>
                                            <td><?php echo $row['adviserSubject'] ?> </td>
                                            <td><?php echo $row['adviserSection'] ?></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <!-- Edit Button with Icon -->
                                                    <a href="#" class="btn btn-warning btn-edit btn-sm edit_adviser"
                                                        data-name="<?php echo $row['adviserFullName']; ?>"
                                                        data-contact="<?php echo $row['adviserContactNumber']; ?>"
                                                        data-email="<?php echo $row['adviserEmailAddress']; ?>"
                                                        data-grade="<?php echo $row['adviserGrLvl']; ?>"
                                                        data-section="<?php echo $row['adviserSection']; ?>"
                                                        data-subject="<?php echo $row['adviserSubject']; ?>"
                                                        data-bs-toggle="modal" data-bs-target="#editAdviser">
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

                                // Show Subject Teachers (with subject names from relation table)
                                $fetch_subject_query = "SELECT 
                                    st.stID, 
                                    st.stFullName, 
                                    st.stContactNumber, 
                                    st.stEmail, 
                                    st.stGradelvl,
                                    st.stSection,
                                    cs.section_id,
                                    cs.section_name,
                                    GROUP_CONCAT(DISTINCT s.subject_id) as subject_ids,
                                    GROUP_CONCAT(DISTINCT s.subject_name ORDER BY s.subject_name SEPARATOR ', ') as subject_names
                                    FROM subject_teachers st
                                    LEFT JOIN class_section cs ON cs.section_id = st.stSection
                                    LEFT JOIN subject_teacher_subjects sts ON sts.subject_teacher_id = st.stID
                                    LEFT JOIN subjects s ON s.subject_id = sts.subject_id
                                    GROUP BY st.stID, st.stFullName, st.stContactNumber, st.stEmail, st.stGradelvl, 
                                             st.stSection, cs.section_id, cs.section_name
                                    ORDER BY st.stFullName ASC";


                                $fetch_subject_query_run = mysqli_query($connection, $fetch_subject_query);

                                if (mysqli_num_rows($fetch_subject_query_run) > 0) {
                                    while ($row = mysqli_fetch_array($fetch_subject_query_run)) {
                                    ?>
                                        <tr>
                                            <td>Subject Teacher</td>
                                            <td><?php echo htmlspecialchars($row['stFullName']) ?></td>
                                            <td><?php echo htmlspecialchars($row['stContactNumber']) ?></td>
                                            <td><?php echo htmlspecialchars($row['stEmail']) ?></td>
                                            <td><?php echo htmlspecialchars($row['stGradelvl']) ?></td>
                                            <td><?php echo htmlspecialchars($row['subject_names']) ?></td>
                                            <td><?php echo htmlspecialchars($row['section_name']) ?></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <!-- Edit Button with Icon -->
                                                    <a href="#" class="btn btn-warning btn-edit btn-sm edit_subject_teacher"
                                                        data-id="<?php echo htmlspecialchars($row['stID']); ?>"
                                                        data-name="<?php echo htmlspecialchars($row['stFullName']); ?>"
                                                        data-contact="<?php echo htmlspecialchars($row['stContactNumber']); ?>"
                                                        data-email="<?php echo htmlspecialchars($row['stEmail']); ?>"
                                                        data-gradelevel="<?php echo htmlspecialchars($row['stGradelvl']); ?>"
                                                        data-section="<?php echo htmlspecialchars($row['section_id']); ?>"
                                                        data-subject="<?php echo htmlspecialchars($row['subject_ids']); ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editSubjectTeacher">
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
    </div>



    <!-- Edit Subject Teacher Modal -->
    <div class="modal fade" id="editSubjectTeacher" tabindex="-1" aria-labelledby="editSubjectTeacherLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editSubjectTeacherLabel">Edit Subject Teacher Account</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../PHP/subjectTeacherUpdate.php" method="POST">
                    <input type="hidden" name="stID" id="edit_stID">
                    <input type="hidden" name="action_type" value="edit">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="edit_stFullName">Full Name</label>
                            <input type="text" class="form-control" id="edit_stFullName"
                                name="stFullName" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_stContactNumber">Contact Number</label>
                            <input type="tel" class="form-control" id="edit_stContactNumber"
                                name="stContactNumber" required minlength="11" maxlength="11" pattern="\d{11}"
                                inputmode="numeric" oninput="this.value = this.value.replace(/\D/g,'');">
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_stEmail">Email Address</label>
                            <input type="email" class="form-control" id="edit_stEmail"
                                name="stEmail" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_stPassword">Password</label>
                            <input type="password" class="form-control" id="edit_stPassword"
                                name="stPassword" placeholder="Leave blank to keep current password">
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Subject Assignments</h5>
                        <p class="text-muted small">Each subject can be assigned to a different grade level and section.</p>
                        
                        <div id="subjectAssignmentsContainer">
                            <!-- Dynamic rows will be added here -->
                        </div>

                        <button type="button" class="btn btn-sm btn-success mt-2" onclick="addSubjectAssignmentRow()">
                            <i class="fas fa-plus"></i> Add Another Subject
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="updateSubjectTeacher" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let assignmentRowCounter = 0;

        // Get section and subject options once for reuse
        const sectionOptions = `<?php
            $conn = mysqli_connect("localhost", "root", "", "educguarddb");
            if ($conn) {
                $query = "SELECT section_id, section_name FROM class_section ORDER BY section_name ASC";
                $result = mysqli_query($conn, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="'.$row['section_id'].'">'.$row['section_name'].'</option>';
                    }
                }
                mysqli_close($conn);
            }
        ?>`;

        const subjectOptions = `<?php
            $conn = mysqli_connect("localhost", "root", "", "educguarddb");
            if ($conn) {
                $query = "SELECT subject_id, subject_name FROM subjects ORDER BY subject_name ASC";
                $result = mysqli_query($conn, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="'.$row['subject_id'].'">'.$row['subject_name'].'</option>';
                    }
                }
                mysqli_close($conn);
            }
        ?>`;

        function addSubjectAssignmentRow(gradeLevel = '', sectionId = '', subjectId = '') {
            const container = document.getElementById('subjectAssignmentsContainer');
            const rowId = assignmentRowCounter++;
            
            const rowHtml = `
                <div class="row g-2 mb-3 subject-assignment-row" id="assignment-row-${rowId}">
                    <div class="col-md-3">
                        <label class="form-label small">Grade Level</label>
                        <select name="stGradelvl[]" class="form-control form-control-sm" required>
                            <option value="">Select</option>
                            <option value="7" ${gradeLevel == '7' ? 'selected' : ''}>Grade 7</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Section</label>
                        <select name="stSection[]" class="form-control form-control-sm" required>
                            <option value="">Select Section</option>
                            ${sectionOptions}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Subject</label>
                        <select name="stSubject[]" class="form-control form-control-sm" required>
                            <option value="">Select Subject</option>
                            ${subjectOptions}
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeSubjectAssignmentRow(${rowId})" title="Remove">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', rowHtml);
            
            // Set the values after the HTML is inserted
            if (sectionId || subjectId) {
                const row = document.getElementById(`assignment-row-${rowId}`);
                if (sectionId) {
                    row.querySelector('select[name="stSection[]"]').value = sectionId;
                }
                if (subjectId) {
                    row.querySelector('select[name="stSubject[]"]').value = subjectId;
                }
            }
        }

        function removeSubjectAssignmentRow(rowId) {
            const row = document.getElementById(`assignment-row-${rowId}`);
            if (row) {
                row.remove();
            }
        }

        function clearSubjectAssignments() {
            const container = document.getElementById('subjectAssignmentsContainer');
            container.innerHTML = '';
            assignmentRowCounter = 0;
        }
    </script>

    <script src="../JS/adviserList.js"></script>
    <script src="../JS/subjectTeacherEdit_new.js"></script>

</body>
</html>




