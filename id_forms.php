<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}
include "db_conn.php";


if (isset($_POST['submit'])) {
    // Sanitize user inputs
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']); 
    $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']); 
    $student_no = mysqli_real_escape_string($conn, $_POST['student_no']); 
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $e_contact = mysqli_real_escape_string($conn, $_POST['e_contact']);
    $batch = mysqli_real_escape_string($conn, $_POST['batch']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $photo = mysqli_real_escape_string($conn, $_POST['photo']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // SQL statement
    $sql = "INSERT INTO `bcp-sms3-idmanage` (`first_name`, `last_name`, `middle_name`, `student_no`, `contact`, `e_contact`, `batch`, `address`, `course`, `photo`, `status`, `birthdate`, `email`) 
    VALUES ('$first_name','$last_name','$middle_name','$student_no','$contact','$e_contact','$batch','$address','$course','$photo','$status','$birthdate','$email')";

    // Execute the query
    $result = mysqli_query($conn, $sql);
    if ($result) {  
        header("Location: id_manage.php?msg=New record created successfully");
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
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="index.php">
            <i class="bx bx-user"></i>
            <span class="d-none d-md-block ps-2">Sign In</span>
          </a><!-- End Profile Iamge Icon -->
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <hr class="sidebar-divider">
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#alumnidata-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-layout-text-window-reverse"></i><span>Alumni Data</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="alumnidata-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="student-data.php">
            <i class="bi bi-circle"></i><span>Manage Alumni Data</span>
          </a>
        </li> 
        <li>
          <a href="add.php">
            <i class="bi bi-circle"></i><span>Add new Alumni</span>
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
            <i class="bi bi-circle"></i><span>Manage Job Posting</span>
          </a>
        </li>
        <li>
          <a href="job-post-add.php">
            <i class="bi bi-circle"></i><span>Add Job Posting</span>
          </a>
        </li>
      </ul>
    </li><!-- Career Opportunities -->
    <hr class="sidebar-divider">
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-layout-text-window-reverse"></i><span>Student Alumni Services</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="students-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="id_manage.php">
            <i class="bi bi-circle"></i><span>Manage Alumni ID Applications</span>
          </a>
        </li>
        <li>
          <a href="admin_tracer.php">
            <i class="bi bi-circle"></i><span>News & Announcements</span>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</aside>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Add Data</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Student Alumni Services</li>
          <li class="breadcrumb-item active">ID Forms</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Alumni ID Application</h5>

        <!-- Multi Columns Form -->
        
        <form class="row g-3" method="post">
        <div class="col-md-10">
<label class="form-label">Last Name </label>
<input type="text" class="form-control" name="last_name" placeholder="" required>
</div>
<form class="row g-3" method="post">
        <div class="col-md-10">
<label class="form-label">First Name </label>
<input type="text" class="form-control" name="first_name" placeholder="" required>
</div>

<form class="row g-3" method="post">
        <div class="col-md-10">
<label class="form-label">Middle Name </label>
<input type="text" class="form-control" name="middle_name" placeholder="">
</div>
<form class="row g-3" method="post">
        <div class="col-md-10">
<label class="form-label">Address </label>
<input type="text" class="form-control" name="address" placeholder="">
</div>



          <div class="col-md-4">
            <label for="inputStudent" class="form-label">Student Number</label>
            <input type="text" class="form-control" pattern="\d{8}" name="student_no" maxlength="8" 
         oninput="this.value=this.value.replace(/[^0-9]/g,'')" required 
         title="Student number must be exactly 8 digits.">
          <div class="invalid-feedback">
    Please enter exactly 8 numeric digits for the student number.
       </div>
          </div>

          <div class="col-md-4">
          <label for="inputContact" class="form-label">Contact Number</label>
           <input type="text" class="form-control" pattern="\d{11}" name="contact" maxlength="11" 
         oninput="this.value=this.value.replace(/[^0-9]/g,'')" required 
         title="Contact number must be exactly 11 digits.">
          <div class="invalid-feedback">
    Please enter exactly 11 numeric digits for the contact number.
       </div>
        </div>

        <div class="col-md-4">
          <label for="inputContact" class="form-label">Emergency Contact Number</label>
           <input type="text" class="form-control" pattern="\d{11}" name="e_contact" maxlength="11" 
         oninput="this.value=this.value.replace(/[^0-9]/g,'')" required 
         title="Contact number must be exactly 11 digits.">
          <div class="invalid-feedback">
    Please enter exactly 11 numeric digits for the contact number.
       </div>
        </div>
  
          <div class="col-md-5">
            <label for="inputState" class="form-label">Course</label>
            <select id="gender" class="form-select" name="course" required>
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
              <option value="BSOA ">Bachelor of Science in Office Administration</option>
              <option value="BSLIS">Bachelor of Library and Information Science</option>
            
            </select>
          </div>
      
          <div class="col-md-3">
    <label for="inputState" class="form-label">Year Graduated</label>
    <select id="batch" class="form-select" name="batch" required>
        <option value="" selected disabled hidden>Choose...</option>
        <option value="2025">2024-2025</option>
        <option value="2024">2023-2024</option>
        <option value="2023">2022-2023</option>
        <option value="2022">2021-2022</option>
        <option value="2021">2020-2021</option>
        <option value="2020">2019-2020</option>
        <option value="2019">2018-2019</option>
        <option value="2018">2017-2018</option>
        <option value="2017">2016-2017</option>
        <option value="2016">2015-2016</option>
        <option value="2015">2014-2015</option>
        <option value="2014">2013-2014</option>
        <option value="2013">2012-2013</option>
        <option value="2012">2011-2012</option>
        <option value="2011">2010-2011</option>
        <option value="2010">2009-2010</option>
        <option value="2009">2008-2009</option>
        <option value="2008">2007-2008</option>
        <option value="2007">2006-2007</option>
        <option value="2006">2005-2006</option>
        <option value="2005">2004-2005</option>
        <option value="2004">2003-2004</option>
        <option value="2003">2002-2003</option>
    </select>
</div>

<div class="col-md-3">
        <label for="userType" class="col-sm-4 col-form-label">Status</label>
        <div class="col-sm-4">
        <select name="status" class="form-control" required>
          
            <option value="Pending" selected>Pending</option>
         
            
        </select>
        </div>
    </div>

    <form method="POST" action="id_manage.php">
    <div class="col-md-3">
        <label for="inputDate" class="col-sm-4 col-form-label">Date of Birth</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" name="birthdate" id="inputDate" required>
        </div>
    </div>
    

    <div class="col-md-4">
        <label for="inputEmail" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
    </div>

            
    <form action="submit.php" method="POST">
          
          <div class="text-center">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
            
          </div>
        </form><!-- End Multi Columns Form -->

      </div>
    </div>

  </div>









  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>XXXXXX</span></strong>. All Rights Reserved
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
