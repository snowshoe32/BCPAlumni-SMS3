<?php
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
    include 'db_conn.php';

    $sql = "INSERT INTO `$tableName` (`lastName`, `firstName`, `middleName`, `yearGraduated`, `courseProgram`, `courseProgramOther`, `studentNo`, `birthDate`, `age`, `gender`, `homeAddress`, `telephoneNumber`, `mobileNumber`, `email`, `currentJobPosition`, `companyAddress`, `placeOfWork`, `department`, `employmentRecord`, `monthlySalary`, `promoted`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssssssss", $lastName, $firstName, $middleName, $yearGraduated, $courseProgram, $courseProgramOther, $studentNo, $birthDate, $age, $gender, $homeAddress, $telephoneNumber, $mobileNumber, $email, $currentJobPosition, $companyAddress, $placeOfWork, $department, $employmentRecord, $monthlySalary, $promoted, $created_at);

    if ($stmt->execute()) {
        $message = "Form Submitted Successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Alumni Tracer</title>
</head>
<body>
    <!-- Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultModalLabel">Submission Result</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $message; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='alumni_dashboard.php'">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#resultModal').modal('show');
        });
    </script>
</body>
</html>
