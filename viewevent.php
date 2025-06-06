<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}
include 'db_conn.php';
$id = $_GET['id']; 

// Check if the user is logged in
if (!isset($_SESSION['admin_name'])) {
    header('Location: index.php');
    exit();
}

$admin_name = $_SESSION['admin_name'];

if (isset($_POST['submit'])) {
    // Sanitize user inputs
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']); 
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $organizer = mysqli_real_escape_string($conn, $_POST['organizer']);
    $organizer_no = mysqli_real_escape_string($conn, $_POST['organizer_no']);
    $organizer_email = mysqli_real_escape_string($conn, $_POST['organizer_email']);

    $eventStartDateTime = $start_date . ' ' . $start_time;
    $eventEndDateTime = $end_date . ' ' . $end_time;

    $sql = "UPDATE `bcp-sms3_events` SET `title`='$title',`location`='$location',`start_date`='$eventStartDateTime',`end_date`='$eventEndDateTime',`start_time`='$start_time', `end_time`='$end_time', `description`='$description',`status`='$status',`organizer`='$organizer',`organizer_no`='$organizer_no',`organizer_email`='$organizer_email' WHERE id=$id";

    $result = mysqli_query($conn, $sql);

    if ($result) {  
        header("Location: upcoming_events.php?msg=New record created successfully");
        exit();
    } else {
        // Improved error handling
        echo "MySQL Error: " . mysqli_error($conn);
    }
}

$apiUrl = "https://event.bcpsms3.com/api/event.php";

// Initialize cURL session
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
    exit();
}

// Close cURL session
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);

if (!$data || !is_array($data)) {
    echo "Failed to fetch data or invalid response format.";
    exit();
}

$holidays = $data['holidays'] ?? [];
$reservations = $data['reservations'] ?? [];
$events = $data['events'] ?? []; // Fetch the events data from the API response
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
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : $_SESSION['super_admin_name'] ?></span>
          </a><!-- End Profile Image Icon -->

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

      <div class="flex items-center justify-center" style="display: flex; align-items: center; justify-content: center; margin-top: 40px;">
        <img src="assets/img/bestlinkalumnilogo1.png" alt="Bestlink Alumni Logo" style="width:130px;height: auto;">
      </div>
      <!-- Removed name display -->
      
      <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link " href="admin_dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <hr class="sidebar-divider">

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
      </li><!-- End Alumni Data Nav -->

    

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
      </li><!-- End Career Opportunities Nav -->

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
        </ul>
      </li><!-- End Alumni Online Services Nav -->

      <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link " href="accesscontrol.php">
          <i class="bi bi-shield-lock"></i>
          <span>Access Control</span>
        </a>
      </li><!-- End Access Control Nav -->

      <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link " href="auditlogs.php">
          <i class="bi bi-file-earmark-text"></i>
          <span>Audit Logs</span>
        </a>
      </li><!-- End Audit Logs Nav -->

      <hr class="sidebar-divider">

    </ul>

  </aside><!-- End Sidebar -->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Upcoming Events</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Upcoming Events</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container mt-5">
        <h1 class="mb-4">Upcoming Events</h1>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['id']); ?></td>
                        <td><?php echo htmlspecialchars($event['title']); ?></td>
                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                        <td><?php echo htmlspecialchars($event['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($event['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($event['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h1 class="mb-4">Holidays</h1>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Reason</th>
                    <th>Base Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($holidays as $holiday): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($holiday['id']); ?></td>
                        <td><?php echo htmlspecialchars($holiday['date']); ?></td>
                        <td><?php echo htmlspecialchars($holiday['reason']); ?></td>
                        <td><?php echo htmlspecialchars($holiday['bdate']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h1 class="mb-4">Reservations</h1>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Event Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation['rdate']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['rtime']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['event_description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
      </div>
    </section>

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

