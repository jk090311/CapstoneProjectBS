<?php include "adminNavbar.php"?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/CapstoneProjectBS/CSS/studentRegister.css">
    <title>Student Register</title>
</head>
<body>

    <div class="studentRegisterContainer">
        <h1 id="createAcc">Create Account</h1>
        <div class="formContainer">
            <div class="studentInfo">
                    <h2>Student Information</h2>
                <div class="profilePic">
                    <img id="profileImg" src="/CapstoneProjectBS/Assets/user_17827179.png" alt="Profile Picture">
                    </div>
                    <input type="file" id="uploadPic" accept="image/*">
                <label for="uploadPic" class="uploadBtn">Upload Picture</label>
            </div>
        

            <form class="formFormat" id="studentForm" action="\CapstoneProjectBS\PHP\saveStudData.php" method="POST">
            <div class="inputRow">
                <div class="inputGroup">
                    <label for="lrn">LRN</label>
                        <input type="text" id="lrn" name="lrn" placeholder="123456789" required>
                </div>
                <div class="inputGroup">
                    <label for="rfidNo">RFID Number</label>
                        <input type="text" id="rfidNo" name="rfidNo" placeholder="Scan Your RFIDs" required>
                </div>
            </div>
            
            <div class="inputRow">
                <div class="inputGroup">
                    <label for="fName">First Name</label>
                        <input type="text" id="fName" name="fName" placeholder="Kian" required>
                </div>
                <div class="inputGroup">
                    <label for="mName">Middle Name</label>
                        <input type="text" id="mName" name="mName" placeholder="Buentipo" required>
                </div>
                <div class="inputGroup">
                    <label for="lName">Last Name</label>
                        <input type="text" id="lName" name="lName" placeholder="Garcia" required>
                </div>
            </div>

            <div class="inputRow">
                <div class="inputGroup">
                    <label for="bDate">Birthdate</label>
                    <input type="date" id="bDate" name="bDate" required>
                </div>
            
                <div class="inputGroup">
                    <label for="sex">Sex</label>
                    <select id="sex" name="sex" required>
                        <option value="none">-</option>
                        <option value="M">M</option>
                        <option value="F">F</option>
                    </select>
                </div>
            
                <div class="inputGroup">
                    <label for="cNumber">Contact Number</label>
                    <input type="tel" id="cNumber" name="cNumber" placeholder="09123456789" required>
                </div>
            
                <div class="inputGroup">
                    <label for="grLvl">Grade Level</label>
                    <select id="grLvl" name="grLvl" required>
                        <option value="7">Grade 7</option>
                        <option value="8">Grade 8</option>
                        <option value="9">Grade 9</option>
                        <option value="10">Grade 10</option>
                    </select>
                </div>
                <div class="inputGroup">
                    <label for="section">Section</label>
                    <select id="section" name="section" required>
                        <?php
                        $connection = mysqli_connect("localhost", "root", "", "educguarddb");

                        if (!$connection) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        $section_query = "SELECT section_name FROM class_section";
                        $section_query_run = mysqli_query($connection, $section_query);

                        if (mysqli_num_rows($section_query_run) > 0) {
                            while ($section = mysqli_fetch_assoc($section_query_run)) {
                                echo '<option value="' . $section['section_name'] . '">' . $section['section_name'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No Sections Available</option>';
                        }

                        mysqli_close($connection);
                        ?>
                    </select>
                </div>
            </div>

            <div class="inputRow">
                <div class="inputGroupLong">
                <label for="address">Address</label>
                    <input type="text" id="address" name="address" placeholder="1234 Street, City, Province" required>
                <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder=example@gmail.com required>
                </div>
            </div>
            <div class="inputRow">
                <div class="inputGroup">
                    <label for="pName">Parent/Guardian Name</label>
                        <input type="text" id="pName" name="pName" placeholder="Juan Dela Cruz" required>
                </div>
                <div class="inputGroup">
                    <label for="pNum">Parent/Guardian Number</label>
                        <input type="tel" id="pNum" name="pNum" placeholder="123456789" required>
                </div>
            </div>
            <div class="inputRow">
                <div class="inputGroupLong">
                    <label for="pEmail">Parent/Guardian Email</label>
                        <input type="email" id="pEmail" name="pEmail" placeholder="example@gmail.com">
                </div>
            </div>
            <div class="inputRow">
                <div class="inputGroup">
                    <label for="username">Student Username</label>
                        <input type="text" id="username" name="username" required>
                </div>
                <div class="inputGroup">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" id="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="buttons">
                <button type="button" id="cancel" onclick="window.location.href='studentList.php'">Cancel</button>
                <button type="submit" id="submit">Submit</button>
            </div>

        </form>
        </div>
    </div>
    <script src="/CapstoneProjectBS/JS/studentRegister.js"></script>
</body>
</html>