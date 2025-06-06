<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}

include "db_conn.php";

// Check if admin_name is set, otherwise use super_admin_name
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : (isset($_SESSION['super_admin_name']) ? $_SESSION['super_admin_name'] : 'Guest'); // Added fallback


// --- Generate Report Logic ---
// Check if the request is POST and the hidden field for selected students is set
// THIS PHP BLOCK REMAINS THE SAME - IT HANDLES THE ACTUAL CSV GENERATION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_students']) && isset($_POST['action']) && $_POST['action'] === 'generate_report') { // Added action check

    // Check if any students were selected
    if (isset($_POST['selected_students']) && !empty($_POST['selected_students'])) {
        $selected_students_str = $_POST['selected_students'];
        $selected_students_array = explode(',', $selected_students_str);

        // Sanitize the student numbers for the SQL query
        $sanitized_students = [];
        foreach ($selected_students_array as $student_no) {
            // Trim whitespace and ensure it's not empty before escaping
            $trimmed_student_no = trim($student_no);
            if (!empty($trimmed_student_no)) {
                $sanitized_students[] = "'" . mysqli_real_escape_string($conn, $trimmed_student_no) . "'";
            }
        }


        if (!empty($sanitized_students)) {
            $filename = "student_data_report_" . date('Ymd') . ".csv";
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=\"$filename\""); // Use double quotes for filename
            $output = fopen('php://output', "w");

            // Write CSV Header
            fputcsv($output, ['Student Number', 'Last Name', 'First Name', 'Middle Name', 'Address', 'Contact', 'Course', 'Birthday', 'Year Graduated', 'Email']);

            // Construct the query to fetch only selected students
            $query = "SELECT `student_no`, `lname`, `fname`, `mname`, `Address`, `contact`, `course`, `birthday`, `yearGraduated`, `email` FROM `bcp_sms3_alumnidata1` WHERE `student_no` IN (" . implode(',', $sanitized_students) . ")";

            $result = mysqli_query($conn, $query);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    fputcsv($output, $row);
                }
            } else {
                // Log error or handle appropriately if query fails
                // For now, just exit to prevent broken CSV
                error_log("CSV Generation Error: " . mysqli_error($conn));
                fclose($output); // Close the stream before exiting
                exit();
            }

            fclose($output);
            exit(); // IMPORTANT: Stop script execution after sending the file
        } else {
            $_SESSION['report_error'] = "No valid students selected for the report.";
            header('Location: student-data.php?msg=No valid students selected'); // Redirect back with error
            exit();
        }
    } else {
        // This case should ideally be caught by JavaScript, but keep as a fallback
        $_SESSION['report_error'] = "Please select at least one student to generate the report.";
        header('Location: student-data.php?msg=Please select students for the report'); // Redirect back with error
        exit();
    }
}
// --- End Generate Report Logic ---


// Fetch data for display (existing code)
$query = "SELECT `student_no`, `lname`, `fname`, `mname`, `Address`, `contact`, `course`, `birthday`, `yearGraduated`, `email` FROM `bcp_sms3_alumnidata1` WHERE 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error fetching data from database.";
    exit();
}

$students_data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $students_data[] = $row;
}

// Count total alumni data (existing code)
$query_count = "SELECT COUNT(*) as total_alumni FROM `bcp_sms3_alumnidata1`";
$result_count = mysqli_query($conn, $query_count);

if ($result_count) {
    $row_count = mysqli_fetch_assoc($result_count);
    $_SESSION['total_alumni'] = $row_count['total_alumni'];
} else {
    $_SESSION['total_alumni'] = 0;
}

