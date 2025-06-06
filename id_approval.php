<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}
include 'db_conn.php';
$id = $_GET['id']; 

// Check if the user is logged in
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}

$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : $_SESSION['super_admin_name'];

if (isset($_POST['submit'])) {
  // Sanitize user inputs
  $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
  $last_name = mysqli_real_escape_string($conn, $_POST['last_name']); 
  $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']); 
  $student_no= mysqli_real_escape_string($conn, $_POST['student_no']); 
  $contact = mysqli_real_escape_string($conn, $_POST['contact']);
  $e_contact = mysqli_real_escape_string($conn, $_POST['e_contact']);
  $batch = mysqli_real_escape_string($conn, $_POST['batch']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $course = mysqli_real_escape_string($conn, $_POST['course']);
  $photo = mysqli_real_escape_string($conn, $_POST['photo']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);

  
    
   // SQL statement
   $sql = "UPDATE `bcp_sms3_idapprove` SET `first_name`='$first_name',`last_name`='$last_name', `middle_name`='$middle_name', `student_no`='$student_no',
    `contact`='$contact',`e_contact`='$e_contact',`batch`='$batch',`address`='$address',`course`='$course',`photo`='$photo',`status`='$status' WHERE id=$id";


    // Execute the query
    $result = mysqli_query($conn, $sql);
    if ($result) {  
        header("Location: id_manage.php?msg=Record Updated successfully");
        exit(); // Good practice to call exit after header redirection
    } else {
        echo "Failed: " . mysqli_error($conn); // Display error message
    } 
  }
if ($result) {
  if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_array($result);
      // Other logic for the admin dashboard
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
      <a href="add.php">
        <i class="bi bi-circle"></i><span>Add new Alumni</span>
      </a>
    </li>
  </ul>
</li><!-- End System Nav -->

<hr class="sidebar-divider">

       <!-- Removed Alumni Events Section -->
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
    <i class="bi bi-layout-text-window-reverse"></i><span>Alumni Online Services</span><i class="bi bi-chevron-down ms-auto"></i>
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
<!--Alumni Online Services-->

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
      <h1>Approval of ID</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Pages</li>
          <li class="breadcrumb-item active">Approval of ID</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Approval of ID</h5>

        <!-- Multi Columns Form -->
        
        <?php
include "db_conn.php";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql = "SELECT * FROM `bcp_sms3_idapprove` WHERE `id` = '$id'";
$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row) {
       
        $student_no = $row['student_no'];
      
    
    } else {
        echo "No record found for ID: $id";
        exit;
    }
} else {
    die("Error executing query: " . mysqli_error($conn));
}

