<?php

include "db_conn.php"; 
session_start();

if (isset($_POST['submit'])) {
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];  // Input password (not hashed)

    // Check if user exists based on the username
    $check_user = "SELECT * FROM `bcp_sms3_user` WHERE username = '$username'";
    $result = mysqli_query($conn, $check_user);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        // Verifying hashed password with input password
        if (password_verify($password, $row['password'])) {
            // Set session based on user type
            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                header('location:admin_dashboard.php');  
            } else if ($row['user_type'] == 'user') {
                $_SESSION['user_name'] = $row['name'];
                header('location:user_dashboard.php');  
            }
        } else {
            // Incorrect password
            $error[] = 'Incorrect password!';
        }
    } else {
        // Username does not exist
        $error[] = 'No user found with this username!';
    }
}

// Display error messages, if any
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
                  <span class="d-none d-lg-block">Bestlink Alumni Association</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                  <h1 class="card-title text-center pb-0 fs-4">Forgot your Password?</h1>
                  </div>

                  <form class="row g-3 " method="POST" action="login_form.php">
                    <?php
                    if(isset($error)) { 
                      foreach($error as $error){
                        echo '<span class=error-msg">' . $error .'</span>';
                      };
                    }
                    
                    ?>

<form action="forgot_pass2.php" method="post">
  <label for="email" class="card-title">Enter your email address:</label>
  <input type="email" id="email" name="email" class="form-control"required>
  <button type="submit" class="btn btn-primary">Send Reset Link</button>
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