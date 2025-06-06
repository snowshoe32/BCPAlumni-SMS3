<?php
session_start();
include "db_conn.php";
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hashing the password
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
    $student_no = ($user_type == 'alumni' && !empty($_POST['student_no'])) 
        ? $_POST['student_no'] // No escaping needed here
        : NULL; // Use NULL explicitly

    // Check if email already exists
    $check_email = "SELECT * FROM `bcp_sms3_user` WHERE email = '$email'";
    $result_email = mysqli_query($conn, $check_email);

    // Check if username already exists (CASE-INSENSITIVE)
    $check_username = "SELECT * FROM `bcp_sms3_user` WHERE LOWER(username) = LOWER('$username')";
    $result_username = mysqli_query($conn, $check_username);

    if (mysqli_num_rows($result_email) > 0) {
        $error[] = 'This Email is already registered!';
    } elseif (mysqli_num_rows($result_username) > 0) {
        $error[] = 'This Username is already taken!';
    } else {
        $token = bin2hex(random_bytes(50)); // Generate a unique token
        $token2 = bin2hex(random_bytes(50)); // Generate a unique token2
    $stmt = $conn->prepare("INSERT INTO `bcp_sms3_user` (`name`, `email`, `student_no`, `username`, `password`, `user_type`, `token`, `token2`, `is_verified`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssssssss", $name, $email, $student_no, $username, $password, $user_type, $token, $token2, $is_verified);
        $is_verified = 0; // Initialize is_verified here
        if ($stmt->execute()) {
            // ... rest of your email sending code ...
        } else {
            $error[] = 'Failed to register the user! MySQL Error: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $error[] = 'Failed to prepare statement: ' . $conn->error;
    }



        if ($stmt) {
            $mail = new PHPMailer(true);
            try {
                //Server settings (your SMTP settings here)
                // ... (Your existing email configuration) ...

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
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

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
                  
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
  <li class="breadcrumb-item">Admin</li>
  <li class="breadcrumb-item active">Register</li>
</ol>

                
                  
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Create a new Account</h5>
                    <p class="text-center small">Enter your personal details to create account</p>
                  </div>

                  <form class="row g-3" method="POST" action="admin_register.php" autocomplete="off">
    <div class="col-12">
        <label for="yourName" class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" id="yourName" required>
        <div class="invalid-feedback">Please, enter your name!</div>
    </div>

    <div class="col-12">
        <label for="yourEmail" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" id="yourEmail" required>
        <div class="invalid-feedback">Please enter a valid Email address!</div>
    </div>

    <div class="col-12">
        <label for="yourUsername" class="form-label">Username</label>
        <div class="input-group has-validation">
            <span class="input-group-text" id="inputGroupPrepend">@</span>
            <input type="text" name="username" class="form-control" id="yourUsername" required>
            <div class="invalid-feedback">Please choose a username.</div>
        </div>
    </div>

    <div class="col-12">
        <label for="yourPassword" class="form-label">Password</label>
        <div class="input-group">
        <input type="password" name="password" class="form-control" id="yourPassword" required
               pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}"
               title="Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.">
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
        <label for="userType" class="form-label">User Type</label>
        <select name="user_type" class="form-control" id="userType" required onchange="toggleStudentNoField()">
            <option value="staff">Staff</option>
            <option value="admin">Admin</option>
            <option value="alumni">Alumni</option>
            <option value="super_admin">Super Admin</option>
        </select>
    </div>
    <div class="col-12" id="studentNoField" style="display: none;">
        <label for="studentNo" class="form-label">Student Number</label>
        <input type="text" name="student_no" class="form-control" id="studentNo" autocomplete="off">
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

  <script>
    function toggleStudentNoField() {
      var userType = document.getElementById("userType").value;
      var studentNoField = document.getElementById("studentNoField");
      if (userType === "alumni") {
        studentNoField.style.display = "block";
      } else {
        studentNoField.style.display = "none";
      }
    }
  </script>

<!-- Remove Sidebar Section -->
<!-- 
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <!-- Removed LC -->
    <!-- ...existing code... -->
  </ul>
</aside>
-->

</body>
</html>
