<?php
session_start();
include "db_conn.php";

if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM `bcp-sms3_news` WHERE id = $id";
$result = mysqli_query($conn, $sql);
$news = mysqli_fetch_assoc($result);

if (!$news) {
    echo "News item not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $headline = mysqli_real_escape_string($conn, $_POST['headline']);
    $publisher = mysqli_real_escape_string($conn, $_POST['publisher']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $updateSql = "UPDATE `bcp-sms3_news` SET headline = '$headline', publisher = '$publisher', date = '$date', description = '$description' WHERE id = $id";
    if (mysqli_query($conn, $updateSql)) {
        header("Location: admin_managenews.php?msg=News updated successfully");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View News</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- Add z-index to the sidebar to ensure it appears in front of the menu bar -->
    <style>
        .sidebar {
            z-index: 1050; /* Ensure it is higher than the menu bar */
        }
    </style>
</head>
<body>
    <header class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : $_SESSION['super_admin_name']; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : $_SESSION['super_admin_name']; ?></h6>
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
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <aside class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <div style="display: flex; flex-direction: column; align-items: center; margin-top: 24px; text-align: center;">
                <div style="font-weight: 500; color: #fff;">
                </div>
                <div class="flex items-center justify-center" style="display: flex; align-items: center; justify-content: center; margin-top: 40px;">
                    <img src="assets/img/bestlinkalumnilogo1.png" alt="Bestlink Alumni Logo" style="width:130px;height: auto;">
                </div>
            </div>
            <div style="margin-top: 4px; font-size: 14px; color: #fff;">
                <h6><span></span></h6>
            </div>
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link " href="admin_dashboard.php" class="active">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>
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
            </li>
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
            </li>
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
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link " href="auditlogs.php" class="active">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Audit Logs</span>
                </a>
            </li>
            <hr class="sidebar-divider">
        </ul>
    </aside>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Edit News</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
                    <li class="breadcrumb-item">News & Announcements</li>
                    <li class="breadcrumb-item active">Edit News</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit News Details</h5>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="headline" class="form-label">Headline</label>
                                    <input type="text" class="form-control" id="headline" name="headline" value="<?php echo htmlspecialchars($news['headline']); ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="publisher" class="form-label">Publisher</label>
                                    <input type="text" class="form-control" id="publisher" name="publisher" value="<?php echo htmlspecialchars($news['publisher']); ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($news['date']); ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" disabled><?php echo htmlspecialchars($news['description']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Photo</label>
                                    <img src="<?php echo htmlspecialchars($news['photo']); ?>" alt="News Photo" style="max-width: 100px;">
                                </div>
                                <div class="mb-3">
                                    <label for="embed" class="form-label">Link</label>
                                    <textarea class="form-control" id="embed" name="embed" rows="2" disabled readonly><?php echo htmlspecialchars($news['embed']); ?></textarea>
                                </div>
                                <a href="admin_managenews.php" class="btn btn-secondary">Back</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </footer>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
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
