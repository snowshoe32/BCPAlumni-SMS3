<?php
include "db_conn.php"; 

if (isset($_POST['submit'])) {
    
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hashing the password
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);

    // Check if user already exists
    $check_email = "SELECT * FROM `bcp-sms3_user` WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'This Email is already registered!';
    } else {
        
        $sql = "INSERT INTO `bcp-sms3_user` (`name`, `email`, `username`, `password`, `user_type`) 
                VALUES ('$name', '$email', '$username', '$password', '$user_type')";

        
        if (mysqli_query($conn, $sql)) {
            
            header('Location: login_form.php');
            exit();
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
                  <span class="d-none d-lg-block">Your System Admin/Staff Register</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">
                  

                
                  
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                    <p class="text-center small">Enter your personal details to create account</p>
                  </div>

                  <form class="row g-3" method="POST" action="pages-register.php">
    <div class="col-12">
        <label for="yourName" class="form-label">Your Name</label>
        <input type="text" name="name" class="form-control" id="yourName" required>
        <div class="invalid-feedback">Please, enter your name!</div>
    </div>

    <div class="col-12">
        <label for="yourEmail" class="form-label">Your Email</label>
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
        <select name="user_type" class="form-control" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <div class="col-12">
        <div class="form-check">
            <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
            <label class="form-check-label" for="acceptTerms">I agree and accept the <a href="#">terms and conditions</a></label>
            <div class="invalid-feedback">You must agree before submitting.</div>
        </div>
    </div>
    <div class="col-12">
        <button class="btn btn-primary w-100" type="submit" name="submit">Create Account</button>
    </div>
    <div class="col-12">
        <p class="small mb-0">Already have an account? <a href="login_form.php">Log in</a></p>
    </div>
</form>

                </div>
              </div>

              <div class="credits">
                BCP
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