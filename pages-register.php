<?php
session_start();
include "db_conn.php"; 
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $student_no = mysqli_real_escape_string($conn, $_POST['student_no']);
    if (strlen($student_no) > 11) {
        $error[] = 'Student Number must be a maximum of 11 characters!';
    }
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hashing the password
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);

    // Check if email already exists
    $check_email = "SELECT * FROM `bcp_sms3_user` WHERE email = '$email'";
    $result_email = mysqli_query($conn, $check_email);

    // Check if student number already exists
    $check_student_no = "SELECT * FROM `bcp_sms3_user` WHERE student_no = '$student_no'";
    $result_student_no = mysqli_query($conn, $check_student_no);

    if (mysqli_num_rows($result_email) > 0) {
        $error[] = 'This Email is already registered!';
    } elseif (mysqli_num_rows($result_student_no) > 0) {
        $error[] = 'This Student Number is already taken!';
    } else {
        $token = bin2hex(random_bytes(50)); // Generate a unique token
        $sql = "INSERT INTO `bcp_sms3_user` (`name`, `email`, `student_no`, `password`,`username`, `user_type`, `token`, `is_verified`) 
                VALUES ('$name', '$email', '$student_no', '$password','$username', '$user_type', '$token', 0)";

        if (mysqli_query($conn, $sql)) {
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth   = true;
                $mail->Username   = 'snowshoe0103@gmail.com'; // SMTP username
                $mail->Password   = 'bvoa zaxb ugki vtwm'; // SMTP password (use App Password if 2-Step Verification is enabled)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('snowshoe0103@gmail.com', 'Mailer');
                $mail->addAddress($email, $name);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Account Verification';
                $mail->Body    = "Hi $name, Click the link below to verify your account: 
                                  <a href='http://localhost/public_html/verify.php?token=$token'>Verify Account</a>";

                $mail->send();
                $_SESSION['success'] = 'Account created successfully! Please check your email to verify your account.';
                echo "Account created successfully! Please check your email to verify your account.";
                header("Location: index.php");
                exit();
            } catch (Exception $e) {
                $error[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $error[] = 'Failed to register the user! MySQL Error: ' . mysqli_error($conn);
        }
    }
}

if (isset($error)) {
    foreach ($error as $msg) {
        echo "<div class='alert alert-danger'>$msg</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pages / Login - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="https://elc-public-images.s3.ap-southeast-1.amazonaws.com/bcp-olp-logo-mini2.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="https://elc-public-images.s3.ap-southeast-1.amazonaws.com/bcp-olp-logo-mini2.png" alt="Logo">
                  <span class="d-none d-lg-block">Alumni Management System</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">
                  

                
                  
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Create a new Account</h5>
                    <p class="text-center small">Enter your personal details to create account</p>
                  </div>

                  <form class="row g-3" method="POST" action="pages-register.php">
    <div class="col-12">
        <label for="yourName" class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" id="yourName" required autocomplete="off">
        <div class="invalid-feedback">Please, enter your name!</div>
    </div>

    <div class="col-12">
        <label for="yourEmail" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" id="yourEmail" required autocomplete="off">
        <div class="invalid-feedback">Please enter a valid Email address!</div>
    </div>

    <div class="col-12">
        <label for="yourStudentNo" class="form-label">Student Number</label>
        <div class="input-group has-validation">
            <span class="input-group-text" id="inputGroupPrepend">#</span>
            <input type="text" name="student_no" class="form-control" id="yourStudentNo" required maxlength="8" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            <div class="invalid-feedback">Please enter your student number (maximum 8 characters).</div>
        </div>
    </div>

    <div class="col-12">
        <label for="yourPassword" class="form-label">Password</label>
        <div class="input-group">
        <input type="password" name="password" class="form-control" id="yourPassword" required
               pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}"
               title="Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character." autocomplete="off">
               <button type="button" class="input-group-text" onclick="togglePasswordVisibility()">
               <i class="bi bi-eye-fill"></i>
            </button>
            </div>
               <div class="invalid-feedback">
            It must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.
        </div>
    </div>
 
   <script> // show password
function togglePasswordVisibility() {
    var passwordField = document.getElementById("yourPassword");
    var toggleEyeIcon = document.getElementById("toggleEye"); 
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleEyeIcon.classList.remove('bi-eye-fill');
        toggleEyeIcon.classList.add('bi-eye-slash-fill');
    } else {
        passwordField.type = "password";
        toggleEyeIcon.classList.remove('bi-eye-slash-fill');
        toggleEyeIcon.classList.add('bi-eye-fill');
    }
}
</script>

    <div class="col-12">
        <input type="hidden" name="user_type" value="alumni">
    </div>

    <div class="col-12">
    <div class="form-check">
        <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
        <label class="form-check-label" for="acceptTerms">
            I agree and accept the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a>
        </label>
        <div class="invalid-feedback">You must agree before submitting.</div>
    </div>
</div>

<!-- Modal for Terms and Conditions -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Welcome to Bestlink College of the Philippines. These terms and conditions outline the rules and regulations 
                    for the use of our services. By accessing this website, we assume you accept these terms and conditions 
                    in full. Do not continue to use Alumni Management System if you do not accept all of the terms and conditions 
                    stated on this page.
                </p>
                <h6>1. Data Collection</h6>
                <p>
                    We collect personal information to provide better services. By using our services, you agree to the 
                    collection and use of information in accordance with this policy.
                </p>
                <h6>2. User Obligations</h6>
                <p>
                    Users agree not to misuse the service and must comply with all applicable laws.
                </p>
                <h6>3. Privacy Policy</h6>
                <p>
                    Please refer to our <a href="#">Privacy Policy</a> for more information on how we handle your data.
                </p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    <div class="col-12">
        <button class="btn btn-primary w-100" type="submit" name="submit">Create Account</button>
    </div>
    <div class="col-12">
        <p class="small mb-0">Already have an account? <a href="index.php">Log in</a></p>
    </div>
</form>

                </div>
              </div>

              <div class="credits">
               
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>