<?php
session_start();
include "db_conn.php";

if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
  header('Location: index.php');
  exit();
}

$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : $_SESSION['super_admin_name'];

if (isset($_POST['submit'])) {
    // Sanitize user inputs
    $student_no = mysqli_real_escape_string($conn, $_POST['student_no']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']); 
    $fname = mysqli_real_escape_string($conn, $_POST['fname']); 
    $mname = mysqli_real_escape_string($conn, $_POST['mname']); 
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
    $yearGraduated = mysqli_real_escape_string($conn, $_POST['yearGraduated']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // SQL statement
    $sql = "INSERT INTO `bcp_sms3_alumnidata1` (`student_no`, `lname`, `fname`, `mname`, `Address`, `contact`, `course`, `birthday`, `yearGraduated`, `email`)
            VALUES ('$student_no', '$lname', '$fname', '$mname', '$address', '$contact', '$course', '$birthdate', '$yearGraduated', '$email')";

    // Execute the query
    $result = mysqli_query($conn, $sql);
    if ($result) {  
        header("Location: student-data.php?msg=New record created successfully");
        exit(); 
    } else {
        echo "Failed: " . mysqli_error($conn); // Display error message
    } 
}
if (isset($result) && $result) {
  if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_array($result);
      
  } else {
      echo "No admin found with the username: " . htmlspecialchars($admin_name);
  }
} else {
  echo "MySQL Error: " . mysqli_error($conn);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name ="viewport" meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Dashboard - Title</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="https://elc-public-images.s3.ap-southeast-1.amazonaws.com/bcp-olp-logo-mini2.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

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

  <!-- ======= Header ======= -->
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
    
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : $_SESSION['super_admin_name'] ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : $_SESSION['super_admin_name'] ?></h6>
              <span></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

       
        

            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout_form.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
 
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">




        <!-- Removed LC -->
    </div>
    <div style="display: flex; flex-direction: column; align-items: center; margin-top: 24px; text-align: center;">
      <div style="font-weight: 500; color: #fff;">
        <!-- Removed echo name -->
      </div>
      <div class="flex items-center justify-center" style="display: flex; align-items: center; justify-content: center; margin-top: 40px;">
        <img src="assets/img/bestlinkalumnilogo1.png" alt="Bestlink Alumni Logo" style="width:130px;height: auto;">
      </div>
    </div>
    <div style="margin-top: 4px; font-size: 14px; color: #fff;">
      <h6> <span> <!-- Removed echo name --></span></h6>
    </div>
  </div>
</div>

<hr class="sidebar-divider">

  <li class="nav-item">
    <a class="nav-link " href="admin_dashboard.php" class="active">
      <i class="bi bi-grid"></i>
      <span>Dashboard</span>
    </a>
  </li><!-- End Dashboard Nav -->

  <hr class="sidebar-divider">

  <li class="nav-heading"></li>

  <li class="nav-item">
<a class="nav-link collapsed" data-bs-target="#alumnidata-nav" data-bs-toggle="collapse" href="#">
<i class="bi bi-layout-text-window-reverse"></i><span>Alumni Data</span><i class="bi bi-chevron-down ms-auto"></i>
</a>
<ul id="alumnidata-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
<li>
  <a href="student-data.php">
    <i class="bi bi-circle"></i><span>Alumni Data</span>
  </a>
</li> 
<li>
  <a href="add.php">
    <i class="bi bi-circle"></i><span>Add Alumni Data</span>
  </a>
</li>
</ul>
</li><!-- End System Nav -->
  <hr class="sidebar-divider">

<li class="nav-item">
<a class="nav-link collapsed" data-bs-target="#careers-nav" data-bs-toggle="collapse" href="#">
<i class="bi bi-layout-text-window-reverse"></i><span>Career Opportunities</span><i class="bi bi-chevron-down ms-auto"></i>
</a>
<ul id="careers-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
<li>
  <a href="job-post-manage.php">
    <i class="bi bi-circle"></i><span>Job Posting</span>
  </a>
</li>
<li>
  <a href="job-post-add.php">
    <i class="bi bi-circle"></i><span>Add Job Posting</span>
  </a>
</li>
</ul>
<!-- Career Opportunities -->

<hr class="sidebar-divider">

<li class="nav-item">
  <a class="nav-link collapsed" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
    <i class="bi bi-layout-text-window-reverse"></i><span>Alumni Online Services</span><i class="bi bi-chevron-down ms-auto"></i>
  </a>
  <ul id="students-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
    <li>
      <a href="id_manage.php">
        <i class="bi bi-circle"></i><span>ID Applications</span>
      </a>
    </li>
    <li>
      <a href="admin_tracer.php">
        <i class="bi bi-circle"></i><span>Alumni Tracer</span>
      </a>
    </li>
    <li>
      <a href="admin_managenews.php">
        <i class="bi bi-circle"></i><span>News & Announcements</span>
      </a>
    </li>
    <li>
    <a href="alumni_benefits.php">
    <i class="bi bi-circle"></i><span>Alumni Benefits</span>
    </li>
  </ul>
</li>
<!--Alumni Online Services-->

  <hr class="sidebar-divider">


<li class="nav-item">
    <a class="nav-link " href="accesscontrol.php" class="active">
      <i class="bi bi-shield-lock"></i>
      <span>Access Control</span>
    </a>
  </li><!-- End Dashboard Nav -->

  <hr class="sidebar-divider">

  <li class="nav-item">
    <a class="nav-link " href="auditlogs.php" class="active">
      <i class="bi bi-file-earmark-text"></i>
      <span>Audit Logs</span>
    </a>
  </li><!-- End Dashboard Nav -->

  <hr class="sidebar-divider">
 

<!-- Remove Profile and Contact links -->
<!-- End Profile Page Nav -->
<!-- End Contact Page Nav -->

</aside><!-- End Sidebar-->
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Add Data</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Alumni Data</li>
          <li class="breadcrumb-item active">Add Alumni Data</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Add New Alumni</h5>

        <!-- Multi Columns Form -->
        
        <form class="row g-3" method="post">
    <div class="col-md-10">
        <label class="form-label">Last Name</label>
        <input type="text" class="form-control" name="lname" placeholder="" required>
    </div>
    <div class="col-md-10">
        <label class="form-label">First Name</label>
        <input type="text" class="form-control" name="fname" placeholder="" required>
    </div>
    <div class="col-md-10">
        <label class="form-label">Middle Name</label>
        <input type="text" class="form-control" name="mname" placeholder="">
    </div>
    <div class="col-md-10">
        <label class="form-label">Address</label>
        <input type="text" class="form-control" name="address" placeholder="" required>
    </div>
    <div class="col-md-4">
        <label for="inputStudent" class="form-label">Student Number</label>
        <input type="text" class="form-control" pattern="\d{8}" name="student_no" maxlength="8" 
               oninput="this.value=this.value.replace(/[^0-9]/g,'')" required 
               title="Student number must be exactly 8 digits.">
    </div>
    <div class="col-md-6">
        <label for="inputEmail" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" placeholder="bcp123@gmail.com">
    </div>
    <div class="col-md-4">
        <label for="inputContact" class="form-label">Contact Number</label>
        <input type="text" class="form-control" pattern="\d{11}" name="contact" maxlength="11" 
               oninput="this.value=this.value.replace(/[^0-9]/g,'')" required 
               title="Contact number must be exactly 11 digits.">
    </div>
    <div class="col-md-3">
        <label for="inputCourse" class="form-label">Course</label>
        <select class="form-control" name="course" required>
            <option value="" selected disabled hidden>Choose...</option>
            <option value="BLIS">BLIS</option>
            <option value="BSAIS">BSAIS</option>
            <option value="BSCPE">BSCPE</option>
            <option value="BSCRIM">BSCRIM</option>
            <option value="BSENTREP">BSENTREP</option>
            <option value="BSHM">BSHM</option>
            <option value="BSIT">BSIT</option>
            <option value="BSOA">BSOA</option>
            <option value="BSP">BSP</option>
            <option value="BSTM">BSTM</option>
            <option value="BSBA-HRM">BSBA-HRM</option>
            <option value="BSBA-MM">BSBA-MM</option>
            <option value="CTE-BSED English">CTE-BSED English</option>
            <option value="CTE-BSED Filipino">CTE-BSED Filipino</option>
            <option value="CTE-BSED Math">CTE-BSED Math</option>
            <option value="CTE-BSED Science">CTE-BSED Science</option>
            <option value="CTE-BSED Social Studies">CTE-BSED Social Studies</option>
            <option value="CTE-BSED Values">CTE-BSED Values</option>
            <option value="CTE-BTLED">CTE-BTLED</option>
            <option value="CTE-BPED">CTE-BPED</option>
            <option value="CTE-BEED">CTE-BEED</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="inputDate" class="form-label">Date of Birth</label>
        <input type="date" class="form-control" name="birthdate" required>
    </div>
    <div class="col-md-3">
                <label for="inputYearGraduated" class="form-label">Year Graduated</label>
                <select class="form-control" name="yearGraduated" required>
                    <option value="" selected disabled hidden>Choose...</option>
                    <?php
                    for ($year = 2002; $year <= 2025; $year++) {
                        echo '<option value="' . $year . '"' . ($row['yearGraduated'] == $year ? ' selected' : '') . '>' . $year . '</option>';
                    }
                    ?>
                </select>
            </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
    </div>
</form>

      </div>
    </div>

  </div>









  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Bestlink College of the Philippines</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      BCP
    </div>
  </footer><!-- End Footer -->

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
