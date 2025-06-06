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

$sql = "SELECT * FROM `bcp-sms3_alumnidata` WHERE `student_no` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $alumni_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $alumni_fname = htmlspecialchars($row['fname']); // Display first name safely
        // Other logic for the alumni dashboard
    } else {
        echo "No admin found with the username: Guest";
    }
} else {
    echo "MySQL Error: " . $conn->error;
}

// Fetch news data from the database
$sql_news = "SELECT * FROM `bcp-sms3_news` ORDER BY date DESC";
$result_news = mysqli_query($conn, $sql_news);
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
        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $alumni_fname; ?></span>
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
          <a class="dropdown-item d-flex align-items-center" href="data-profile.php">
            <i class="bi bi-file-earmark-person"></i>
            <span>Data Profile</span>
          </a>
        </li>
        
        <li>
          <a class="dropdown-item d-flex align-items-center" href="logout_form2.php">
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
        <a class="nav-link " href="alumni_dashboard.php" class="active">
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
    <i class="bi bi-layout-text-window-reverse"></i><span>Alumni Online Services</span><i class="bi bi-chevron-down ms-auto"></i>
  </a>
  <ul id="students-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
    
    <li>
      <a href="alumni_tracer.php">
        <i class="bi bi-circle"></i><span>Alumni Tracer</span>
      </a>
    </li>
    <li>
      <a href="id_application.php">
        <i class="bi bi-circle"></i><span>Apply for Alumni ID</span>
      </a>
    </li>
    <li>
      <a href="alumni_benefits2.php">
        <i class="bi bi-circle"></i><span>Alumni Benefits</span>
      </a>
    </li>
  </ul>
</li>
<!--Alumni Online Services-->

<!-- Remove Profile and Contact links -->
<!-- End Profile Page Nav -->
<!-- End Contact Page Nav -->

<hr class="sidebar-divider">

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Announcements</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Announcements</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->


   
    </section>

    <?php
// Fetch news data from the database
$sql_news = "SELECT * FROM `bcp-sms3_news` ORDER BY date DESC";
$result_news = mysqli_query($conn, $sql_news);
?>

<section class="section">
  <div class="row">
    <?php
    if ($result_news && mysqli_num_rows($result_news) > 0) {
      while ($row_news = mysqli_fetch_assoc($result_news)) {
        $formatted_date = date("m/d/Y", strtotime($row_news['date']));
        if (isset($row_news['photo']) && !empty($row_news['photo']) && file_exists($row_news['photo'])) {
            $image_path = htmlspecialchars($row_news['photo']);
        } else {
            $image_path = 'assets/img/placeholder.png'; // Default placeholder image

        }
        $headline = htmlspecialchars($row_news['headline']);
        $publisher = htmlspecialchars($row_news['publisher']);
        $news_id = $row_news['id']; // Assuming 'id' is the primary key for the news table
    ?>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="card">
            <a href="alumniviewnews.php?id=<?php echo $news_id; ?>">
              <img src="<?php echo $image_path; ?>" class="card-img-top" alt="News Image" style="height: 200px; object-fit: cover;">
            </a>
            <div class="card-body ">
              <h5 class="card-title "><?php echo $headline; ?></h5>
              <p class="card-text">Published by: <?php echo $publisher; ?></p>
              <p class="card-text ">
                <small class="text-muted">Date: <?php echo $formatted_date; ?></small>
              </p>
              <a href="embed.php?id=<?php echo $news_id; ?>" class="bi bi-facebook text-muted"></a>
            </div>
          </div>
        </div>
    <?php
      }
    } else {
      echo "<p>No news available</p>";
    }
    ?>
  </div>
</section>

                </tbody>
              </table>
            </div>
          </div>
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
