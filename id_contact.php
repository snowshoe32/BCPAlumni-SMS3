<?php
session_start();
include "db_conn.php";

if (!isset($_SESSION['admin_name'])) {
  header('Location: login_form.php');
  exit();
}

$admin_name = $_SESSION['admin_name'];

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
  <link href="assets/img/favicon.png" rel="icon">
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
            <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['admin_name'] ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $_SESSION['admin_name'] ?></h6>
              <span>Web Designer</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
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

      <li class="nav-heading">Your System</li>

      <li class="nav-item">
  <a class="nav-link collapsed" data-bs-target="#system-nav" data-bs-toggle="collapse" href="#">
    <i class="bi bi-layout-text-window-reverse"></i><span>Alumni Data</span><i class="bi bi-chevron-down ms-auto"></i>
  </a>
  <ul id="system-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
    <li>
      <a href="student-data.php">
        <i class="bi bi-circle"></i><span>Manage Alumni Data</span>
      </a>
    </li> 
    <li>
      <a href="add.php" class="active">
        <i class="bi bi-circle"></i><span>Add new Alumni</span>
      </a>
    </li>
  </ul>
</li><!-- End System Nav -->

      <hr class="sidebar-divider" />
       
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
      <a href="event-data.php">
        <i class="bi bi-circle"></i><span>Manage Job Posting</span>
      </a>
    </li>
    <li>
      <a href="add-event.php">
        <i class="bi bi-circle"></i><span>Add Job Posting</span>
      </a>
    </li>
    <li>
      <a href="add-event.php">
        <i class="bi bi-circle"></i><span>Manage Job Applications</span>
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
      <a href="event-data.php">
        <i class="bi bi-circle"></i><span>Manage Alumni Applications</span>
      </a>
    </li>
    <li>
      <a href="add-event.php">
        <i class="bi bi-circle"></i><span>News & Announcements</span>
      </a>
    </li>
  </ul>
</li>
<!--Student Alumni Services-->

      <hr class="sidebar-divider">
      
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Components</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="components-alerts.html">
              <i class="bi bi-circle"></i><span>Alerts</span>
            </a>
          </li>
          <li>
            <a href="components-accordion.html">
              <i class="bi bi-circle"></i><span>Accordion</span>
            </a>
          </li>
          <li>
            <a href="components-badges.html">
              <i class="bi bi-circle"></i><span>Badges</span>
            </a>
          </li>
          <li>
            <a href="components-breadcrumbs.html">
              <i class="bi bi-circle"></i><span>Breadcrumbs</span>
            </a>
          </li>
          <li>
            <a href="components-buttons.html">
              <i class="bi bi-circle"></i><span>Buttons</span>
            </a>
          </li>
          <li>
            <a href="components-cards.html">
              <i class="bi bi-circle"></i><span>Cards</span>
            </a>
          </li>
          <li>
            <a href="components-carousel.html">
              <i class="bi bi-circle"></i><span>Carousel</span>
            </a>
          </li>
          <li>
            <a href="components-list-group.html">
              <i class="bi bi-circle"></i><span>List group</span>
            </a>
          </li>
          <li>
            <a href="components-modal.html">
              <i class="bi bi-circle"></i><span>Modal</span>
            </a>
          </li>
          <li>
            <a href="components-tabs.html">
              <i class="bi bi-circle"></i><span>Tabs</span>
            </a>
          </li>
          <li>
            <a href="components-pagination.html">
              <i class="bi bi-circle"></i><span>Pagination</span>
            </a>
          </li>
          <li>
            <a href="components-progress.html">
              <i class="bi bi-circle"></i><span>Progress</span>
            </a>
          </li>
          <li>
            <a href="components-spinners.html">
              <i class="bi bi-circle"></i><span>Spinners</span>
            </a>
          </li>
          <li>
            <a href="components-tooltips.html">
              <i class="bi bi-circle"></i><span>Tooltips</span>
            </a>
          </li>
        </ul>
      </li><!-- End Components Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="forms-elements.html">
              <i class="bi bi-circle"></i><span>Form Elements</span>
            </a>
          </li>
          <li>
            <a href="forms-layouts.html">
              <i class="bi bi-circle"></i><span>Form Layouts</span>
            </a>
          </li>
          <li>
            <a href="forms-editors.html">
              <i class="bi bi-circle"></i><span>Form Editors</span>
            </a>
          </li>
          <li>
            <a href="forms-validation.html">
              <i class="bi bi-circle"></i><span>Form Validation</span>
            </a>
          </li>
        </ul>
      </li><!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tables-general.html">
              <i class="bi bi-circle"></i><span>General Tables</span>
            </a>
          </li>
          <li>
            <a href="tables-data.html">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Charts</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="charts-chartjs.html">
              <i class="bi bi-circle"></i><span>Chart.js</span>
            </a>
          </li>
          <li>
            <a href="charts-apexcharts.html">
              <i class="bi bi-circle"></i><span>ApexCharts</span>
            </a>
          </li>
          <li>
            <a href="charts-echarts.html">
              <i class="bi bi-circle"></i><span>ECharts</span>
            </a>
          </li>
        </ul>
      </li><!-- End Charts Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gem"></i><span>Icons</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="icons-bootstrap.html">
              <i class="bi bi-circle"></i><span>Bootstrap Icons</span>
            </a>
          </li>
          <li>
            <a href="icons-remix.html">
              <i class="bi bi-circle"></i><span>Remix Icons</span>
            </a>
          </li>
          <li>
            <a href="icons-boxicons.html">
              <i class="bi bi-circle"></i><span>Boxicons</span>
            </a>
          </li>
        </ul>
      </li><!-- End Icons Nav -->

      <hr class="sidebar-divider">

      <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-faq.html">
          <i class="bi bi-question-circle"></i>
          <span>F.A.Q</span>
        </a>
      </li><!-- End F.A.Q Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-contact.html">
          <i class="bi bi-envelope"></i>
          <span>Contact</span>
        </a>
      </li><!-- End Contact Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-register.html">
          <i class="bi bi-card-list"></i>
          <span>Register</span>
        </a>
      </li><!-- End Register Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-login.html">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Login</span>
        </a>
      </li><!-- End Login Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-error-404.html">
          <i class="bi bi-dash-circle"></i>
          <span>Error 404</span>
        </a>
      </li><!-- End Error 404 Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-blank.html">
          <i class="bi bi-file-earmark"></i>
          <span>Blank</span>
        </a>
      </li><!-- End Blank Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Manage ID Applications</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Student Alumni Services</li>
          <li class="breadcrumb-item active">Manage ID Application</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Bordered Tabs Justified</h5>

              <!-- Bordered Tabs Justified -->
              <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
  <li class="nav-item flex-fill" role="presentation">
    <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home" aria-selected="true">Approval of ID</button>
  </li>
  <li class="nav-item flex-fill" role="presentation">
    <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Manage ID</button>
  </li>
  <li class="nav-item flex-fill" role="presentation">
    <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Email Alumni</button>
  </li>
