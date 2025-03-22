<?php

include "db_conn.php"; 
session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $check_token = "SELECT * FROM `password_reset_tokens` WHERE token='$token'";
    $result = mysqli_query($conn, $check_token);

    if (mysqli_num_rows($result) > 0) {
        $token_data = mysqli_fetch_assoc($result);
        $email = $token_data['email'];

        if (isset($_POST['submit'])) {
            $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
            $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

            if ($new_password === $confirm_password) {
                if (strlen($new_password) >= 8 && preg_match('/[A-Z]/', $new_password) && preg_match('/[0-9]/', $new_password)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    $update_password = "UPDATE `bcp_sms3_user` SET password='$hashed_password' WHERE email='$email'";
                    if (mysqli_query($conn, $update_password)) {
                        $delete_token = "DELETE FROM `password_reset_tokens` WHERE token='$token'";
                        mysqli_query($conn, $delete_token);

                        echo "<div class='alert alert-success'>Your password has been reset successfully.</div>";
                        echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 2000);</script>";
                        header('Location: index.php');
                        exit();
                    } else {
                        echo "<div class='alert alert-danger'>Failed to reset password. Please try again.</div>";
                    }
                } else {
                    echo "<script>showModal('Password must be at least 8 characters long, contain at least one uppercase letter and one number!');</script>";
                }
            } else {
                echo "<script>showModal('Passwords do not match!');</script>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Invalid token!</div>";
    }
} else {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pages / Reset Password - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
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
                  <h1 class="card-title text-center pb-0 fs-4">Reset your Password</h1>
                  </div>

                  <form action="reset_password.php?token=<?php echo $token; ?>" method="post" onsubmit="return validatePasswords()">
                    <label for="new_password" class="card-title">Enter your new password:</label>
                    <div class="input-group">
                      <input type="password" id="new_password" name="new_password" class="form-control" required>
                      <span class="input-group-text" onclick="togglePasswordVisibility('new_password')"><i class="bi bi-eye"></i></span>
                    </div>
                    <br>
                    <label for="confirm_password" class="card-title">Confirm your new password:</label>
                    <div class="input-group">
                      <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                      <span class="input-group-text" onclick="togglePasswordVisibility('confirm_password')"><i class="bi bi-eye"></i></span>
                    </div>
                    <br>
                    <div class="d-flex justify-content-center">
                      <button type="submit" name="submit" class="btn btn-primary">Reset Password</button>
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

  <!-- Modal -->
  <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="passwordModalLabel">Error</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Passwords do not match!
        </div>
        <div class="modal-footer"></div>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

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
    function validatePasswords() {
      var newPassword = document.getElementById("new_password").value;
      var confirmPassword = document.getElementById("confirm_password").value;
      var passwordPattern = /^(?=.*[A-Z])(?=.*\d).+$/;

      if (newPassword !== confirmPassword) {
        showModal('Passwords do not match!');
        return false;
      }

      if (newPassword.length < 8 || !passwordPattern.test(newPassword)) {
        showModal('Password must be at least 8 characters long, contain at least one uppercase letter and one number!');
        return false;
      }

      return true;
    }

    function togglePasswordVisibility(fieldId) {
      var field = document.getElementById(fieldId);
      if (field.type === "password") {
        field.type = "text";
      } else {
        field.type = "password";
      }
    }

    function showModal(message) {
      var modal = new bootstrap.Modal(document.getElementById('passwordModal'));
      document.querySelector('#passwordModal .modal-body').textContent = message;
      modal.show();
    }
  </script>

</body>

</html>