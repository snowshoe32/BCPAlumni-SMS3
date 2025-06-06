<?php
session_start();
include 'db_conn.php';

// Check if user is logged in and is an alumni
if (!isset($_SESSION['alumni_name'])) {
    header("Location: index2.php");
    exit();
}

$alumni_name = $_SESSION['alumni_name'];

// Fetch alumni details
$sql = "SELECT * FROM `bcp-sms3_alumnidata` WHERE `student_no` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $alumni_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $alumni_fname = htmlspecialchars($row['fname']);
} else {
    echo "<p>No alumni found with the student number: " . htmlspecialchars($alumni_name) . "</p>";
    exit();
}
$stmt->close();

// Check if a specific news item is being requested
$news_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni News</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
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
  </ul>
</li>
<!--Alumni Online Services-->

<!-- Remove Profile and Contact links -->
<!-- End Profile Page Nav -->
<!-- End Contact Page Nav -->

<hr class="sidebar-divider">

  </aside><!-- End Sidebar-->

    <!-- Main Content -->
    <main id="main" class="main">
        <div class="container">
        <h1>View News</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="alumni_dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="announcements.php">Announcements</a></li>
                    <li class="breadcrumb-item active" aria-current="page">News Details</li>
                </ol>
            </nav>
            <div class="news">
                <?php
                if ($news_id > 0) {                    
                    $news_sql = "SELECT * FROM `bcp-sms3_news` WHERE `id` = ?";
                    $stmt = $conn->prepare($news_sql);
                    $stmt->bind_param("i", $news_id);
                    $stmt->execute();
                    $news_result = $stmt->get_result();
                
                    if ($news_result->num_rows > 0) {
                        $news = $news_result->fetch_assoc();
                        $image_path = htmlspecialchars($news['photo']);
                        echo "<div class='card'>";
                        echo "<div class='card-body'>";
                        echo "<h3 class='card-title mt-3'>" . htmlspecialchars($news['headline']) . "</h3>";
                        echo "<p class='card-text'><strong>Published by:</strong> " . htmlspecialchars($news['publisher']) . "</p>";
                        echo "<p class='card-text'><strong>Published on:</strong> " . date("m/d/Y", strtotime($news['date'])) . "</p>";
                        echo "<p class='card-text'>" . nl2br(htmlspecialchars($news['description'])) . "</p>";
                        if (isset($news['photo']) && !empty($news['photo']) && file_exists($news['photo'])) {
                            $image_path = htmlspecialchars($news['photo']);
                            echo "<img src='$image_path' class='card-img-top mt-3' alt='News Image' style='max-width: 600px; max-height: 600px; width: auto; height: auto; display: block; margin: 0 auto;'>";
                        } else {
                            $image_path = 'assets/img/placeholder.png'; // Default placeholder image
                            echo "<img src='$image_path' class='card-img-top mt-3' alt='News Image' style='max-width: 600px; max-height: 600px; width: auto; height: auto; display: block; margin: 0 auto;'>";
                        }
                        echo "<div class='text-center'><a href='announcements.php' class='btn btn-primary mt-3'>Back</a></div>";
                        echo "</div>";
                        echo "</div>";
                        
                        
                    } else {
                        echo "<p>No news found with the specified ID.</p>";
                    }
                    $stmt->close();
                } else {
                    echo "<p>No specific news selected. Please select a news item to view its details.</p>";
                }
                ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Bestlink Alumni Association</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </footer>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
