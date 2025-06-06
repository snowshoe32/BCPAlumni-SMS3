<?php
session_start();
include 'db_conn.php';

// Remove the need to login to access this page

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
            

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="index2.php">
            <i class="bx bx-user"></i>
            <span class="d-none d-md-block ps-2">Sign In</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>Sign In</h6>
              <span>Please sign in to access more features</span>
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
        <a class="nav-link " href="index2.php" class="active">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <hr class="sidebar-divider">

      <li class="nav-heading"></li>

      <li class="nav-item">
        <a class="nav-link " href="index2.php" class="active">
          <i class="bi bi-grid"></i>
          <span>Announcements</span>
        </a>
      </li><!-- Announcements Nav -->

  
</li><!-- End System Nav -->
      <hr class="sidebar-divider">



      <li class="nav-item">
        <a class="nav-link " href="index2.php" class="active">
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
      <a href="index2.php">
        <i class="bi bi-circle"></i><span>View Alumni ID Applications</span>
      </a>
    </li>
    <li>
      <a href="index2.php">
        <i class="bi bi-circle"></i><span>Alumni Tracer</span>
      </a>
    </li>
    <li>
      <a href="index2.php">
        <i class="bi bi-circle"></i><span>Apply for Alumni ID</span>
      </a>
    </li>
  </ul>
</li>
<!--Student Alumni Services-->


<hr class="sidebar-divider">

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
      <div class="row">


        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

            <!-- Sales Card -->
          
          </div>
          <div class="col-lg-12" style="padding: 0; width: 150%;">
  <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="9000">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="assets/img/alumnipic1.jpg" class="d-block w-100" alt="Alumni Pic 1" style="height: 85%;  object-fit: cover;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6);"></div>
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 60px; font-weight: 100 bold; font-family: 'Consolas', sans-serif; opacity: 0.9; pointer-events: none; text-align: center;">
          Bestlink Alumni Association
        </div>
      </div>
      <div class="carousel-item">
        <img src="assets/img/alumnipic2.jpg" class="d-block w-100" alt="Alumni Pic 2" style="height: 85%; object-fit: cover;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6);"></div>
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 60px; font-weight: 100 bold; font-family: 'Consolas', sans-serif; opacity: 0.9; pointer-events: none; text-align: center;">
          Bestlink Alumni Association
        </div>
      </div>
      <div class="carousel-item">
        <img src="assets/img/alumnipic3.jpg" class="d-block w-100" alt="Alumni Pic 3" style="height: 85%; object-fit: cover;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6);"></div>
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 60px; font-weight: 100 bold; font-family: 'Consolas', sans-serif; opacity: 0.9; pointer-events: none; text-align: center;">
          Bestlink Alumni Association
        </div>
      </div>
      <div class="carousel-item">
        <img src="assets/img/alumnipic4.jpg" class="d-block w-100" alt="Alumni Pic 4" style="height: 85%; object-fit: cover;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6);"></div>
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 60px; font-weight: 100 bold; font-family: 'Consolas', sans-serif; opacity: 0.9; pointer-events: none; text-align: center;">
          Bestlink Alumni Association
        </div>
      </div>
      <div class="carousel-item">
        <img src="assets/img/alumnipic5.jpg" class="d-block w-100" alt="Alumni Pic 5" style="height: 85%; object-fit: cover;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6);"></div>
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 60px; font-weight: 100 bold; font-family: 'Consolas', sans-serif; opacity: 0.9; pointer-events: none; text-align: center;">
          Bestlink Alumni Association
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</div>
        </div><!-- End Left side columns -->
       



        <!-- Right side columns -->
     
         
         
        

          <!-- News & Updates Traffic -->
          <div class="card">

            <div class="card-body pb-0"></div>
              <h5 class="card-title">Recent News &amp; Updates <span>| Today</h5>

              <div class="news">
                <?php include 'fetch_facebook_posts.php'; ?>
              </div>
            </div>
          </div><!-- End News & Updates -->
        <!-- End Right side columns -->
      </div>
    </section>
  </main><!-- End #main -->
  <div style="display: flex; justify-content: center; margin-top: 20px;">
  <div style="margin-right: 40px;">
    <h5>Office of Alumni Relations</h5>
    <p>1071 Brgy. Kaligayahan, Quirino Highway 
        Novaliches, Quezon City, Philippines</p>
  </div>
  <div style="margin-right: 40px;">
    <h5>Email & Contact</h5>
    <p>bestlinkalumni@gmail.com</p>
    <p>bcp-inquiry@bcp.edu.ph</p>
    <p>284428601</p>
  </div>
  <div>
    <h5>Visit us on:</h5>
    <a href="https://www.facebook.com/BestlinkAlumni" target="_blank">
      <i class="bi bi-facebook" style="font-size: 24px; color:rgb(100, 105, 116);"></i>
    </a>
    <a href="https://www.tiktok.com/@bestlinkcollege_official" target="_blank" style="margin-left: 10px;">
      <i class="bi bi-tiktok" style="font-size: 24px; color:rgb(100, 105, 116);"></i>
    </a>
  </div>
</div>
  
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
  <footer id="footer" class="footer">
    <div class="copyright">
    
    </div>
    <div class="credits">
    </div>
  </footer><!-- End Footer -->
  </footer><!-- End Footer -->
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>s"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <!-- Template Main JS File --></script>
  <script src="assets/js/main.js"></script>
  <!-- Template Main JS File -->
</body></script>

</html>

</html>

