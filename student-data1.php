<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}
include 'db_conn.php';

// Determine logged-in user
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : (isset($_SESSION['super_admin_name']) ? $_SESSION['super_admin_name'] : null);
if (!$admin_name) {
    header('Location: index.php');
    exit();
}

// --- Fetching Alumni Data from API ---
$apiUrl = "https://sis.bcpsms3.com/api/alumni";
$alumni = []; // Initialize alumni array

// Initialize cURL session
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// --- Optional but Recommended for HTTPS ---
// If you suspect SSL issues, uncomment the next line TEMPORARILY for diagnosis.
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // !!! Less secure - for testing only !!!
// --- End Optional HTTPS Settings ---

curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

// Execute cURL request
$response = curl_exec($ch);
$curl_error_no = curl_errno($ch); // Store error number before closing
$curl_error_message = curl_error($ch); // Store error message before closing

// Close cURL session
curl_close($ch); // Close handle *after* getting error info

// --- Process API Response ---
if ($response !== null) {
    // Decode JSON response
    $decoded_response = json_decode($response, true); // Use true for associative array

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<h1>API Error</h1>"; // Make the error obvious
        echo "<p><strong>Error decoding JSON response:</strong> " . json_last_error_msg() . "</p>";
        echo "<p><strong>API URL Called:</strong> " . htmlspecialchars($apiUrl) . "</p>";
        echo "<p><strong>Raw Response from API:</strong></p>";
        echo "<pre style='border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9; white-space: pre-wrap; word-wrap: break-word;'>" . htmlspecialchars($response) . "</pre>";
        // *** ADD EXIT HERE ***
        exit; // Stop script execution immediately after showing the error and raw response
    }
    // Validate the structure and status
    elseif (!is_array($decoded_response) || !isset($decoded_response['status']) || $decoded_response['status'] !== 'success' || !isset($decoded_response['data']) || !is_array($decoded_response['data'])) {
         echo "<h1>API Error</h1>"; // Make the error obvious
         echo "<p><strong>Error:</strong> Invalid API response format or status was not 'success'. Expected structure: {'status': 'success', 'data': [...]}.</p>";
         echo "<p><strong>API URL Called:</strong> " . htmlspecialchars($apiUrl) . "</p>";
         echo "<p><strong>Raw Response from API:</strong></p>";
         echo "<pre style='border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9; white-space: pre-wrap; word-wrap: break-word;'>" . htmlspecialchars($response) . "</pre>";
         // *** ADD EXIT HERE ***
         exit; // Stop script execution immediately
    } else {
        // Assign the actual alumni data array from the 'data' key
        $alumni = $decoded_response['data'];
    }

} else {
     // Handle cURL errors specifically
     if ($curl_error_no) {
        echo "<h1>cURL Error</h1>";
        echo "<p><strong>Error fetching alumni data:</strong> (" . $curl_error_no . ") " . $curl_error_message . "</p>";
        if (strpos($curl_error_message, 'SSL certificate problem') !== false) {
            echo "<p><strong>Suggestion:</strong> There might be an issue with SSL certificate verification on your server. Check your server's CA bundle or consider cURL options like CURLOPT_SSL_VERIFYPEER (use cautiously for testing).</p>";
        }
        // *** ADD EXIT HERE ***
        exit; // Stop script execution
     } else {
        // Handle cases where $response is null but no cURL error was recorded (less common)
        echo "<h1>API Error</h1>";
        echo "<p><strong>Error:</strong> Failed to retrieve data from the API (URL: " . htmlspecialchars($apiUrl) . "). The response was empty or null.</p>";
        // *** ADD EXIT HERE ***
        exit; // Stop script execution
     }
     $alumni = []; // Ensure alumni is an empty array on error
}

// --- The rest of your HTML code follows ---
// If the script reaches here, it means the API call was successful and JSON was valid.
?>

