<?php
// Removed session_start() and session checks
include "db_conn.php";
// Removed $admin_name assignment

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tableName = "bcp-sms3_tracer";
    $lastName = htmlspecialchars($_POST['lastName']);
    $firstName = htmlspecialchars($_POST['firstName']);
    $middleName = htmlspecialchars($_POST['middleName']);
    $yearGraduated = htmlspecialchars($_POST['yearGraduated']);
    $courseProgram = htmlspecialchars($_POST['courseProgram']);
    $courseProgramOther = htmlspecialchars($_POST['courseProgramOther']);
    $studentNo = htmlspecialchars($_POST['studentNo']);
    $birthDate = htmlspecialchars($_POST['birthDate']);
    $age = htmlspecialchars($_POST['age']);
    $gender = htmlspecialchars($_POST['gender']);
    $homeAddress = htmlspecialchars($_POST['homeAddress']);
    $telephoneNumber = htmlspecialchars($_POST['telephoneNumber']);
    $mobileNumber = htmlspecialchars($_POST['mobileNumber']);
    $email = htmlspecialchars($_POST['email']);
    $currentJobPosition = htmlspecialchars($_POST['currentJobPosition']);
    $companyAddress = htmlspecialchars($_POST['companyAddress']);
    $placeOfWork = htmlspecialchars($_POST['placeOfWork']);
    $department = htmlspecialchars($_POST['department']);
    $employmentRecord = htmlspecialchars($_POST['employmentRecord']);
    $monthlySalary = htmlspecialchars($_POST['monthlySalary']);
    $promoted = htmlspecialchars($_POST['promoted']);
    $created_at = date("Y-m-d H:i:s");

    // Database connection
    $conn = new mysqli("localhost", "username", "password", "database");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email exists in `bcp-sms3_alumnidata` and fetch its ID
    $alumniDataId = null;
    $checkAlumniSql = "SELECT `id` FROM `bcp-sms3_alumnidata` WHERE `email` = ?";
    $checkAlumniStmt = $conn->prepare($checkAlumniSql);
    $checkAlumniStmt->bind_param("s", $email);
    $checkAlumniStmt->execute();
    $checkAlumniStmt->store_result();

    if ($checkAlumniStmt->num_rows > 0) {
        $checkAlumniStmt->bind_result($alumniDataId);
        $checkAlumniStmt->fetch();
    }
    $checkAlumniStmt->close();

    // Check if email already exists and matches the provided studentNo in `bcp-sms3_tracer`
    $checkEmailSql = "SELECT `email`, `studentNo` FROM `bcp-sms3_tracer` WHERE `email` = ?";
    $checkStmt = $conn->prepare($checkEmailSql);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $checkStmt->bind_result($existingEmail, $existingStudentNo);
        $checkStmt->fetch();

        if ($existingStudentNo !== $studentNo) {
            echo "Error: The email address is already registered with a different student number.";
            $checkStmt->close();
            $conn->close();
            exit;
        }
    }
    $checkStmt->close();

    // Insert data into `bcp-sms3_tracer` with the linked alumni ID
    $sql = "INSERT INTO `bcp-sms3_tracer` (`lastName`, `firstName`, `middleName`, `yearGraduated`, `courseProgram`, `courseProgramOther`, `studentNo`, `birthDate`, `age`, `gender`, `homeAddress`, `telephoneNumber`, `mobileNumber`, `email`, `currentJobPosition`, `companyAddress`, `placeOfWork`, `department`, `employmentRecord`, `monthlySalary`, `promoted`, `created_at`, `alumniDataId`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssssssssi", $lastName, $firstName, $middleName, $yearGraduated, $courseProgram, $courseProgramOther, $studentNo, $birthDate, $age, $gender, $homeAddress, $telephoneNumber, $mobileNumber, $email, $currentJobPosition, $companyAddress, $placeOfWork, $department, $employmentRecord, $monthlySalary, $promoted, $created_at, $alumniDataId);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['studentNo'])) {
    $studentNo = htmlspecialchars($_GET['studentNo']);

    // Database connection
    $conn = new mysqli("localhost", "username", "password", "database");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch data for the given student number
    $sql = "SELECT `lastName`, `firstName`, `middleName`, `yearGraduated`, `courseProgram`, `courseProgramOther`, `birthDate`, `age`, `gender`, `homeAddress`, `telephoneNumber`, `mobileNumber`, `email`, `currentJobPosition`, `companyAddress`, `placeOfWork`, `department`, `employmentRecord`, `monthlySalary`, `promoted` FROM `bcp-sms3_tracer` WHERE `studentNo` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $studentNo);
    $stmt->execute();
    $stmt->bind_result($lastName, $firstName, $middleName, $yearGraduated, $courseProgram, $courseProgramOther, $birthDate, $age, $gender, $homeAddress, $telephoneNumber, $mobileNumber, $email, $currentJobPosition, $companyAddress, $placeOfWork, $department, $employmentRecord, $monthlySalary, $promoted);
    $stmt->fetch();
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
        <form action="submit_alumni_tracer.php" method="post" id="alumniTracerForm">
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName ?? ''); ?>" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName ?? ''); ?>" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="middleName" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middleName" name="middleName" value="<?php echo htmlspecialchars($middleName ?? ''); ?>" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="yearGraduated" class="form-label">Year Graduated</label>
                <select class="form-control" id="yearGraduated" name="yearGraduated" required>
                    <?php
                    $currentYear = date("Y");
                    for ($year = $currentYear; $year >= 1950; $year--) {
                        $selected = ($year == ($yearGraduated ?? '')) ? 'selected' : '';
                        echo "<option value=\"$year\" $selected>$year</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="courseProgram" class="form-label">Graduated Course/Program</label>
                <select class="form-control" id="courseProgram" name="courseProgram" required>
                    <option selected value="" disabled hidden>Choose...</option>
                    <option value="BSE" <?php echo ($courseProgram ?? '') === 'BSE' ? 'selected' : ''; ?>>Bachelor of Science in Entrepreneurship</option>
                    <option value="BEED" <?php echo ($courseProgram ?? '') === 'BEED' ? 'selected' : ''; ?>>Bachelor in Elementary Education</option>
                    <option value="BSEd" <?php echo ($courseProgram ?? '') === 'BSEd' ? 'selected' : ''; ?>>Bachelor in Secondary Education</option>
                    <option value="BSCRIM" <?php echo ($courseProgram ?? '') === 'BSCRIM' ? 'selected' : ''; ?>>Bachelor of Science in Criminology</option>
                    <option value="BSPSYCH" <?php echo ($courseProgram ?? '') === 'BSPSYCH' ? 'selected' : ''; ?>>Bachelor of Science in Psychology</option>
                    <option value="BSIE" <?php echo ($courseProgram ?? '') === 'BSIE' ? 'selected' : ''; ?>>Bachelor of Science in Industrial Engineering</option>
                    <option value="BSBA" <?php echo ($courseProgram ?? '') === 'BSBA' ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>
                    <option value="BSCE" <?php echo ($courseProgram ?? '') === 'BSCE' ? 'selected' : ''; ?>>Bachelor of Science in Computer Engineering</option>
                    <option value="BSIT" <?php echo ($courseProgram ?? '') === 'BSIT' ? 'selected' : ''; ?>>Bachelor of Science in Information Technology</option>
                    <option value="BSHM" <?php echo ($courseProgram ?? '') === 'BSHM' ? 'selected' : ''; ?>>Bachelor of Science in Hospitality Management</option>
                    <option value="BSOA" <?php echo ($courseProgram ?? '') === 'BSOA' ? 'selected' : ''; ?>>Bachelor of Science in Office Administration</option>
                    <option value="BSLIS" <?php echo ($courseProgram ?? '') === 'BSLIS' ? 'selected' : ''; ?>>Bachelor of Library and Information Science</option>
                    <option value="other" <?php echo ($courseProgram ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
                <input type="text" class="form-control mt-2" id="courseProgramOther" name="courseProgramOther" placeholder="Please specify if other" value="<?php echo htmlspecialchars($courseProgramOther ?? ''); ?>" style="<?php echo ($courseProgram ?? '') === 'other' ? 'display:block;' : 'display:none;'; ?>" autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="studentNo" class="form-label">Student No</label>
                <input type="text" class="form-control" id="studentNo" name="studentNo" value="<?php echo htmlspecialchars($studentNo ?? ''); ?>" required autocomplete="new-password">
                <div class="invalid-feedback" id="studentNoError"></div>
            </div>
            <div class="mb-3">
                <label for="birthDate" class="form-label">Birth Date</label>
                <input type="date" class="form-control" id="birthDate" name="birthDate" value="<?php echo htmlspecialchars($birthDate ?? ''); ?>" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($age ?? ''); ?>" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="male" <?php echo ($gender ?? '') === 'male' ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo ($gender ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="homeAddress" class="form-label">Home Address</label>
                <input type="text" class="form-control" id="homeAddress" name="homeAddress" value="<?php echo htmlspecialchars($homeAddress ?? ''); ?>" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="telephoneNumber" class="form-label">Telephone Number</label>
                <input type="text" class="form-control" id="telephoneNumber" name="telephoneNumber" value="<?php echo htmlspecialchars($telephoneNumber ?? ''); ?>" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="mobileNumber" class="form-label">Mobile Number</label>
                <input type="text" class="form-control" id="mobileNumber" name="mobileNumber" value="<?php echo htmlspecialchars($mobileNumber ?? ''); ?>" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="currentJobPosition" class="form-label">Current Job Position</label>
                <input type="text" class="form-control" id="currentJobPosition" name="currentJobPosition" value="<?php echo htmlspecialchars($currentJobPosition ?? ''); ?>" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="companyAddress" class="form-label">Company Address</label>
                <input type="text" class="form-control" id="companyAddress" name="companyAddress" value="<?php echo htmlspecialchars($companyAddress ?? ''); ?>" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="placeOfWork" class="form-label">Place of Work (Local/Abroad)</label>
                <select class="form-control" id="placeOfWork" name="placeOfWork" required>
                    <option value="local" <?php echo ($placeOfWork ?? '') === 'local' ? 'selected' : ''; ?>>Local</option>
                    <option value="abroad" <?php echo ($placeOfWork ?? '') === 'abroad' ? 'selected' : ''; ?>>Abroad</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department (College/Graduate School)</label>
                <select class="form-control" id="department" name="department" required>
                    <option value="college" <?php echo ($department ?? '') === 'college' ? 'selected' : ''; ?>>College</option>
                    <option value="graduate_school" <?php echo ($department ?? '') === 'graduate_school' ? 'selected' : ''; ?>>Graduate School</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="employmentRecord" class="form-label">Employment Record</label>
                <textarea class="form-control" id="employmentRecord" name="employmentRecord" rows="3" required><?php echo htmlspecialchars($employmentRecord ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="monthlySalary" class="form-label">Monthly Salary</label>
                <select class="form-control" id="monthlySalary" name="monthlySalary" required>
                    <option value="Below 15000" <?php echo ($monthlySalary ?? '') === 'Below 15000' ? 'selected' : ''; ?>>Below 15,000</option>
                    <option value="15001 - 20000" <?php echo ($monthlySalary ?? '') === '15001 - 20000' ? 'selected' : ''; ?>>15,001 - 20,000</option>
                    <option value="20001 - 25000" <?php echo ($monthlySalary ?? '') === '20001 - 25000' ? 'selected' : ''; ?>>20,001 - 25,000</option>
                    <option value="25001 Above" <?php echo ($monthlySalary ?? '') === '25001 Above' ? 'selected' : ''; ?>>25,001 Above</option>
                    <option value="Prefer not to say" <?php echo ($monthlySalary ?? '') === 'Prefer not to say' ? 'selected' : ''; ?>>Prefer not to say</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="promoted" class="form-label">Have you been promoted in your current job?</label>
                <select class="form-control" id="promoted" name="promoted" required>
                    <option value="yes" <?php echo ($promoted ?? '') === 'yes' ? 'selected' : ''; ?>>Yes</option>
                    <option value="no" <?php echo ($promoted ?? '') === 'no' ? 'selected' : ''; ?>>No</option>
                </select>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="terms" required>
                <label class="form-check-label" for="terms">I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a></label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
Furthermore, you acknowledge your rights as a data subject under the Data Privacy Act of 2012, including the right to be 
informed, the right to access, the right to rectify, the right to object to data processing,
 the right to data portability, the right to erasure, and the right to damages in case of misuse 
 or unauthorized processing of your data. You may exercise these rights by contacting our Data Protection
 Officer (DPO) through the provided contact details in our privacy policy. By agreeing to these terms and
  conditions, you accept that failure to provide accurate and truthful information may result in the denial 
  of services, termination of agreements, or legal consequences. Our organization reserves the right to amend
   these terms to reflect any changes in laws or operational practices, and it is your responsibility to periodically review these terms. Continued use of our services following any updates 
   constitutes your acceptance of the revised terms."</p>
                    <!-- Add your terms and conditions content here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="acceptTerms" >Accept</button>
                </div>
            </div>
        </div>
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

        document.getElementById('termsModal').addEventListener('scroll', function () {
            var modalBody = this.querySelector('.modal-body');
            if (modalBody.scrollTop + modalBody.clientHeight >= modalBody.scrollHeight) {
                document.getElementById('acceptTerms').disabled = false;
            }
        });

        document.getElementById('acceptTerms').addEventListener('click', function () {
            document.getElementById('terms').disabled = false;
            document.getElementById('terms').checked = true;
            var modal = bootstrap.Modal.getInstance(document.getElementById('termsModal'));
            modal.hide();
        });

        document.getElementById('studentNo').addEventListener('input', function () {
            const studentNoField = this;
            const studentNoError = document.getElementById('studentNoError');
            const studentNo = studentNoField.value;

            // Allow only numbers and limit to 8 digits
            if (!/^\d{0,8}$/.test(studentNo)) {
                studentNoField.classList.add('is-invalid');
                studentNoError.textContent = "Student number must be numeric and up to 8 digits.";
                return;
            } else {
                studentNoField.classList.remove('is-invalid');
                studentNoError.textContent = "";
            }

            // Check for duplicate student number via AJAX
            if (studentNo.length > 0) {
                fetch(`check_student_no.php?studentNo=${studentNo}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            studentNoField.classList.add('is-invalid');
                            studentNoError.textContent = "This student number is already registered.";
                        } else {
                            studentNoField.classList.remove('is-invalid');
                            studentNoError.textContent = "";
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
