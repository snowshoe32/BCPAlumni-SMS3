<?php
session_start();
include 'db_conn.php';

// Check if user is logged in and is an alumni
if (isset($_SESSION['alumni_name'])) {
    $alumni_name = $_SESSION['alumni_name'];
} else {
    // Redirect to login page if not logged in as alumni
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM bcp_sms3_user WHERE username = 'Guest'";
$result = mysqli_query($conn, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        // Other logic for the admin dashboard
    } else {
        echo "No admin found with the username: Guest";
    }
} else {
    echo "MySQL Error: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

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
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($alumni_name); ?></span>
          </a><!-- End Profile Image Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo htmlspecialchars($alumni_name); ?></h6>
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

      </div>
      <div class="flex items-center justify-center" style="display: flex; align-items: center; justify-content: center; margin-top: 40px;">
        <img src="assets/img/bestlinkalumnilogo1.png" alt="Bestlink Alumni Logo" style="width:130px;height: auto;">
       
      </div>

    
    
    </div>
</div>
            <div style="margin-top: 4px; font-size: 14px; color: #fff;">
           
            </div>
        </div>
    </div>

    <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link " href="public_dashboard.php" class="active">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <hr class="sidebar-divider">

      <li class="nav-heading"></li>

    
      <li class="nav-item">
        <a class="nav-link " href="announcements.php" class="active">
          <i class="bi bi-grid"></i>
          <span>Announcements</span>
        </a>
      </li><!-- Announcements Nav -->
</li><!-- End System Nav -->

      <hr class="sidebar-divider">


      <li class="nav-item">
        <a class="nav-link " href="career.php" class="active">
          <i class="bi bi-grid"></i>
          <span>Career Opportunities</span>
        </a>
      </li><!-- Announcements Nav -->

<hr class="sidebar-divider">

<li class="nav-item">
  <a class="nav-link collapsed" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
    <i class="bi bi-layout-text-window-reverse"></i><span>Student Alumni Services</span><i class="bi bi-chevron-down ms-auto"></i>
  </a>
  <ul id="students-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
    <li>
      <a href="id_manage.php">
        <i class="bi bi-circle"></i><span>View Alumni ID Applications</span>
      </a>
    </li>
    <li>
      <a href="admin_managenews.php">
        <i class="bi bi-circle"></i><span>Alumni Tracer</span>
      </a>
    </li>
    <li>
      <a href="admin_managenews.php">
        <i class="bi bi-circle"></i><span>Apply for Alumni ID</span>
      </a>
    </li>
  </ul>
</li>
<!--Student Alumni Services-->

<!-- Remove Profile and Contact links -->
<!-- End Profile Page Nav -->
<!-- End Contact Page Nav -->

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
<!-- Career Opportunities -->

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
<!--Student Alumni Services-->

<!-- Remove Profile and Contact links -->
<!-- End Profile Page Nav -->
<!-- End Contact Page Nav -->
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->


    <section class="section dashboard">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Alumni ID Application</h5>
          <form action="submit_application.php" method="post">
            <div class="mb-3">
              <label for="last_name" class="form-label">Last Name</label>
              <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="mb-3">
              <label for="first_name" class="form-label">First Name</label>
              <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="mb-3">
              <label for="middle_name" class="form-label">Middle Name</label>
              <input type="text" class="form-control" id="middle_name" name="middle_name">
            </div>
            <div class="mb-3">
              <label for="student_number" class="form-label">Student Number</label>
              <input type="text" class="form-control" id="student_number" name="student_number" maxlength="11" pattern="\d{11}" required>
            </div>
            <div class="mb-3">
              <label for="contact_number" class="form-label">Contact Number</label>
              <input type="text" class="form-control" id="contact_number" name="contact_number" maxlength="11" pattern="\d{11}" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="birthdate" class="form-label">Birthdate</label>
              <input type="date" class="form-control" id="birthdate" name="birthdate" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; <strong><span>Bestlink Alumni Association 2025</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
    </div>
  </footer><!-- End Footer -->
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
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
