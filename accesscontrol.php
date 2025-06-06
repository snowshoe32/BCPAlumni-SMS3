<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    echo "<script>alert('You are not allowed to access this page.');</script>";
    exit();
}

// Include the database connection file
include 'db_conn.php';

$admin_name = $_SESSION['super_admin_name'];

// Handle permission change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id']) && isset($_POST['new_permission'])) {
    $user_id = $_POST['user_id'];
    $new_permission = $_POST['new_permission'];
    $update_sql = "UPDATE `bcp_sms3_user` SET user_type = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('si', $new_permission, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: accesscontrol.php');
    exit();
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_user_id'])) {
    $delete_user_id = $_GET['delete_user_id'];
    $delete_sql = "DELETE FROM `bcp_sms3_user` WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $delete_user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: accesscontrol.php');
    exit();
}

// Handle user data update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user_id'])) {
    $edit_user_id = $_POST['edit_user_id'];
    $edit_name = $_POST['edit_name'];
    $edit_email = $_POST['edit_email'];
    $edit_student_no = $_POST['edit_student_no'];
    $edit_username = $_POST['edit_username'];
    $edit_sql = "UPDATE `bcp_sms3_user` SET name = ?, email = ?, student_no = ?, username = ? WHERE id = ?";
    $stmt = $conn->prepare($edit_sql);
    $stmt->bind_param('sssii', $edit_name, $edit_email, $edit_student_no, $edit_username, $edit_user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: accesscontrol.php');
    exit();
}

// Fetch user information
$sql = "SELECT id, name, email, student_no, username, user_type FROM `bcp_sms3_user`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $users = [];
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
        <h1>Access Control</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
            <li class="breadcrumb-item active">Access Control</li>
          </ol>
        </nav>
      </div>
      <!-- End Page Title -->

      <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Access Control</h5>
                <p></p>

                <?php if (isset($_SESSION['super_admin_name'])): ?>
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
      <th scope="col">Name</th>
      <th scope="col">Email</th>
     
      <th scope="col">Username</th>
      <th scope="col">User Type</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($users as $user) {
      
      $class = '';
      switch ($user['user_type']) {
        case 'alumni':
          $class = 'text-primary';
          break;
        case 'admin':
          $class = 'text-danger';
          break;
        case 'super_admin':
          $class = 'text-warning';
          break;
      }
    ?>
      <tr>
      <td><?php echo $user['id'] ?></td>
      <td><?php echo $user['name'] ?></td>
      <td><?php echo $user['email'] ?></td>
     
      <td><?php echo $user['username'] ?></td>
      <td class="<?php echo $class; ?>"><?php echo $user['user_type'] ?></td>
      <td>
      
        
        <a href="#" class="fas fa-pen-square black-icon" style="font-size:24px" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $user['id']; ?>">
  <i class="bx bxs-trash"></i>
</a>
<a href="#" class="fas fa-pen-square black-icon" style="font-size:24px" data-bs-toggle="modal" data-bs-target="#changePermissionModal" data-id="<?php echo $user['id']; ?>" data-permission="<?php echo $user['user_type']; ?>">
  <i class="bx bxs-key"></i>
</a>
<a href="#" class="fas fa-pen-square black-icon" style="font-size:24px" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $user['id']; ?>" data-name="<?php echo $user['name']; ?>" data-email="<?php echo $user['email']; ?>" data-student_no="<?php echo $user['student_no']; ?>" data-username="<?php echo $user['username']; ?>">
  <i class="bx bxs-edit"></i>
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
    confirmDelete.setAttribute('href', 'accesscontrol.php?delete_user_id=' + recordId);
  });
</script>
    <?php
    }
    ?> 

  </tbody>
</table>
                <!-- End Table with stripped rows -->
                <?php else: ?>
                <p>You do not have permission to view this content.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
</div>
      </section>
    </main>
    <!-- End #main -->

    <!-- Change Permission Modal -->
    <div class="modal fade" id="changePermissionModal" tabindex="-1" aria-labelledby="changePermissionModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="changePermissionModalLabel">Change User Permission</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="accesscontrol.php">
            <div class="modal-body">
              <input type="hidden" name="user_id" id="user_id">
              <div class="mb-3">
                <label for="new_permission" class="form-label">New Permission</label>
                <select class="form-select" id="new_permission" name="new_permission" required>
                  <option value="alumni">Alumni</option>
                  <option value="admin">Admin</option>
                  <option value="super_admin">Super Admin</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Change Permission</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      var changePermissionModal = document.getElementById('changePermissionModal');
      changePermissionModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var userId = button.getAttribute('data-id');
        var userPermission = button.getAttribute('data-permission');
        var modalUserId = changePermissionModal.querySelector('#user_id');
        var modalUserPermission = changePermissionModal.querySelector('#new_permission');
        modalUserId.value = userId;
        modalUserPermission.value = userPermission;
      });
    </script>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit User Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="accesscontrol.php">
            <div class="modal-body">
              <input type="hidden" name="edit_user_id" id="edit_user_id">
              <div class="mb-3">
                <label for="edit_name" class="form-label">Name</label>
                <input type="text" class="form-control" id="edit_name" name="edit_name" required>
              </div>
              <div class="mb-3">
                <label for="edit_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="edit_email" name="edit_email" required>
              </div>
              <div class="mb-3">
                <label for="edit_student_no" class="form-label">Student No</label>
                <input type="text" class="form-control" id="edit_student_no" name="edit_student_no" required>
              </div>
              <div class="mb-3">
                <label for="edit_username" class="form-label">Username</label>
                <input type="text" class="form-control" id="edit_username" name="edit_username" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      var editModal = document.getElementById('editModal');
      editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var userId = button.getAttribute('data-id');
        var userName = button.getAttribute('data-name');
        var userEmail = button.getAttribute('data-email');
        var userStudentNo = button.getAttribute('data-student_no');
        var userUsername = button.getAttribute('data-username');
        var modalUserId = editModal.querySelector('#edit_user_id');
        var modalUserName = editModal.querySelector('#edit_name');
        var modalUserEmail = editModal.querySelector('#edit_email');
        var modalUserStudentNo = editModal.querySelector('#edit_student_no');
        var modalUserUsername = editModal.querySelector('#edit_username');
        modalUserId.value = userId;
        modalUserName.value = userName;
        modalUserEmail.value = userEmail;
        modalUserStudentNo.value = userStudentNo;
        modalUserUsername.value = userUsername;
      });
    </script>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
      <div class="copyright">
        &copy; Copyright <strong><span>Bestlink College of the Philippines</span></strong
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
