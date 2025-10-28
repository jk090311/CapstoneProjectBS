<?php
// Start output buffering
// ob_start();

include "teacherNavbar.php";
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Not logged in, redirect to login page
    header("Location: ../PHPmain/index.php");
    exit();
}

$required_role = "adviser";
if ($_SESSION['user_role'] != $required_role) {
    if ($_SESSION['user_role'] == "admin") {
        header("Location: ../PHPAdviser/dashboardAdmin.php");
    } else if ($_SESSION['user_role'] == "student") {
        header("Location: dashboardStudent.php");
    }
    exit();
}
// Database connection
$conn = new mysqli("sql211.infinityfree.com", "if0_40275155", "EduGuard202526", "if0_40275155_eduguarddb");

// Handle grade submission first, before any HTML output
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id']) && isset($_POST['quarter']) && isset($_POST['grade']) && isset($_POST['subject_id'])) {
    // Clear any buffered output
    ob_clean();
    
    header('Content-Type: application/json');
    
    $student_id = $_POST['student_id'];
    $quarter_id = $_POST['quarter'];
    $grade = $_POST['grade'];
    $subject_id = $_POST['subject_id'];

    try {
        // First check if grade already exists
        $check_sql = "SELECT grade_id FROM grades 
                     WHERE student_id = ? 
                     AND subject_id = ? 
                     AND quarter_id = ?";

        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("iii", $student_id, $subject_id, $quarter_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $check_stmt->close();

        if ($check_result->num_rows > 0) {
            // Update existing grade
            $update_sql = "UPDATE grades 
                          SET grade = ? 
                          WHERE student_id = ? 
                          AND subject_id = ? 
                          AND quarter_id = ?";

            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("diii", $grade, $student_id, $subject_id, $quarter_id);
        } else {
            // Insert new grade
            $insert_sql = "INSERT INTO grades (student_id, subject_id, quarter_id, grade) 
                          VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("iiid", $student_id, $subject_id, $quarter_id, $grade);
        }

        $success = $stmt->execute();
        $stmt->close();

        // Make sure no output has been sent before this point
        ob_clean(); // Clear any output buffers
        echo json_encode(['success' => $success]);
        exit;
    } catch (Exception $e) {
        ob_clean(); // Clear any output buffers
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get adviser's section
$adviser_email = $_SESSION['user_email'];
$adviser_section_query = "SELECT adviserSection FROM advisers WHERE adviserEmailAddress = ?";
$stmt = $conn->prepare($adviser_section_query);
$stmt->bind_param("s", $adviser_email);
$stmt->execute();
$adviser_result = $stmt->get_result();
$adviser_section = ($adviser_result && $adviser_result->num_rows > 0) ? $adviser_result->fetch_assoc()['adviserSection'] : null;
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Management</title>
    <link rel="stylesheet" href="../CSS/Teacher/reportSystem.css">
    <!--
        NOTE: Scoped modern styles were added below to modernize the Subject/Student Grades UI
        and improve positioning/size of the form. These styles are intentionally kept in-page
        for quick iteration. To move them to an external stylesheet, copy the <style> block
        into ../CSS/Teacher/reportSystem.css and remove the duplicate rules.
    -->
    <style>
        /* Scoped modern styles for the report system page */
        body { font-family: 'Segoe UI', Roboto, Arial, sans-serif; background: #0f3340; color: #222; }
        .background-image { position:fixed; inset:0; background: url('../Assets/malinta.jpg') center/cover no-repeat; opacity:0.14; z-index:0; }
        .page-content { position:relative; z-index:1; padding:40px 16px; display:flex; justify-content:center; }
        .panel { max-width:1100px; width:100%; display:flex; flex-direction:column}

        /* Card wrapper for the main content */
    .card { background: rgba(255,255,255,0.98); border-radius:12px; box-shadow: 0 8px 28px rgba(2,12,26,0.35); padding:20px; position:relative; }

        .tabs { display:flex; gap:12px; justify-content:center; }
        .tabs button { padding:8px 14px; border-radius:22px; border:1px solid rgba(15,51,64,0.08); background:transparent; cursor:pointer; font-weight:600; }
        .tabs button.active { background:#0f7a8a; color:#fff; border-color:transparent; box-shadow:0 6px 14px rgba(15,122,138,0.12); }

        /* Subjects grid */
        .subject-container { display:grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap:16px; }
        .subject-box { cursor:pointer; border-radius:10px; overflow:hidden; background:linear-gradient(180deg, #fff, #fbfdff); border:1px solid #e6eef0; transition:transform .18s ease, box-shadow .18s ease; }
        .subject-box:hover { transform:translateY(-6px); box-shadow:0 10px 30px rgba(2,12,26,0.12); }
        .subject-content { padding:12px; display:flex; flex-direction:column; align-items:center; gap:8px; }
        .subject-img { width:100%; height:80px; object-fit:cover; border-radius:6px; }
        .subject-title { font-weight:700; color:#0b4b56; text-align:center; font-size:14px; }

        /* Student panel */
        #student-panel { max-width:980px; margin:0 auto; }
        #studentGridMain { margin-top:12px; }

        /* Injected subject grades fragment styles */
        #subject-grades-fragment { margin-top:8px; }
    .grades-table { width:100%; border-collapse:collapse; }
    .grades-table th, .grades-table td { padding:10px 8px; border-bottom:1px solid #eef3f4; vertical-align:middle; }
    /* Make grade inputs larger and more visible */
    .grade-input { width:84px; padding:8px 10px; border-radius:8px; border:1px solid #cfe6e9; text-align:center; font-size:15px; background:#fff; box-shadow: inset 0 1px 0 rgba(0,0,0,0.02); }
    .grade-input:disabled { background:#f7fbfb; color:#666; }
    .final-grade { font-weight:800; color:#0b7a43; font-size:15px; }
        .submit-grades-btn, .edit-grade-btn { padding:6px 10px; border-radius:6px; border:none; cursor:pointer; font-weight:600; }
        .submit-grades-btn { background:#28a745; color:white; }
        .edit-grade-btn { background:#ffb84d; color:#663f00; }

        /* Responsive tweaks */
        @media (max-width:900px) {
            .panel { padding:12px; }
            .subject-img { height:68px; }
            .grade-input { width:64px; }
        }
        /* Back button and subject header inside the subject fragment */
    /* Header inside the card for subject fragment */
    .subject-header { display:block; margin-bottom:14px; padding-bottom:6px; border-bottom:1px solid #eef6f7; }
    .subject-name { margin:0 0 10px 0; font-size:20px; color:#0b4b56; font-weight:900; }
    .subject-fragment { margin-top:8px; width:calc(100% + 80px); max-width:1200px; transform:translateX(-40px); }
    /* Back button placed at the top-left corner of the card */
    .back-to-subjects-btn { position:absolute; left:-40px; top:12px; padding:10px 14px; border-radius:10px; border:1px solid #d1e4e6; background:#fff; cursor:pointer; box-shadow:0 8px 20px rgba(2,12,26,0.06); font-weight:800; }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="background-image"></div>
    
    <header class="header">
        <button class="menu-button">☰</button>
        <img src="eduguard_logo.png" alt="EduGuard" class="logo">
        <h3 style="margin-left: 10px;">EduGuard</h3>
    </header>
    
    <div class="page-content">
        <div class="panel">
            <div class="card">
            <div class="tabs" style="margin-bottom:18px;">
                <button id="tabSubjectsBtn" class="active">Subject Grades</button>
                <button id="tabStudentsBtn">Student Grades</button>
            </div>
            <div id="tab-subjects" class="tab-content" style="display:block; width:100%;">
                <div id="subjects-list" class="subject-container">
            <?php
            // Fetch all subjects
            $query = "SELECT * FROM subjects ORDER BY subject_name ASC";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $subjectId = $row['subject_id'];
                $subjectName = $row['subject_name'];
                $subjectPicture = $row['subject_picture'];
                
                echo "<div class='subject-box' data-subject-id='{$subjectId}' data-subject-name='{$subjectName}'>
                    <div class='subject-content'>
                        <img class='subject-img' src='../Uploads/{$subjectPicture}' alt='{$subjectName}'>
                        <div class='subject-title'>{$subjectName}</div>
                    </div>
                </div>";
            }
            ?>
                </div>
            </div>

            <div id="tab-students" class="tab-content" style="display:none; width:100%;">
                <div id="student-panel" class="card">
                    <h3 style="margin-top:0;">Student Grades</h3>
                    <div style="display:flex; gap:8px; margin-bottom:12px; align-items:center;">
                        <input id="studentSearchMain" type="text" placeholder="Enter student LRN (e.g. 123456789)..." style="flex:1; padding:10px 12px; border-radius:8px; border:1px solid #d7e3e5;">
                        <button id="searchStudentBtn" style="padding:10px 12px; border-radius:8px; border:1px solid #0f7a8a; background:#0f7a8a; color:#fff; cursor:pointer;">Search</button>
                    </div>
                    <div id="studentGridMain">
                        <p style="color:#666; margin:0;">Search for a student to view grades here.</p>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
 
    <script>
        $(document).ready(function() {
            const tabSubjectsBtn = document.getElementById('tabSubjectsBtn');
            const tabStudentsBtn = document.getElementById('tabStudentsBtn');
            const tabSubjects = document.getElementById('tab-subjects');
            const tabStudents = document.getElementById('tab-students');

            function showSubjectsTab() {
                tabSubjects.style.display = 'block';
                tabStudents.style.display = 'none';
                tabSubjectsBtn.classList.add('active');
                tabStudentsBtn.classList.remove('active');
            }

            function showStudentsTab() {
                tabSubjects.style.display = 'none';
                tabStudents.style.display = 'block';
                tabStudentsBtn.classList.add('active');
                tabSubjectsBtn.classList.remove('active');
            }

            tabSubjectsBtn.addEventListener('click', showSubjectsTab);
            tabStudentsBtn.addEventListener('click', showStudentsTab);

            // cache original subjects HTML so we can restore it when Back is clicked
            const subjectsListEl = document.getElementById('subjects-list');
            let originalSubjectsHTML = subjectsListEl ? subjectsListEl.innerHTML : '';

            function bindSubjectClicks() {
                $('.subject-box').off('click').on('click', function(e) {
                    e.preventDefault(); e.stopPropagation();

                    const subjectId = $(this).data('subject-id');
                    const subjectName = $(this).data('subject-name');

                    // keep the user in Subject Grades tab; inject fragment here
                    showSubjectsTab();
                    subjectsListEl.innerHTML = '<p>Loading ' + subjectName + '...</p>';

                    // load first page by default (page=1)
                    const perPage = 10;
                    function loadGradesPage(sid, sname, page){
                        const url = 'getGrades.php?ajax=1&subject_id=' + encodeURIComponent(sid) + '&subject_name=' + encodeURIComponent(sname) + '&page=' + encodeURIComponent(page) + '&per_page=' + encodeURIComponent(perPage);
                        subjectsListEl.innerHTML = '<p>Loading ' + sname + ' (page '+page+')...</p>';
                        return fetch(url).then(resp => resp.text());
                    }

                    // store current subject so delegated handlers can use it
                    let currentSubject = { id: subjectId, name: subjectName };

                    function renderFragment(html, sname){
                        subjectsListEl.innerHTML = `
                            <button id="backToSubjects" class="back-to-subjects-btn">← Back to Subjects</button>
                            <div class="subject-header">
                                <h2 class="subject-name">${sname}</h2>
                            </div>
                            <div id="subject-grades-fragment" class="subject-fragment">${html}</div>
                        `;
                        const frag2 = document.getElementById('subject-grades-fragment');
                        initInjectedGradesFragment(frag2);
                    }

                    // initializer for behaviors inside a fragment
                    function initInjectedGradesFragment(container) {
                        if (!container) return;

                        function showNotification(message, type) {
                            const existing = document.getElementById('notification');
                            if (existing) {
                                existing.textContent = message;
                                existing.className = `notification ${type}`;
                                existing.classList.add('show');
                                setTimeout(() => existing.classList.remove('show'), 3000);
                            } else {
                                alert(message);
                            }
                        }

                        function updateFinalGrade(row) {
                            const gradeInputs = row.querySelectorAll('.grade-input');
                            const finalGradeSpan = row.querySelector('.final-grade');
                            let sum = 0; let count = 0;
                            gradeInputs.forEach(input => { if (input.value) { sum += parseInt(input.value); count++; } });
                            finalGradeSpan.textContent = count > 0 ? Math.round(sum / count) : '';
                        }

                        container.querySelectorAll('.submit-grades-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                const studentId = this.dataset.studentId;
                                const subjectId = this.dataset.subjectId;
                                const row = this.closest('tr');
                                const gradeInputs = row.querySelectorAll('.grade-input');
                                const grades = {};
                                gradeInputs.forEach(input => { const quarter = input.dataset.quarter; const grade = input.value; if (grade) grades[quarter] = grade; });

                                let isValid = true;
                                Object.values(grades).forEach(grade => { if (grade < 0 || grade > 100) isValid = false; });
                                if (!isValid) { showNotification('Grades must be between 0 and 100', 'error'); return; }

                                fetch('getGrades.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        student_id: studentId,
                                        subject_id: subjectId,
                                        grades: grades
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                        window.location.reload(true);
                                    }
                                    const data = response.text();
                                    console.log('Response received, reloading...', data);
                                    // return await response.text();
                                    window.location.reload(true);                                })
                                .then(data => {
                                    if (data.success) {
                                        showNotification('Grades submitted successfully', 'success');
                                        setTimeout(() => {
                                            window.location.reload(true);
                                        }, 500);
                                    } else {
                                        showNotification('Error submitting grades: ' + (data.error || 'Unknown'), 'error');
                                        window.location.reload(true);
                                    }
                                })
                             
                            });
                        });

                        container.querySelectorAll('.edit-grade-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                const row = this.closest('tr');
                                row.querySelectorAll('.grade-input').forEach(i => i.disabled = false);
                                this.textContent = 'Editing...'; this.style.backgroundColor = '#dc3545'; this.style.color = 'white';
                            });
                        });

                        container.querySelectorAll('.grade-input').forEach(input => {
                            input.addEventListener('input', function() {
                                const row = this.closest('tr'); updateFinalGrade(row);
                                const value = parseInt(this.value);
                                if (value < 0 || value > 100) { this.style.backgroundColor = '#ffebee'; showNotification('Grade must be between 0 and 100', 'error'); }
                                else this.style.backgroundColor = 'white';
                            });
                        });
                    }

                    // initial load and render
                    loadGradesPage(subjectId, subjectName, 1)
                        .then(html => renderFragment(html, subjectName))
                        .catch(err => { console.error(err); subjectsListEl.innerHTML = '<p class="error">Error loading grades.</p>'; });

                    // delegated click handling for pagination and back button
                    if (!subjectsListEl._delegationAdded) {
                        subjectsListEl.addEventListener('click', function(e){
                            const btn = e.target.closest && e.target.closest('.pagination-btn');
                            if (btn) {
                                const p = btn.dataset.page;
                                if (currentSubject && currentSubject.id) loadGradesPage(currentSubject.id, currentSubject.name, p).then(html => renderFragment(html, currentSubject.name));
                                return;
                            }
                            const back = e.target.closest && e.target.closest('#backToSubjects');
                            if (back) {
                                subjectsListEl.innerHTML = originalSubjectsHTML;
                                bindSubjectClicks();
                            }
                        });
                        subjectsListEl._delegationAdded = true;
                    }
                });
            }

            bindSubjectClicks();
            // Student search behavior
            document.getElementById('searchStudentBtn').addEventListener('click', function(){
                const val = document.getElementById('studentSearchMain').value.trim();
                if (!val) { alert('Please enter a student LRN'); return; }
                // Search by LRN (preferred) -- backend accepts either 'lrn' or 'student_id'
                const url = 'getStudentGrades.php?ajax=1&lrn=' + encodeURIComponent(val);
                const target = document.getElementById('studentGridMain');
                target.innerHTML = '<p>Loading student ' + val + '...</p>';
                fetch(url).then(r=>r.text()).then(html=>{ target.innerHTML = html; }).catch(err=>{ target.innerHTML = '<p class="error">Error loading student grades.</p>'; console.error(err); });
                
                // Delegated handler for export button inside the injected student fragment
                const studentGrid = document.getElementById('studentGridMain');
                if (studentGrid && !studentGrid._exportHandlerAdded) {
                    studentGrid.addEventListener('click', function(ev){
                        const btn = ev.target.closest && ev.target.closest('#exportStudentBtn');
                        if (!btn) return;
                        // Export supports either data-student-lrn or data-student-id (fallback)
                        const lrn = btn.getAttribute('data-student-lrn');
                        const sid = btn.getAttribute('data-student-id');
                        if (lrn) {
                            const url2 = 'getStudentGradesExport.php?lrn=' + encodeURIComponent(lrn);
                            window.open(url2, '_blank');
                            return;
                        }
                        if (sid) {
                            const url2 = 'getStudentGradesExport.php?student_id=' + encodeURIComponent(sid);
                            window.open(url2, '_blank');
                            return;
                        }
                        return alert('Missing student identifier (LRN or ID)');
                    });
                    studentGrid._exportHandlerAdded = true;
                }
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>