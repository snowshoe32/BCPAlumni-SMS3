<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}

// Include the database connection file
include 'db_conn.php';

$admin_name = $_SESSION['admin_name'] ?? $_SESSION['super_admin_name'];

// Fetch audit log information
$sql = "SELECT id, user_type, student_no, username, name, event, timestamp, resource_access, ip_address FROM `bcp-sms3_auditlogs`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $logs = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $logs = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Dashboard - Title</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Favicons -->
    <link href="https://elc-public-images.s3.ap-southeast-1.amazonaws.com/bcp-olp-logo-mini2.png" rel="icon" />
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />

    <!-- Vendor CSS Files -->
    <link
      href="assets/vendor/bootstrap/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="assets/vendor/bootstrap-icons/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" />
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet" />
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet" />
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet" />
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet" />

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet" />
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
    <!-- End Sidebar-->

    <main id="main" class="main">
      <div class="pagetitle">
        <h1>Data Tables</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item">Tables</li>
            <li class="breadcrumb-item active">Data</li>
          </ol>
        </nav>
      </div>
      <!-- End Page Title -->

      <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Datatables</h5>
                <p></p>

                <!-- Table with stripped rows -->
                <div class="table container-table">
                  <?php 
                  if(isset($_GET['msg'])){
                    $msg = $_GET['msg'];
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                   '.$msg.'
                   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  }
                  ?>
                
                <table class="table datatable table-hover text-center">
  <thead class="table">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">User Type</th>
      <th scope="col">Student No</th>
      <th scope="col">Username</th>
      <th scope="col">Name</th>
      <th scope="col">Action Performed</th>
      <th scope="col">Timestamp</th>
      <th scope="col">Resource Access</th>
      <th scope="col">IP Address</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($logs as $log) {
      $action_class = '';
      switch ($log['event']) {
        case 'Logging in':
          $action_class = 'text-success';
          break;
        case 'Submitting an ID application':
          $action_class = 'text-info';
          break;
        case 'Applying for alumni tracer':
          $action_class = 'text-primary';
          break;
        case 'Editing data':
          $action_class = 'text-warning';
          break;
        case 'Deleting data':
          $action_class = 'text-danger';
          break;
        default:
          $action_class = 'text-muted';
          break;
      }
    ?>
      <tr>
      <td><?php echo $log['id'] ?></td>
      <td><?php echo $log['user_type'] ?></td>
      <td><?php echo $log['student_no'] ?></td>
      <td><?php echo $log['username'] ?></td>
      <td><?php echo $log['name'] ?></td>
      <td class="<?php echo $action_class; ?>"><?php echo $log['event'] ?></td>
      <td><?php echo $log['timestamp'] ?></td>
      <td><?php echo $log['resource_access'] ?></td>
      <td><?php echo $log['ip_address'] ?></td>
      <td>
        <a href="view_log.php?id=<?php echo $log['id']?>" class="fas fa-pen-square black-icon" style="font-size:24px;"><i class="bx bx-show-alt "></i></a>
        <a href="edit_log.php?id=<?php echo $log['id']?>" class="fas fa-pen-square black-icon" style="font-size:24px;"><i class="bx bxs-edit "></i></a>
        <a href="#" class="fas fa-pen-square black-icon" style="font-size:24px" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $log['id']; ?>">
  <i class="bx bxs-trash"></i>
</a>
      </td>
      </tr> 
    
    <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this record?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
        <!-- The delete button where we will inject the dynamic ID -->
        <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>
<script>
  var deleteModal = document.getElementById('deleteModal');
  deleteModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    var button = event.relatedTarget;
    // Extract the record id from data-id attribute
    var recordId = button.getAttribute('data-id');
    
    // Update the modal's delete button with the correct delete link
    var confirmDelete = deleteModal.querySelector('#confirmDelete');
    confirmDelete.setAttribute('href', 'delete_user.php?id=' + recordId);
  });
</script>
    <?php
    }
    ?> 

  </tbody>
</table>
                <!-- End Table with stripped rows -->
              </div>
            </div>
          </div>
        </div>
</div>
      </section>
    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
      <div class="copyright">
        &copy; Copyright <strong><span>NiceAdmin</span></strong
        >. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </footer>
    <!-- End Footer -->

    <a
      href="#"
      class="back-to-top d-flex align-items-center justify-content-center"
      ><i class="bi bi-arrow-up-short"></i
    ></a>

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