<!-- The rest of your HTML code remains the same -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <!-- Changed Title -->
  <title>Dashboard - Alumni Data (SIS)</title>
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
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($admin_name); ?></span>
          </a><!-- End Profile Image Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo htmlspecialchars($admin_name); ?></h6>
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
              <i class="bi bi-circle"></i><span>Alumni Data </span> <!-- Clarified source -->
            </a>
          </li>
           <li>
            <a href="student-data1.php" class="active"> <!-- Link to this page - Added active class -->
              <i class="bi bi-circle"></i><span>Alumni Data (SIS)</span> <!-- Clarified source -->
            </a>
          </li>
          <li>
            <a href="add.php">
              <i class="bi bi-circle"></i><span>Add Alumni Data </span> <!-- Clarified source -->
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
           <li>
            <a href="alumni_benefits.php">
              <i class="bi bi-circle"></i><span>Alumni Benefits</span>
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
       <!-- Changed Title -->
      <h1>Student Data (SIS)</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
           <li class="breadcrumb-item">Alumni Data</li>
          <li class="breadcrumb-item active">Student Data (SIS)</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container-fluid mt-5"> <!-- Use container-fluid for wider table -->

        <h1 class="mb-4">Student Data</h1>
        <div class="table-responsive"> <!-- Added for better responsiveness on small screens -->
            <table class="table table-bordered table-striped table-hover datatable"> <!-- Added datatable class -->
                <thead class="table-dark"> <!-- Changed header style -->
                    <tr>
                        <th>ID</th>
                        <th>Student Number</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Suffix</th> <!-- Added Suffix -->
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Birthdate</th>
                        <th>Religion</th>
                        <th>Place of Birth</th>
                        <th>Current Address</th>
                        <th>Email Address</th>
                        <th>Contact Number</th>
                        <th>Enrollment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($alumni)): ?>
                        <?php foreach ($alumni as $alumnus): ?>
                            <tr>
                                <!-- Use null coalescing operator (??) for safety -->
                                <td><?php echo htmlspecialchars($alumnus['id'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['student_number'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['first_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['middle_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['last_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['suffix_name'] ?? ''); ?></td> <!-- Added Suffix, use empty string if null -->
                                <td><?php echo htmlspecialchars($alumnus['age'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['gender'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['birthdate'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['religion'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['place_of_birth'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['current_address'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['email_address'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['contact_number'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($alumnus['enrollment_status'] ?? 'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <!-- Display the error messages generated earlier if $alumni is empty -->
                            <td colspan="15" class="text-center">
                                <?php
                                // Check again for errors to display a more specific message if available
                                if (curl_errno($ch)) {
                                    echo "Failed to load data: cURL Error - " . curl_error($ch);
                                } elseif ($response === null && !curl_errno($ch)) {
                                    echo "Failed to load data: No response from API.";
                                } elseif (json_last_error() !== JSON_ERROR_NONE) {
                                    echo "Failed to load data: Invalid JSON response received from API.";
                                } elseif (isset($decoded_response) && (!is_array($decoded_response) || !isset($decoded_response['status']) || $decoded_response['status'] !== 'success' || !isset($decoded_response['data']) || !is_array($decoded_response['data']))) {
                                    echo "Failed to load data: API response format is incorrect or status is not 'success'.";
                                } else {
                                    echo "No alumni data received from the API or the data was invalid."; // Generic fallback
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
      </div>
    </section>

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
  <!-- Make sure simple-datatables is loaded -->
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  <!-- Initialize Datatable (if not already done in main.js) -->
  <script>
    // Ensure this runs after the table is in the DOM
    document.addEventListener('DOMContentLoaded', function() {
      // Check if simple-datatables is loaded
      if (typeof simpleDatatables !== 'undefined') {
        const datatables = document.querySelectorAll('.datatable');
        datatables.forEach(datatable => {
          new simpleDatatables.DataTable(datatable);
        });
      } else {
        console.error("Simple Datatables library not found.");
      }
    });
  </script>

</body>

</html>
