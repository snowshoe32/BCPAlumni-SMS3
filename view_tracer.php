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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT `id`, `lastName`, `firstName`, `middleName`, `yearGraduated`, `courseProgram`, `courseProgramOther`, `studentNo`, `birthDate`, `age`, `gender`, `homeAddress`, `telephoneNumber`, `mobileNumber`, `email`, `currentJobPosition`, `companyAddress`, `placeOfWork`, `department`, `employmentRecord`, `monthlySalary`, `promoted`, `created_at` FROM `bcp-sms3_tracer` WHERE `id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No data found.";
    exit();
}

$row = $result->fetch_assoc();

$stmt->close();
$conn->close();
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

      <div class="flex items-center w-full p-1 pl-6" style="display: flex; align-items: center; padding: 3px; width: 40px; background-color: transparent; height: 4rem;">
        <div class="flex items-center justify-center" style="display: flex; align-items: center; justify-content: center;">
            <img src="https://elc-public-images.s3.ap-southeast-1.amazonaws.com/bcp-olp-logo-mini2.png" alt="Logo" style="width: 30px; height: auto;">
        </div>
      </div>

      <div style="display: flex; flex-direction: column; align-items: center; padding: 16px;">
        <div style="display: flex; align-items: center; justify-content: center; width: 96px; height: 96px; border-radius: 50%; background-color: #334155; color: #e2e8f0; font-size: 48px; font-weight: bold; text-transform: uppercase; line-height: 1;">
            LC
        </div>
        <div style="display: flex; flex-direction: column; align-items: center; margin-top: 24px; text-align: center;">
            <div style="font-weight: 500; color: #fff;">
            <?php echo $_SESSION['admin_name'] ?>
            </div>
            <div style="margin-top: 4px; font-size: 14px; color: #fff;">
                ID
            </div>
        </div>
    </div>

    <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link " href="admin_dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <hr class="sidebar-divider">

      <li class="nav-item">
  <a class="nav-link collapsed" data-bs-target="#system-nav" data-bs-toggle="collapse" href="#">
    <i class="bi bi-layout-text-window-reverse"></i><span>Alumni Data</span><i class="bi bi-chevron-down ms-auto"></i>
  </a>
  <ul id="system-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
    <li>
      <a href="student-data.php" class="active">
        <i class="bi bi-circle"></i><span>Manage Alumni Data</span>
      </a>
    </li> 
    <li>
      <a href="add.php"></a>
        <i class="bi bi-circle"></i><span>Add new Alumni</span>
      </a>
    </li>
  </ul>
</li><!-- End System Nav -->

<hr class="sidebar-divider">

       <!-- Events Management Nav -->
<li class="nav-item">
  <a class="nav-link collapsed" data-bs-target="#events-nav" data-bs-toggle="collapse" href="#">
    <i class="bi bi-layout-text-window-reverse"></i><span>Alumni Events</span><i class="bi bi-chevron-down ms-auto"></i>
  </a>
  <ul id="events-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
    <li>
      <a href="add_events.php">
        <i class="bi bi-circle"></i><span>Add Events</span>
      </a>
    </li>
    <li>
      <a href="upcoming_events.php">
        <i class="bi bi-circle"></i><span>Manage Events</span>
      </a>
    </li>
    <li>
    </li>
  </ul>
</li><!-- End Events Management Nav -->
      
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
      <a href="job-post-add.php"></a>
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
<!--Student Alumni Services-->

      <hr class="sidebar-divider">
      
      <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-contact.html">
          <i class="bi bi-envelope"></i>
          <span>Contact</span>
        </a>
      </li><!-- End Contact Page Nav -->

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
        <form class="row g-3" method="post" action="update_alumni_tracer.php">
          <div class="col-md-10">
            <label class="form-label">Last Name</label>
            <input type="text" class="form-control" name="lastName" value="<?php echo htmlspecialchars($row['lastName']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">First Name</label>
            <input type="text" class="form-control" name="firstName" value="<?php echo htmlspecialchars($row['firstName']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Middle Name</label>
            <input type="text" class="form-control" name="middleName" value="<?php echo htmlspecialchars($row['middleName']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Year Graduated</label>
            <input type="text" class="form-control" name="yearGraduated" value="<?php echo htmlspecialchars($row['yearGraduated']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Course/Program</label>
            <input type="text" class="form-control" name="courseProgram" value="<?php echo htmlspecialchars($row['courseProgram']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Student No</label>
            <input type="text" class="form-control" name="studentNo" value="<?php echo htmlspecialchars($row['studentNo']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Birth Date</label>
            <input type="date" class="form-control" name="birthDate" value="<?php echo htmlspecialchars($row['birthDate']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Age</label>
            <input type="text" class="form-control" name="age" value="<?php echo htmlspecialchars($row['age']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Gender</label>
            <input type="text" class="form-control" name="gender" value="<?php echo htmlspecialchars($row['gender']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Home Address</label>
            <input type="text" class="form-control" name="homeAddress" value="<?php echo htmlspecialchars($row['homeAddress']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Telephone Number</label>
            <input type="text" class="form-control" name="telephoneNumber" value="<?php echo htmlspecialchars($row['telephoneNumber']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Mobile Number</label>
            <input type="text" class="form-control" name="mobileNumber" value="<?php echo htmlspecialchars($row['mobileNumber']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($row['email']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Current Job Position</label>
            <input type="text" class="form-control" name="currentJobPosition" value="<?php echo htmlspecialchars($row['currentJobPosition']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Company Address</label>
            <input type="text" class="form-control" name="companyAddress" value="<?php echo htmlspecialchars($row['companyAddress']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Place of Work</label>
            <input type="text" class="form-control" name="placeOfWork" value="<?php echo htmlspecialchars($row['placeOfWork']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Department</label>
            <input type="text" class="form-control" name="department" value="<?php echo htmlspecialchars($row['department']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Employment Record</label>
            <textarea class="form-control" name="employmentRecord" required disabled><?php echo htmlspecialchars($row['employmentRecord']) ?></textarea>
          </div>
          <div class="col-md-10">
            <label class="form-label">Monthly Salary</label>
            <input type="text" class="form-control" name="monthlySalary" value="<?php echo htmlspecialchars($row['monthlySalary']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Promoted</label>
            <input type="text" class="form-control" name="promoted" value="<?php echo htmlspecialchars($row['promoted']) ?>" required disabled>
          </div>
          <div class="col-md-10">
            <label class="form-label">Created At</label>
            <input type="text" class="form-control" name="created_at" value="<?php echo htmlspecialchars($row['created_at']) ?>" disabled>
          </div>
         
          <div class="text-center">
            <a href="admin_tracer.php" class="btn btn-primary">Back</a>
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
