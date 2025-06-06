<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}
include "db_conn.php";

$admin_name = $_SESSION['admin_name'];

if (isset($result) && $result) {
  if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_array($result);
      
  } else {
      echo "No admin found with the username: " . htmlspecialchars($admin_name);
  }
} else {
  echo " " . mysqli_error($conn);
}

if (isset($_POST['detect'])) {
    // Removed detection and approval logic
    $msg = "Detection and approval functionality has been removed.";
    header("Location: id_manage.php?msg=" . urlencode($msg));
    exit();
}

if (isset($_POST['update_status'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql_update_status = "UPDATE `bcp-sms3-idmanage` SET `status` = '$new_status' WHERE `id` = '$id'";
    if (mysqli_query($conn, $sql_update_status)) {
        $msg = "Status for ID $id updated successfully.";
    } else {
        $msg = "Error updating status for ID $id: " . mysqli_error($conn);
    }
    header("Location: id_manage.php?msg=" . urlencode($msg));
    exit();
}

if (isset($_POST['generate_report'])) {
    $filename = "id_applications_report_" . date('Ymd') . ".csv";
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");

    $output = fopen("php://output", "w");
    fputcsv($output, ['ID', 'Last Name', 'First Name', 'Middle Name', 'Student Number', 'Contact Number', 'Email', 'Birthdate', 'Status']);

    $sql = "SELECT id, last_name, first_name, middle_name, student_no, contact, email, birthdate, status FROM `bcp-sms3-idmanage`";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

if (isset($_POST['delete_record'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql_delete = "DELETE FROM `bcp-sms3-idmanage` WHERE `id` = '$id'";
    if (mysqli_query($conn, $sql_delete)) {
        $msg = "Record with ID $id deleted successfully.";
    } else {
        $msg = "Error deleting record with ID $id: " . mysqli_error($conn);
    }
    header("Location: id_manage.php?msg=" . urlencode($msg));
    exit();
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
<li>
    <a href="alumni_benefits.php">
    <i class="bi bi-circle"></i><span>Alumni Benefits</span>
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
      <h1>Manage ID Applications</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="admin_dashboard.php"be>Home</a></li>
          <li class="breadcrumb-item">Alumni Online Services</li>
          <li class="breadcrumb-item active">Manage ID Applications</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <div class="card" style="width: 100%; min-height: auto; overflow: auto;">
            <div class="card-body">
              <h5 class="card-title">Manage Alumni ID</h5>

              <!-- Generate Report Button -->
              <form method="POST" action="" style="display: inline;">
                  <button type="submit" name="generate_report" class="btn btn-success mb-3">Generate Report</button>
              </form>
              <button onclick="printTable()" class="btn btn-secondary mb-3" style="display: inline;">Print Data</button>

   <!-- Approval ID Applications -->
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
            <form method="POST" action="">
                <div class="input-group mb-3">
                    <!-- Removed Detect and Update All button -->
                </div>
            </form>
                <table class="table datatable  table-hover text-center">
  <thead class="table">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Last Name</th>
      <th scope="col">First Name</th>
      <th scope="col">Middle Name</th>
      <th scope="col">Student Number</th>
      <th scope="col">Contact Number</th>
      <th scope="col">Email</th>
      <th scope="col">Birthdate</th>
      <th scope="col">Status</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    include "db_conn.php";
    $sql = "SELECT id, last_name, first_name, middle_name, student_no, contact, email, birthdate, status FROM `bcp-sms3-idmanage`";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)){ 
      $student_no = $row['student_no'];
    ?>
      
      <tr>
      <td><?php echo $row['id'] ?></td>
      <td><?php echo $row['last_name'] ?></td>
      <td><?php echo $row['first_name'] ?></td>
      <td><?php echo $row['middle_name'] ?></td>
      <td><?php echo $row['student_no'] ?></td>
      <td><?php echo $row['contact'] ?></td>
      <td><?php echo $row['email'] ?></td>
      <td><?php echo empty($row['birthdate']) ? date('Y-m-d') : $row['birthdate']; ?></td>
      <td>
      <h5> <span class= "badge 
        <?php 
            if ($row['status'] === 'Pending') {
                echo 'bg-secondary'; 
            } elseif ($row['status'] === 'Received') { // Changed from 'Approved' to 'Received'
                echo 'bg-success'; 
            } elseif ($row['status'] === 'Rejected')
            echo 'bg-danger';
            elseif ($row['status'] === 'Error')
            echo 'bg-warning';
        ?> ">
        <?php echo $row['status']; ?>
    </span></h5>
</td>
      <td>
      <form method="POST" action="" style="display: inline;">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <select name="status" class="form-select form-select-sm" style="width: auto; display: inline-block;">
            <option value="Pending" <?php echo $row['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Received" <?php echo $row['status'] === 'Received' ? 'selected' : ''; ?>>Received</option> <!-- Changed from 'Approved' to 'Received' -->
            <option value="Rejected" <?php echo $row['status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
            <option value="Error" <?php echo $row['status'] === 'Error' ? 'selected' : ''; ?>>Error</option>
        </select>
        <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
    </form>
    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['id']; ?>">Delete</button>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel<?php echo $row['id']; ?>">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the record with ID <?php echo $row['id']; ?>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_record" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</td>
      </tr> 
    <?php
    }
    ?> 

  </tbody>
</table>


            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
<!--Approval of ID-->

  
        </form><!-- End Multi Columns Form -->

      </div>
    </div>

  </div>

<script>
function printTable() {
    const printContents = document.querySelector('.table').outerHTML;
    const originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>







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
