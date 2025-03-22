<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}
include "db_conn.php";
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : $_SESSION['super_admin_name'];

$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id)) {
    echo "Invalid ID.";
    exit();
}

// Fetch data from the API using cURL
$api_url = 'https://sis.bcpsms3.com/api/alumni';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$alumni_data = json_decode($response, true);

if ($alumni_data === null || !isset($alumni_data['data'][0])) {
    echo "Error fetching data from API.";
    exit();
}

$row = $alumni_data['data'][0];
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
      <h1>View Alumni Data</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Alumni Data</li>
          <li class="breadcrumb-item active">View data</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">View Alumni Data</h5>

        <!-- Multi Columns Form -->
        <form class="row g-3" method="post">
          <div class="col-md-10">
            <label class="form-label">ID</label>
            <input type="text" class="form-control" name="id" value="<?php echo htmlspecialchars($row['id']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($row['first_name']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Middle Name</label>
            <input type="text" class="form-control" name="middle_name" value="<?php echo htmlspecialchars($row['middle_name']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($row['last_name']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Suffix Name</label>
            <input type="text" class="form-control" name="suffix_name" value="<?php echo htmlspecialchars($row['suffix_name']) ?>" disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Age</label>
            <input type="text" class="form-control" name="age" value="<?php echo htmlspecialchars($row['age']) ?>" disabled>
          </div>
          <div class="col-md-3">
            <label for="inputState" class="form-label">Gender</label>
            <select id="gender" class="form-select" name="gender" disabled>
              <option value="">Choose...</option>
              <option value="M" <?php echo ($row['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
              <option value="F" <?php echo ($row['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="inputDate" class="col-sm-4 col-form-label">Date of Birth</label>
            <div class="col-sm-10">
              <input type="date" class="form-control" name="birthdate" id="inputDate" required value="<?php echo htmlspecialchars($row['birthdate']) ?>" disabled>
            </div>
          </div>
          <div class="col-md-10">
            <label class="form-label">Religion</label>
            <input type="text" class="form-control" name="religion" value="<?php echo htmlspecialchars($row['religion']) ?>" disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Place of Birth</label>
            <input type="text" class="form-control" name="place_of_birth" value="<?php echo htmlspecialchars($row['place_of_birth']) ?>" disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Current Address</label>
            <input type="text" class="form-control" name="current_address" value="<?php echo htmlspecialchars($row['current_address']) ?>" disabled>
          </div>
          <div class="col-md-6">
            <label for="inputEmail" class="form-label">Email</label>
            <input type="email" class="form-control" name="email_address" value="<?php echo htmlspecialchars($row['email_address']) ?>" disabled>
          </div>
          <div class="col-md-4">
            <label for="inputContact" class="form-label">Contact Number</label>
            <input type="text" class="form-control" pattern="\d{11}" name="contact_number" maxlength="11" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required disabled value="<?php echo htmlspecialchars($row['contact_number']) ?>" title="Contact number must be exactly 11 digits.">
            <div class="invalid-feedback">
              Please enter exactly 11 numeric digits for the contact number.
            </div>
          </div>
          <div class="col-md-10">
            <label class="form-label">Enrollment Date</label>
            <input type="date" class="form-control" name="enrollment_date" value="<?php echo htmlspecialchars($row['enrollment_date']) ?>" disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Program ID</label>
            <input type="text" class="form-control" name="program_id" value="<?php echo htmlspecialchars($row['program_id']) ?>" disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Desired Major</label>
            <input type="text" class="form-control" name="desired_major" value="<?php echo htmlspecialchars($row['desired_major']) ?>" disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Enrollment Status</label>
            <input type="text" class="form-control" name="enrollment_status" value="<?php echo htmlspecialchars($row['enrollment_status']) ?>" disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Created At</label>
            <input type="text" class="form-control" name="created_at" value="<?php echo htmlspecialchars($row['created_at']) ?>" disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Updated At</label>
            <input type="text" class="form-control" name="updated_at" value="<?php echo htmlspecialchars($row['updated_at']) ?>" disabled>
          </div>
          <div class="text-center">
            <a href="student-data.php" class="btn btn-primary">Back</a>
          </div>
        </form><!-- End Multi Columns Form -->
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