</ul>

<!-- Tab Content -->
<div class="tab-content pt-2" id="borderedTabJustifiedContent">

   <!-- Approval ID Applications -->
  <div class="tab-pane fade show active" id="bordered-justified-home" role="tabpanel" aria-labelledby="home-tab">
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Approval of ID</h5>
             
              <?php 
                  if(isset($_GET['msg'])){
                    $msg = $_GET['msg'];
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                   '.$msg.'
                   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  }
                  ?>
              
                <table class="table datatable  table-hover text-center">
  <thead class="table">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Last Name</th>
      <th scope="col">First Name</th>
      <th scope="col">Middle Name</th>
      <th scope="col">Student Number</th>
      <th scope="col">Contact</th>
      <th scope="col">Course</th>
      <th scope="col">Year Graduated</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    include "db_conn.php";
    $sql = "SELECT * FROM `bcp-sms3_idapprove`";
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
      <td><?php echo $row['course'] ?></td> 
      <td><?php echo $row['batch'] ?></td> 
      <td>
      <a href="id_approval.php?id=<?php echo $row['id']?>" class="fas fa-pen-square black-icon" style="font-size:24px;"><i class="ri ri-draft-fill "></i></a>
        <a href="#" class="fas fa-pen-square black-icon" style="font-size:24px" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $row['id']; ?>">
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
    confirmDelete.setAttribute('href', 'delete.php?id=' + recordId);
  });
</script>
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


  <!-- Manage ID Applications -->
  <div class="tab-pane fade" id="bordered-justified-profile" role="tabpanel" aria-labelledby="profile-tab">
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Manage ID Applications</h5>
              <div class="table container-table">              

  </tbody>
</table>
          
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
 <!-- Manage ID Applications -->




  <!-- ID Announcements and Email -->
  <div class="tab-pane fade" id="bordered-justified-contact" role="tabpanel" aria-labelledby="contact-tab">
    <section class="section" class="contact-info"><a href=""></a>
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Contact - Information</h5>
              <div class="table container-table">
                  ddddd
              <!-- Add relevant content for the Contact tab here -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
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