// Display session error message if redirected
$report_error_msg = '';
if (isset($_SESSION['report_error'])) {
    $report_error_msg = $_SESSION['report_error'];
    unset($_SESSION['report_error']); // Clear the message after displaying
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Dashboard - Alumni Data</title> <!-- Updated Title -->
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

        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($admin_name); // Sanitize output ?></span>
      </a><!-- End Profile Iamge Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
          <h6><?php echo htmlspecialchars($admin_name); // Sanitize output ?></h6>
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
   <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">
    <!-- Removed LC -->
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

</ul>
</aside><!-- End Sidebar-->

    <main id="main" class="main">
      <div class="pagetitle">
        <h1>Alumni Data</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
            <li class="breadcrumb-item">Alumni Data</li>
            <li class="breadcrumb-item active">Manage Alumni Data</li>
          </ol>
        </nav>
      </div>
      <!-- End Page Title -->

      <section class="section">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Alumni Data</h5>
                <p>
                 <!-- Optional description -->
                </p>

                <!-- Table with stripped rows -->
                <div class="table container-table">
                  <?php
                  // Display general messages
                  if(isset($_GET['msg'])){
                    $msg = $_GET['msg'];
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                   '.htmlspecialchars($msg).'
                   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  }
                  // Display specific report error message
                  if(!empty($report_error_msg)){
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                   '.htmlspecialchars($report_error_msg).'
                   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                  }
                  ?>
                  <div class="d-flex mb-3">
                    <a href="add.php" class="btn btn-dark me-2">Add New</a>
                    <!-- Email Form - Trigger Modal -->
                    <form method="POST" action="send_update_email.php" style="display: inline;" id="emailForm">
                        <input type="hidden" name="selected_students" id="selected_students_email">
                        <button type="button" class="btn btn-primary text-white me-2" style="background-color: #007bff;" onclick="showSendEmailModal()">Send Update Email</button>
                    </form>
                    <!-- Report Form - Trigger Modal -->
                    <form method="POST" action="" id="reportForm" style="display: inline;">
                        <input type="hidden" name="selected_students" id="selected_students_report">
                        <input type="hidden" name="action" value="generate_report"> <!-- Add action identifier -->
                        <button type="button" class="btn btn-success text-white me-2" style="background-color: #28a745;" onclick="showGenerateReportModal()">Generate Report</button>
                    </form>
                    <button onclick="printSelected()" class="btn btn-secondary">Print Selected Data</button>
                    <span style="width: 10px; display: inline-block;"></span> <!-- Added gap -->
                    <!-- Delete Button - Trigger Modal -->
                    <button type="button" class="btn btn-danger" onclick="showDeleteModal()">Delete Selected Data</button>
                    <button onclick="window.location.href='student-data1.php'" class="btn btn-info">Student Data (SIS)</button>
                  </div>

                  <!-- Filters for Batch and Course -->
                  <form method="GET" action="" class="mb-3">
                    <select name="batch_filter" class="form-select" style="width: auto; display: inline-block;">
                      <option value="">Select Batch</option>
                      <?php
                      // Re-fetch distinct batches for the filter dropdown
                      $batch_query = "SELECT DISTINCT `yearGraduated` FROM `bcp_sms3_alumnidata1` ORDER BY `yearGraduated` DESC"; // Order descending for recent years first
                      $batch_result = mysqli_query($conn, $batch_query);
                      while ($batch_row = mysqli_fetch_assoc($batch_result)) {
                          $selected = isset($_GET['batch_filter']) && $_GET['batch_filter'] == $batch_row['yearGraduated'] ? 'selected' : '';
                          echo "<option value='".htmlspecialchars($batch_row['yearGraduated'])."' $selected>".htmlspecialchars($batch_row['yearGraduated'])."</option>";
                      }
                      ?>
                    </select>
                    <select name="course_filter" class="form-select" style="width: auto; display: inline-block;">
                      <option value="">Select Course</option>
                      <?php
                      // Re-fetch distinct courses for the filter dropdown
                      $course_query = "SELECT DISTINCT `course` FROM `bcp_sms3_alumnidata1` ORDER BY `course`";
                      $course_result = mysqli_query($conn, $course_query);
                      while ($course_row = mysqli_fetch_assoc($course_result)) {
                          $selected = isset($_GET['course_filter']) && $_GET['course_filter'] == $course_row['course'] ? 'selected' : '';
                          echo "<option value='".htmlspecialchars($course_row['course'])."' $selected>".htmlspecialchars($course_row['course'])."</option>";
                      }
                      ?>
                    </select>
                    <button type="submit" class="btn btn-info">Filter</button>
                    <a href="student-data.php" class="btn btn-outline-secondary">Clear Filters</a>
                    <button type="button" class="btn btn-outline-primary" onclick="selectAllRows()">Select All</button>
                    <button type="button" class="btn btn-outline-danger" onclick="unselectAllRows()">Unselect All</button> <!-- Added Unselect All button -->
                  </form>

                <table class="table datatable table-hover text-center">
                  <thead class="table">
                    <tr>
                      <th scope="col">Select</th>
                      <th scope="col">Student Number</th>
                      <th scope="col">Name</th>
                      <th scope="col">Contact</th>
                      <th scope="col">Course</th>
                      <th scope="col">Birthday</th>
                      <th scope="col">Batch</th>
                      <th scope="col">Email</th>
                      <th scope="col">Action</th> <!-- Action column -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Apply filters to the main data query
                    $batch_filter = isset($_GET['batch_filter']) ? mysqli_real_escape_string($conn, $_GET['batch_filter']) : '';
                    $course_filter = isset($_GET['course_filter']) ? mysqli_real_escape_string($conn, $_GET['course_filter']) : '';
                    $filter_query = "SELECT `student_no`, `lname`, `fname`, `mname`, `Address`, `contact`, `course`, `birthday`, `yearGraduated`, `email` FROM `bcp_sms3_alumnidata1` WHERE 1";

                    if (!empty($batch_filter)) {
                        $filter_query .= " AND `yearGraduated` = '$batch_filter'";
                    }
                    if (!empty($course_filter)) {
                        $filter_query .= " AND `course` = '$course_filter'";
                    }
                    $filter_query .= " ORDER BY `lname`, `fname`"; // Added ordering

                    $filter_result = mysqli_query($conn, $filter_query);

                    if ($filter_result && mysqli_num_rows($filter_result) > 0) { // Check if there are results
                        while ($row = mysqli_fetch_assoc($filter_result)) {
                            $formatted_date = !empty($row['birthday']) ? date("m/d/Y", strtotime($row['birthday'])) : 'N/A'; // Handle potential NULL dates
                            $middle_initial = !empty($row['mname']) ? strtoupper(substr($row['mname'], 0, 1)) . '.' : ''; // Safer middle initial handling
                            $full_name = trim(htmlspecialchars($row['fname']) . ' ' . $middle_initial . ' ' . htmlspecialchars($row['lname']));
                    ?>
                      <tr>
                        <td><input type="checkbox" class="row-checkbox" value="<?php echo htmlspecialchars($row['student_no']); ?>"></td>
                        <td><?php echo htmlspecialchars($row['student_no']); ?></td>
                        <td><?php echo $full_name; ?></td>
                        <td><?php echo htmlspecialchars($row['contact']); ?></td>
                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                        <td><?php echo $formatted_date; ?></td>
                        <td><?php echo htmlspecialchars($row['yearGraduated']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                          <a href="viewdata.php?student_no=<?php echo htmlspecialchars($row['student_no']); ?>" class="text-dark">
                            <i class="bx bx-show" style="font-size: 1.5rem;"></i> <!-- Increased icon size -->
                          </a>
                          <a href="edit.php?student_no=<?php echo htmlspecialchars($row['student_no']); ?>" class="text-dark">
                            <i class="bx bx-edit" style="font-size: 1.5rem;"></i> <!-- Increased icon size -->
                          </a>
                        </td>
                      </tr>
                    <?php
                        }
                    } else {
                        // Display a message if no data matches the filter
                        echo '<tr><td colspan="9" class="text-center">No alumni data found matching the criteria.</td></tr>';
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

    <!-- Modals -->
    <!-- Generate Report Modal -->
    <div class="modal fade" id="generateReportModal" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="generateReportModalLabel">Generate Report</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to generate a report for the selected students?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success" id="confirmGenerateReportButton">Generate</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Selected Data Modal -->
    <div class="modal fade" id="deleteSelectedModal" tabindex="-1" aria-labelledby="deleteSelectedModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteSelectedModalLabel">Delete Selected Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete the selected students? This action cannot be undone.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Send Update Email Modal -->
    <div class="modal fade" id="sendUpdateEmailModal" tabindex="-1" aria-labelledby="sendUpdateEmailModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="sendUpdateEmailModalLabel">Send Update Email</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to send update emails to the selected students?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="confirmSendEmailButton">Send</button> <!-- Changed ID and type -->
          </div>
        </div>
      </div>
    </div>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
      <div class="copyright">
        &copy; Copyright <strong><span>Bestlink College of the Philippines</span></strong
        >. All Rights Reserved
      </div>
      <div class="credits">
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
    <script>
    // --- Helper Function ---
    function getSelectedStudentNumbers() {
        const selected = [];
        document.querySelectorAll('.row-checkbox:checked').forEach(checkbox => {
            selected.push(checkbox.value);
        });
        return selected.join(',');
    }

    // --- Generate Report ---
    function showGenerateReportModal() {
        const selectedStudents = getSelectedStudentNumbers();
        if (!selectedStudents) {
            alert('Please select at least one student to generate the report.');
            return; // Stop if nothing is selected
        }
        // Populate the hidden field before showing the modal
        document.getElementById('selected_students_report').value = selectedStudents;
        // Show the modal
        const reportModal = new bootstrap.Modal(document.getElementById('generateReportModal'));
        reportModal.show();
    }

    // Handle generate report confirmation
    document.getElementById('confirmGenerateReportButton').addEventListener('click', function () {
        // The hidden field is already populated by showGenerateReportModal
        // Just submit the form
        document.getElementById('reportForm').submit();

        // Hide the modal after submitting
        const reportModal = bootstrap.Modal.getInstance(document.getElementById('generateReportModal'));
        if (reportModal) {
            reportModal.hide();
        }
    });

    // --- Delete Selected ---
    function showDeleteModal() {
        const selectedStudents = getSelectedStudentNumbers();
        if (!selectedStudents) {
            alert('Please select at least one student to delete.');
            return; // Stop if nothing is selected
        }
        // Show the modal
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteSelectedModal'));
        deleteModal.show();
    }

    // Handle delete confirmation
    document.getElementById('confirmDeleteButton').addEventListener('click', function () {
        const selectedStudents = getSelectedStudentNumbers(); // Get again just in case

        if (!selectedStudents) {
            alert('Please select at least one student to delete.');
            // Optionally hide the modal again
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteSelectedModal'));
            if (deleteModal) {
                deleteModal.hide();
            }
            return;
        }

        // Proceed with the delete action by creating and submitting a form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'delete_students.php'; // Ensure this PHP script handles deletion
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'selected_students';
        input.value = selectedStudents;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    });

    // --- Send Update Email ---
    function showSendEmailModal() {
        const selectedStudents = getSelectedStudentNumbers();
        if (!selectedStudents) {
            alert('Please select at least one student to send email.');
            return; // Stop if nothing is selected
        }
        // Populate the hidden field before showing the modal
        document.getElementById('selected_students_email').value = selectedStudents;
        // Show the modal
        const emailModal = new bootstrap.Modal(document.getElementById('sendUpdateEmailModal'));
        emailModal.show();
    }

    // Handle send email confirmation
    document.getElementById('confirmSendEmailButton').addEventListener('click', function () {
        // The hidden field is already populated by showSendEmailModal
        // Just submit the form
        document.getElementById('emailForm').submit();
    });


    // --- Print Selected ---
    function printSelected() {
        const selectedRows = document.querySelectorAll('.row-checkbox:checked');
        if (selectedRows.length === 0) {
            alert('Please select at least one student to print.');
            return;
        }

        // Create a new table for printing
        const printTable = document.createElement('table');
        printTable.className = 'table table-bordered table-striped'; // Add some basic styling
        printTable.style.width = '100%';
        printTable.setAttribute('border', '1'); // Ensure borders are visible in print

        // Clone the header
        const header = document.querySelector('.table thead').cloneNode(true);
        // Remove the checkbox header cell
        if (header.rows.length > 0 && header.rows[0].cells.length > 0) {
            header.rows[0].deleteCell(0);
        }
        printTable.appendChild(header);

        // Create and append the body
        const tbody = document.createElement('tbody');
        selectedRows.forEach(checkbox => {
            const originalRow = checkbox.closest('tr');
            const printRow = originalRow.cloneNode(true);
            // Remove the checkbox cell from the cloned row
            if (printRow.cells.length > 0) {
                printRow.deleteCell(0);
            }
            tbody.appendChild(printRow);
        });
        printTable.appendChild(tbody);

        // Open a new window and print
        const printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write('<html><head><title>Print Selected Alumni Data</title>');
        // Optional: Add basic CSS for printing
        printWindow.document.write('<style> body { font-family: sans-serif; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid black; padding: 8px; text-align: left; } </style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2>Selected Alumni Data</h2>');
        printWindow.document.write(printTable.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close(); // Necessary for IE >= 10
        printWindow.focus(); // Necessary for IE >= 10

        // Use a timeout to ensure content is loaded before printing
        setTimeout(() => {
             printWindow.print();
             // printWindow.close(); // Optional: Close window after print
        }, 500); // Adjust timeout as needed
    }

    // --- Select/Unselect All ---
    function selectAllRows() {
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    function unselectAllRows() {
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
    }

  </script>
  </body>
  </html>