?>
        <form class="row g-3" method="post">
        
    <div class="col-md-10">
        <label class="form-label">Last Name</label>
        <input type="text" class="form-control" name="last_name_display" value="<?php echo $row['last_name']?>" required disabled>    
        <input type="hidden" name="last_name" value="<?php echo $row['last_name']?>">
    </div>

         <div class="col-md-10">
        <label class="form-label">First Name </label>
         <input type="text" class="form-control" name="first_name" value="<?php echo $row['first_name']?>" required disabled>
         <input type="hidden" name="first_name" value="<?php echo $row['first_name']?>">
         </div>

         <div class="col-md-10">
        <label class="form-label">Middle Name </label>
         <input type="text" class="form-control" name="middle_name" value="<?php echo $row['middle_name']?>" required disabled>
         <input type="hidden" name="middle_name" value="<?php echo $row['middle_name']?>">
         </div>
         <div class="col-md-10">
        <label class="form-label">Address </label>
         <input type="text" class="form-control" name="address" value="<?php echo $row['address']?>" required disabled>
         <input type="hidden" name="address" value="<?php echo $row['address']?>">
         </div>


         <div class="col-md-4">
            <label for="inputStudent" class="form-label">Student Number</label>
            <input type="text" class="form-control" pattern="\d{8}" name="student_no" maxlength="8" value="<?php echo $row['student_no']?>"
         oninput="this.value=this.value.replace(/[^0-9]/g,'')" required disabled
         title="Student number must be exactly 8 digits.">
         <input type="hidden" name="student_no" value="<?php echo $row['student_no']?>">
          <div class="invalid-feedback">
    Please enter exactly 8 numeric digits for the student number.
       </div>
          </div>
          
          <div class="col-md-4">
            <label for="inputContact" class="form-label">Contact Number</label>
            <input type="text" class="form-control" pattern="\d{11}" name="contact" maxlength="11" 
         
          oninput="this.value=this.value.replace(/[^0-9]/g,'')" required disabled
            value="<?php echo $row['contact']; ?>"
         title="Contact number must be exactly 11 digits.">
         <input type="hidden" name="contact" value="<?php echo $row['contact']?>">
          <div class="invalid-feedback">
    Please enter exactly 11 numeric digits for the contact number.
          </div>
          </div> 

          <div class="col-md-4">
            <label for="inputContact" class="form-label">Emergency Contact Number</label>
            <input type="text" class="form-control" pattern="\d{11}" name="e_contact" maxlength="11"       
          oninput="this.value=this.value.replace(/[^0-9]/g,'')" required disabled
            value="<?php echo $row['contact']; ?>"
         title="Contact number must be exactly 11 digits.">
         <input type="hidden" name="e_contact" value="<?php echo $row['e_contact']?>">
          <div class="invalid-feedback">
    Please enter exactly 11 numeric digits for the contact number.   
          </div>
          </div> 

        


    
    <div class="col-md-5">
            <label for="inputState" class="form-label">Course</label>
            <select id="gender" class="form-select" name="course" required disabled>
            <option value="BSE" <?php if ($row['course'] === 'BSE') echo 'selected'; ?>>Bachelor of Science in Entrepreneurship</option>         
            <option value="BEED" <?php if ($row['course'] === 'BEED') echo 'selected'; ?>>Bachelor in Elementary Education</option>
            <option value="BSEd" <?php echo ($row['course'] == 'BSEd') ? 'selected' : ''; ?>>Bachelor in Secondary Education</option>
            <option value="BSCRIM" <?php echo ($row['course'] == 'BSCRIM') ? 'selected' : ''; ?>>Bachelor of Science in Criminology</option>
            <option value="BSPSYCH" <?php if ($row['course'] === 'BSPSYCH') echo 'selected'; ?>>Bachelor of Science in Psychology</option>         
            <option value="BSIE" <?php if ($row['course'] === 'BSIE') echo 'selected'; ?>>Bachelor of Science in Industrial Engineering</option>
            <option value="BSBA" <?php echo ($row['course'] == 'BSBA') ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>
            <option value="BSCE" <?php echo ($row['course'] == 'BSCE') ? 'selected' : ''; ?>>Bachelor of Science in Computer Engineering</option>
            <option value="BSIT" <?php if ($row['course'] === 'BSIT') echo 'selected'; ?>>Bachelor of Science in Information Technology</option>         
            <option value="BSHM" <?php if ($row['course'] === 'BSHM') echo 'selected'; ?>>Bachelor of Science in Hospitality Management</option>
            <option value="BSOA" <?php echo ($row['course'] == 'BSOA') ? 'selected' : ''; ?>>Bachelor of Science in Office Administration</option>
            <option value="BSLIS" <?php echo ($row['course'] == 'BSLIS') ? 'selected' : ''; ?>>Bachelor of Library and Information Science</option>

            </select>
            <input type="hidden" name="course" value="<?php echo $row['course']?>">
          </div>
      
   
         
          <div class="col-md-3">
    <label for="inputState" class="form-label">Year Graduated</label>
    <select id="batch" class="form-select" name="batch" required disabled>
        <option value="2025" <?php if ($row['batch'] === '2025') echo 'selected'; ?>>2025</option>    
        <option value="2024" <?php if ($row['batch'] === '2024') echo 'selected'; ?>>2024</option> 
        <option value="2023" <?php if ($row['batch'] === '2023') echo 'selected'; ?>>2023</option> 
        <option value="2022" <?php if ($row['batch'] === '2022') echo 'selected'; ?>>2022</option> 
        <option value="2021" <?php if ($row['batch'] === '2021') echo 'selected'; ?>>2021</option> 
        <option value="2020" <?php if ($row['batch'] === '2020') echo 'selected'; ?>>2020</option> 
        <option value="2019" <?php if ($row['batch'] === '2019') echo 'selected'; ?>>2019</option> 
        <option value="2018" <?php if ($row['batch'] === '2018') echo 'selected'; ?>>2018</option> 
        <option value="2017" <?php if ($row['batch'] === '2017') echo 'selected'; ?>>2017</option> 
        <option value="2016" <?php if ($row['batch'] === '2016') echo 'selected'; ?>>2016</option> 
        <option value="2015" <?php if ($row['batch'] === '2015') echo 'selected'; ?>>2015</option> 
        <option value="2014" <?php if ($row['batch'] === '2014') echo 'selected'; ?>>2014</option> 
        <option value="2013" <?php if ($row['batch'] === '2013') echo 'selected'; ?>>2013</option> 
        <option value="2012" <?php if ($row['batch'] === '2012') echo 'selected'; ?>>2012</option> 
        <option value="2011" <?php if ($row['batch'] === '2011') echo 'selected'; ?>>2011</option> 
        <option value="2010" <?php if ($row['batch'] === '2010') echo 'selected'; ?>>2010</option> 
        <option value="2009" <?php if ($row['batch'] === '2009') echo 'selected'; ?>>2009</option> 
        <option value="2008" <?php if ($row['batch'] === '2008') echo 'selected'; ?>>2008</option> 
        <option value="2007" <?php if ($row['batch'] === '2007') echo 'selected'; ?>>2007</option> 
        <option value="2006" <?php if ($row['batch'] === '2006') echo 'selected'; ?>>2006</option> 
        <option value="2005" <?php if ($row['batch'] === '2005') echo 'selected'; ?>>2005</option> 
        <option value="2004" <?php if ($row['batch'] === '2004') echo 'selected'; ?>>2004</option> 
        <option value="2003" <?php if ($row['batch'] === '2003') echo 'selected'; ?>>2003</option> 
    </select>
    <input type="hidden" name="batch" value="<?php echo $row['batch']?>">
</div>
    
<div class="col-md-3">
        <label for="userType" class="col-sm-4 col-form-label">Status</label>
        <div class="col-sm-4">
        <select name="status" class="form-control" required>
          
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
            <option value="Error">Error</option>
            
        </select>
        </div>
    </div>
   

            
    <form action="submit.php" method="POST">
          
    <div class="text-center">
    <button type="submit" class="btn btn-success" name="submit">Update</button>
         <a href="id_manage.php"  class="btn btn-primary">Back</a>
            
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
