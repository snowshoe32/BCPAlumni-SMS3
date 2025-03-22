<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}
include "db_conn.php";

$admin_name = $_SESSION['admin_name'];


if (isset($_POST['submit'])) {
    // Sanitize user inputs
    $jobtitle = mysqli_real_escape_string($conn, $_POST['jobtitle']);
    $location = mysqli_real_escape_string($conn, $_POST['location']); 
    $description = mysqli_real_escape_string($conn, $_POST['description']); 
    $qualification = mysqli_real_escape_string($conn, $_POST['qualification']); 
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $source = mysqli_real_escape_string($conn, $_POST['source']);
    $employer = mysqli_real_escape_string($conn, $_POST['employer']);
    
    // SQL statement
    $sql = "INSERT INTO `bcp-sms3_job` (`jobtitle`, `location`, `description`, `qualification`, `image`, `email`, `salary`, `date`, `source`, `employer`)
            VALUES ('$jobtitle', '$location', '$description', '$qualification', '$image', '$email', '$salary', '$date', '$source', '$employer')";

    // Execute the query
    $result = mysqli_query($conn, $sql);
    if ($result) {  
        header("Location: job-post-manage.php?msg=New record created successfully");
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
    <i class="bi bi-layout-text-window-reverse"></i><span>Student Alumni Services</span><i class="bi bi-chevron-down ms-auto"></i>
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
  
  </ul>
</li>


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
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Pages</li>
          <li class="breadcrumb-item active">Add data</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Add New Alumni</h5>

        <!-- Multi Columns Form -->
        
        <form class="row g-3" method="post">
        <div class="col-md-10">
<label class="form-label">Job Title </label>
<input type="text" class="form-control" name="jobtitle" placeholder="" required>
</div>
<div class="row g-3">
                  <label for="inputPassword" class=" col-form-label">Job Description</label>
                  <div class="col-md-10">
                    <textarea class="form-control" style="height: 150px" name="description"></textarea>
                  </div>
                </div>
                <div class="row g-3">
                  <label for="inputPassword" class=" col-form-label">Job Qualifications</label>
                  <div class="col-md-10">
                    <textarea class="form-control" style="height: 150px" name="qualification"></textarea>
                  </div>
                </div>
<form class="row g-3" method="post">
        <div class="col-md-10">
<label class="form-label">Location </label>
<input type="text" class="form-control" name="location" placeholder="" required>
</div>
<form class="row g-3" method="post">
        <div class="col-md-10">
<label class="form-label">Send your resume to: </label>
<input type="text" class="form-control" name="email" placeholder="" required>
</div>
              <div class="row g-3">
                  <label for="inputNumber" class="col-form-label">Job Image</label>
                  <div class="col-md-10">
                    <input class="form-control" type="file" id="formFile" name="image">
                  </div>
                </div>
                <div class="col-md-10">
        <label class="form-label">Date </label>
        <input type="date" class="form-control" name="date" required>
    </div>
    <div class="col-md-10">
        <label class="form-label">Source </label>
        <input type="text" class="form-control" name="source" placeholder="" required>
    </div>
    <div class="col-md-10">
        <label class="form-label">Employer </label>
        <input type="text" class="form-control" name="employer" placeholder="" required>
    </div>

        
         
          <div class="col-md-4">
          <label for="inputContact" class="form-label">Salary</label>
           <input type="text" class="form-control" name="salary" 
         oninput="this.value=this.value.replace(/[^0-9\-\,]/g,'')" required 
         title="Salary.">
          <div class="invalid-feedback">
       </div>
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
