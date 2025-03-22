<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}
include "db_conn.php";
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : $_SESSION['super_admin_name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tableName = "bcp-sms3_tracer";
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $yearGraduated = $_POST['yearGraduated'];
    $courseProgram = $_POST['courseProgram'];
    $courseProgramOther = $_POST['courseProgramOther'];
    $studentNo = $_POST['studentNo'];
    $birthDate = $_POST['birthDate'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $homeAddress = $_POST['homeAddress'];
    $telephoneNumber = $_POST['telephoneNumber'];
    $mobileNumber = $_POST['mobileNumber'];
    $email = $_POST['email'];
    $currentJobPosition = $_POST['currentJobPosition'];
    $companyAddress = $_POST['companyAddress'];
    $placeOfWork = $_POST['placeOfWork'];
    $department = $_POST['department'];
    $employmentRecord = $_POST['employmentRecord'];
    $monthlySalary = $_POST['monthlySalary'];
    $promoted = $_POST['promoted'];
    $created_at = date("Y-m-d H:i:s");

    // Database connection
    $conn = new mysqli("localhost", "username", "password", "database");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO `$tableName` (`lastName`, `firstName`, `middleName`, `yearGraduated`, `courseProgram`, `courseProgramOther`, `studentNo`, `birthDate`, `age`, `gender`, `homeAddress`, `telephoneNumber`, `mobileNumber`, `email`, `currentJobPosition`, `companyAddress`, `placeOfWork`, `department`, `employmentRecord`, `monthlySalary`, `promoted`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssssssss", $lastName, $firstName, $middleName, $yearGraduated, $courseProgram, $courseProgramOther, $studentNo, $birthDate, $age, $gender, $homeAddress, $telephoneNumber, $mobileNumber, $email, $currentJobPosition, $companyAddress, $placeOfWork, $department, $employmentRecord, $monthlySalary, $promoted, $created_at);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Tracer Form</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Alumni Tracer Form</h2>
        <form action="submit_alumni_tracer.php" method="post">
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>
            <div class="mb-3">
                <label for="middleName" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middleName" name="middleName" required>
            </div>
            <div class="mb-3">
                <label for="yearGraduated" class="form-label">Year Graduated</label>
                <select class="form-control" id="yearGraduated" name="yearGraduated" required>
                    <?php
                    $currentYear = date("Y");
                    for ($year = $currentYear; $year >= 1950; $year--) {
                        echo "<option value=\"$year\">$year</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="courseProgram" class="form-label">Course/Program</label>
                <select class="form-control" id="courseProgram" name="courseProgram" required>
                    <option selected value="" disabled hidden>Choose...</option>
                    <option value="BSE">Bachelor of Science in Entrepreneurship</option>
                    <option value="BEED">Bachelor in Elementary Education</option>
                    <option value="BSEd">Bachelor in Secondary Education</option>
                    <option value="BSCRIM">Bachelor of Science in Criminology</option>
                    <option value="BSPSYCH">Bachelor of Science in Psychology</option>
                    <option value="BSIE">Bachelor of Science in Industrial Engineering</option>
                    <option value="BSBA">Bachelor of Science in Business Administration</option>
                    <option value="BSCE">Bachelor of Science in Computer Engineering</option>
                    <option value="BSIT">Bachelor of Science in Information Technology</option>
                    <option value="BSHM">Bachelor of Science in Hospitality Management</option>
                    <option value="BSOA">Bachelor of Science in Office Administration</option>
                    <option value="BSLIS">Bachelor of Library and Information Science</option>
                    <option value="other">Other</option>
                </select>
                <input type="text" class="form-control mt-2" id="courseProgramOther" name="courseProgramOther" placeholder="Please specify if other" style="display:none;">
            </div>
          <div class="mb-3">    
                <label for="studentNo" class="form-label">Student No</label>
                <input type="text" class="form-control" pattern="\d{8}" name="studentNo" maxlength="8" 
         oninput="this.value=this.value.replace(/[^0-9]/g,'')" required 
         title="Contact number must be exactly 11 digits.">
          <div class="invalid-feedback">
    Please enter exactly 8 digits
       </div>
            
            <div class="mb-3">
                <label for="birthDate" class="form-label">Birth Date</label>
                <input type="date" class="form-control" id="birthDate" name="birthDate" required>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" name="age" required>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="homeAddress" class="form-label">Home Address</label>
                <input type="text" class="form-control" id="homeAddress" name="homeAddress" required>
            </div>
            <div class="mb-3">
                <label for="telephoneNumber" class="form-label">Telephone Number</label>
                <input type="text" class="form-control" pattern="\d{8}" name="telephoneNumber" maxlength="8" 
         oninput="this.value=this.value.replace(/[^0-9]/g,'')" required 
         title="Contact number must be exactly 11 digits.">
          <div class="invalid-feedback">
    Please enter exactly 8 digits
       </div>
            <div class="mb-3">
                <label for="mobileNumber" class="form-label">Mobile Number</label>
                <input type="text" class="form-control" pattern="\d{11}" name="mobileNumber" maxlength="11" 
         oninput="this.value=this.value.replace(/[^0-9]/g,'')" required 
         title="Contact number must be exactly 11 digits.">
          <div class="invalid-feedback">
    Please enter exactly 11 digits
       </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="currentJobPosition" class="form-label">Current Job Position</label>
                <input type="text" class="form-control" id="currentJobPosition" name="currentJobPosition" required>
            </div>
            <div class="mb-3">
                <label for="companyAddress" class="form-label">Company Address</label>
                <input type="text" class="form-control" id="companyAddress" name="companyAddress" required>
            </div>
            <div class="mb-3">
                <label for="placeOfWork" class="form-label">Place of Work (Local/Abroad)</label>
                <select class="form-control" id="placeOfWork" name="placeOfWork" required>
                    <option value="local">Local</option>
                    <option value="abroad">Abroad</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department (College/Graduate School)</label>
                <select class="form-control" id="department" name="department" required>
                    <option value="college">College</option>
                    <option value="graduate_school">Graduate School</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="employmentRecord" class="form-label">Employment Record</label>
                <textarea class="form-control" id="employmentRecord" name="employmentRecord" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="monthlySalary" class="form-label">Monthly Salary</label>
                <select class="form-control" id="monthlySalary" name="monthlySalary" required>
                    <option value="Below 15000">Below 15,000</option>
                    <option value="15001 - 20000">15,001 - 20,000</option>
                    <option value="20001 - 25000">20,001 - 25,000</option>
                    <option value="25001 Above">25,001 Above</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="promoted" class="form-label">Have you been promoted in your current job?</label>
                <select class="form-control" id="promoted" name="promoted" required>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script>
        document.getElementById('courseProgram').addEventListener('change', function () {
            var otherInput = document.getElementById('courseProgramOther');
            if (this.value === 'other') {
                otherInput.style.display = 'block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
            }
        });
    </script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
