<?php
// No need to check for admin login here - this page is for non-logged-in users
session_start(); // Start session to potentially store feedback messages
include "db_conn.php";
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = ''; // Variable to hold feedback messages

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // 1. Check if the email exists in the main user table
    $check_user_sql = "SELECT id, name FROM `bcp_sms3_user` WHERE email = ?";
    $stmt_check = $conn->prepare($check_user_sql);
    if ($stmt_check) {
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user_id = $user['id']; // Get user ID if needed, though reset_password.php uses email
            $name = $user['name'];
            $token = bin2hex(random_bytes(50)); // Generate a secure random token

            // 2. Delete any existing tokens for this email in the reset table
            $delete_token_sql = "DELETE FROM `password_reset_tokens` WHERE email = ?";
            $stmt_delete = $conn->prepare($delete_token_sql);
            if ($stmt_delete) {
                $stmt_delete->bind_param("s", $email);
                $stmt_delete->execute();
                $stmt_delete->close(); // Close delete statement
            } else {
                $message = "<div class='alert alert-danger'>Error preparing token cleanup: " . htmlspecialchars($conn->error) . "</div>";
            }

            // 3. Insert the new token into the password_reset_tokens table
            // Assuming password_reset_tokens table has columns: email, token, (optional: created_at)
            $insert_token_sql = "INSERT INTO `password_reset_tokens` (email, token, created_at) VALUES (?, ?, NOW())";
            $stmt_insert = $conn->prepare($insert_token_sql);
            if ($stmt_insert) {
                $stmt_insert->bind_param("ss", $email, $token);

                if ($stmt_insert->execute()) {
                    // 4. Send the password reset email
                    $mail = new PHPMailer(true);
                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com'; // Your SMTP server
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'snowshoe0103@gmail.com'; // Your SMTP username
                        $mail->Password   = 'bvoa zaxb ugki vtwm'; // Your SMTP password (use App Password)
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;

                        // Recipients
                        $mail->setFrom('snowshoe0103@gmail.com', 'Alumni System Admin'); // Set a proper sender name
                        $mail->addAddress($email, $name); // Add recipient

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset Request';
                        // **IMPORTANT:** Update the URL to your actual domain/path
                        $reset_link = 'http://localhost/public_html/reset_password.php?token=' . $token; // Change localhost if deploying
                        $mail->Body    = "Hi " . htmlspecialchars($name) . ",<br><br>Click the link below to reset your password:<br>"
                                       . "<a href='" . $reset_link . "'>" . $reset_link . "</a><br><br>"
                                       . "If you did not request this, please ignore this email.<br><br>"
                                       . "Thank you,<br>Alumni Management System";
                        $mail->AltBody = "Hi " . htmlspecialchars($name) . ",\n\nClick the link below to reset your password:\n" . $reset_link . "\n\nIf you did not request this, please ignore this email.\n\nThank you,\nAlumni Management System";


                        $mail->send();
                        $message = "<div class='alert alert-success'>Password reset link has been sent to your email. Please check your inbox (and spam folder).</div>";

                    } catch (Exception $e) {
                        $message = "<div class='alert alert-danger'>Message could not be sent. Mailer Error: " . htmlspecialchars($mail->ErrorInfo) . "</div>";
                    }
                } else {
                    $message = "<div class='alert alert-danger'>Failed to store reset token: " . htmlspecialchars($stmt_insert->error) . "</div>";
                }
                $stmt_insert->close(); // Close insert statement
            } else {
                 $message = "<div class='alert alert-danger'>Error preparing token storage: " . htmlspecialchars($conn->error) . "</div>";
            }

        } else {
            // Email not found in the user table
            $message = "<div class='alert alert-danger'>No user found with this email address.</div>";
        }
        $stmt_check->close(); // Close check statement
    } else {
         $message = "<div class='alert alert-danger'>Error preparing user check: " . htmlspecialchars($conn->error) . "</div>";
    }

    $conn->close(); // Close database connection
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Forgot Password - Alumni Management System</title> <!-- Updated Title -->
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="https://elc-public-images.s3.ap-southeast-1.amazonaws.com/bcp-olp-logo-mini2.png" rel="icon"> <!-- Updated Favicon -->
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
                <a href="index.php" class="logo d-flex align-items-center w-auto"> <!-- Link back to index/login -->
                  <img src="https://elc-public-images.s3.ap-southeast-1.amazonaws.com/bcp-olp-logo-mini2.png" alt="Logo">
                  <span class="d-none d-lg-block">Alumni Management System</span> <!-- Updated Name -->
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h1 class="card-title text-center pb-0 fs-4">Forgot Your Password?</h1>
                    <p class="text-center small">Enter your email address below and we'll send you a link to reset your password.</p> <!-- Added instruction -->
                  </div>

                  <?php echo $message; // Display feedback message here ?>

                  <!-- Removed duplicate form tag -->
                  <form class="row g-3 needs-validation" method="POST" action="forgot_pass.php" novalidate>

                    <div class="col-12">
                      <label for="email" class="form-label">Email Address</label> <!-- Changed label -->
                      <div class="input-group has-validation">
                         <span class="input-group-text" id="inputGroupPrepend">@</span> <!-- Added icon -->
                         <input type="email" id="email" name="email" class="form-control" required>
                         <div class="invalid-feedback">Please enter your email address.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <button type="submit" name="submit" class="btn btn-primary w-100">Send Reset Link</button>
                    </div>

                     <div class="col-12">
                       <p class="small mb-0">Remember your password? <a href="index.php">Log in</a></p> <!-- Link back to login -->
                     </div>
                  </form>

                </div>
              </div>

              <div class="credits">
                Bestlink College of the Philippines
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
