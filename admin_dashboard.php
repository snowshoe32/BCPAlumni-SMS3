<?php
session_start();
include 'db_conn.php';

if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location:index.php');
    exit();
}

$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : (isset($_SESSION['super_admin_name']) ? $_SESSION['super_admin_name'] : 'Guest');

// Log user login action (Moved inside the successful login check)
$userType = isset($_SESSION['admin_name']) ? 'admin' : 'super_admin';
$studentNo = ''; // Fetch from the database if needed
$name = ''; // Fetch from the database if needed
$ipAddress = $_SERVER['REMOTE_ADDR']; // Get the user's IP address

// Check if IP address is too long
if (strlen($ipAddress) > 45) {
    $ipAddress = substr($ipAddress, 0, 45); // Truncate to 45 characters if longer
}

// Fetch user details for logging
$username = $admin_name; // Use the logged-in username
$sql_user_details = "SELECT name, student_no FROM bcp_sms3_user WHERE username = ?";
$stmt_user_details = $conn->prepare($sql_user_details);
$stmt_user_details->bind_param("s", $username);
$stmt_user_details->execute();
$result_user_details = $stmt_user_details->get_result();

if ($result_user_details && $result_user_details->num_rows > 0) {
    $user_details = $result_user_details->fetch_assoc();
    $name = $user_details['name'];
    $studentNo = $user_details['student_no'];
}
$stmt_user_details->close();

// **CHECK IF LOGGED IN ALREADY**
// Check if the user has already logged in during this session
if (!isset($_SESSION['logged_in'])) {
    // If not, log the login event and set the session variable
    if (isset($_SESSION['admin_name']) || isset($_SESSION['super_admin_name'])) {
        $sql_log = "INSERT INTO `bcp-sms3_auditlogs` (user_type, student_no, username, name, event, timestamp, resource_access, ip_address) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";
        $stmt_log = $conn->prepare($sql_log);

        $action = "Logging in";
        $resource = "System";
        $stmt_log->bind_param("sssssss", $userType, $studentNo, $username, $name, $action, $resource, $ipAddress);
        $stmt_log->execute();

        $stmt_log->close();
        $_SESSION['logged_in'] = true; // Set the session variable to indicate login has been logged
    }
}

$sql = "SELECT * FROM bcp_sms3_user WHERE username = ?";
$stmt_user = $conn->prepare($sql);
$stmt_user->bind_param("s", $admin_name);
$stmt_user->execute();
$result = $stmt_user->get_result();

if ($result) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Other logic for the admin dashboard
    } else {
        echo "No admin found with the username: " . htmlspecialchars($admin_name);
    }
} else {
    echo "MySQL Error: " . $conn->error;
}
$stmt_user->close();

// Fetch the count of alumni registered
$alumni_count_sql = "SELECT COUNT(*) as alumni_count FROM bcp_sms3_user WHERE user_type = 'alumni'";
$alumni_count_result = mysqli_query($conn, $alumni_count_sql);
$alumni_count = 0;

if ($alumni_count_result) {
    $alumni_count_row = mysqli_fetch_assoc($alumni_count_result);
    $alumni_count = $alumni_count_row['alumni_count'];
} else {
    echo "MySQL Error: " . mysqli_error($conn);
}
?>

<!-- Rest of your HTML code (unchanged) -->

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
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Alumni Donations <span>| Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="ps-3">
                      <h6>145</h6>
                      <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Events Organized <span>| This Month</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6>$3,264</h6>
                      <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Alumni Registered <span>| This Year</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $alumni_count; ?></h6>
                      <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>

                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->
          </div>
          <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Event Status<span>| Today</span></h5>

            
              <canvas id="pieChart" style="max-height: 400px;"></canvas>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#pieChart'), {
                    type: 'pie',
                    data: {
                      labels: [
                        'Cancelled',
                        'Upcoming',
                        'Ongoing',
                        'Ended'
                      ],
                      datasets: [{
                        label: 'Event Status',
                        data: [100, 50, 130, 250],
                        backgroundColor: [
                          'rgb(255, 99, 132)',
                          'rgb(54, 162, 235)',
                          'rgb(255, 205, 86)',
                          'rgb(198, 198, 198)'
                        ],
                        hoverOffset: 4
                      }]
                    }
                  });
                });
              </script>
              </div>
          </div>
        </div>
        </div><!-- End Left side columns -->
       

<!--recent activity -->
<div class="col-xxl-4 col-xl-12 h-200">
        <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Recent Activity <span>| Today</span></h5>

              <div class="activity">
                <?php
                include 'db_conn.php';
                $sql = "SELECT user_type, username, event, timestamp FROM `bcp-sms3_auditlogs` ORDER BY timestamp DESC LIMIT 10";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($log = $result->fetch_assoc()) {
                        $action_class = '';
                        switch ($log['event']) {
                            case 'Logging in':
                                $action_class = 'text-success';
                                break;
                            case 'Submitting an ID application':
                                $action_class = 'text-info';
                                break;
                            case 'Applying for alumni tracer':
                                $action_class = 'text-primary';
                                break;
                            case 'Editing data':
                                $action_class = 'text-warning';
                                break;
                            case 'Deleting data':
                                $action_class = 'text-danger';
                                break;
                            default:
                                $action_class = 'text-muted';
                                break;
                        }
                ?>
                <div class="activity-item d-flex">
                  <div class="activite-label"><?php echo $log['timestamp']; ?></div>
                  <i class='bi bi-circle-fill activity-badge <?php echo $action_class; ?> align-self-start'></i>
                  <div class="activity-content">
                    <?php echo $log['user_type']; ?> <?php echo $log['username']; ?> performed <span class="fw-bold"><?php echo $log['event']; ?></span>
                  </div>
                </div><!-- End activity item-->
                <?php
                    }
                } else {
                    echo "<div class='activity-item d-flex'><div class='activity-content'>No recent activity found.</div></div>";
                }
                $conn->close();
                ?>
              </div>
            </div>
          </div>
        </div><!-- End Recent Activity -->

        <!-- Right side columns -->
     
         
         
        

          <!-- News & Updates Traffic -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0"></div>
              <h5 class="card-title">Recent News &amp; Updates <span>| Today</span></h5>

              <div class="news">
                <div class="post-item clearfix">
                  <img src="assets/img/news-1.jpg" alt="">
                  <h4><a href="#">Alumni from Bestlink topnotcher places 1st</a></h4>
                  <p>Sit recusandae non aspernatur laboriosam. Quia enim eligendi sed ut harum...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-2.jpg" alt="">  
                  <h4><a href="#">Quidem autem et impedit</a></h4>
                  <p>Illo nemo neque maiores vitae officiis cum eum turos elan dries werona nande...</p>
                </div>

                <div class="post-item clearfix"></div>
                  <img src="assets/img/news-3.jpg" alt="">
                  <h4><a href="#">Id quia et et ut maxime similique occaecati ut</a></h4>
                  <p>Fugiat voluptas vero eaque accusantium eos. Consequuntur sed ipsam et totam...</p>
                </div>

                <div class="post-item clearfix"></div>
                  <img src="assets/img/news-4.jpg" alt="">
                  <h4><a href="#">Laborum corporis quo dara net para</a></h4>
                  <p>Qui enim quia optio. Eligendi aut asperiores enim repellendusvel rerum cuder...</p>
                </div>

                <div class="post-item clearfix"></div>
                  <img src="assets/img/news-5.jpg" alt="">
                  <h4><a href="#">Et dolores corrupti quae illo quod dolor</a></h4>
                  <p>Odit ut eveniet modi reiciendis. Atque cupiditate libero beatae dignissimos eius...</p>
                </div>

              </div><!-- End sidebar recent posts-->

            </div>
          </div><!-- End News & Updates -->
          </div>
        <!-- End Right side columns -->

      </div>
    </section>

    <section class="section">
      <div class="container">
        <h2>Event Data</h2>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Date</th>
              <th>Location</th>
            </tr>
          </thead>
          <tbody id="events-table-body">
            <!-- Data will be populated here by JavaScript -->
          </tbody>
        </table>
      </div>
    </section>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
      fetch('api/events.php')
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            console.error(data.error);
          } else {
            const tableBody = document.getElementById('events-table-body');
            data.forEach(event => {
              const row = document.createElement('tr');
              row.innerHTML = `
                <td>${event.id}</td>
                <td>${event.title}</td>
                <td>${event.date}</td>
                <td>${event.location}</td>
              `;
              tableBody.appendChild(row);
            });

            // Filter and display only "Alumni" specific events for the Event Status chart
            const alumniEvents = data.filter(event => event.title.includes('Alumni'));
            const eventStatusData = {
              labels: ['Cancelled', 'Upcoming', 'Ongoing', 'Ended'],
              datasets: [{
                label: 'Event Status',
                data: [
                  alumniEvents.filter(event => event.status === 'Cancelled').length,
                  alumniEvents.filter(event => event.status === 'Upcoming').length,
                  alumniEvents.filter(event => event.status === 'Ongoing').length,
                  alumniEvents.filter(event => event.status === 'Ended').length
                ],
                backgroundColor: [
                  'rgb(255, 99, 132)',
                  'rgb(54, 162, 235)',
                  'rgb(255, 205, 86)',
                  'rgb(198, 198, 198)'
                ],
                hoverOffset: 4
              }]
            };

            new Chart(document.querySelector('#pieChart'), {
              type: 'pie',
              data: eventStatusData
            });
          }
        })
        .catch(error => console.error('Error fetching event data:', error));
    });
    </script>

    <section class="section">
      <div class="container">
        <h2>Holidays</h2>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Date</th>
              <th>Reason</th>
              <th>Booking Date</th>
            </tr>
          </thead>
          <tbody id="holidays-table-body">
            <!-- Data will be populated here by JavaScript -->
          </tbody>
        </table>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <h2>Reservations</h2>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Date</th>
              <th>Time</th>
              <th>Event Description</th>
              <th>User Count</th>
            </tr>
          </thead>
          <tbody id="reservations-table-body">
            <!-- Data will be populated here by JavaScript -->
          </tbody>
        </table>
      </div>
    </section>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
      fetch('api/holidays.php')
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            console.error(data.error);
          } else {
            const tableBody = document.getElementById('holidays-table-body');
            data.forEach(holiday => {
              const row = document.createElement('tr');
              row.innerHTML = `
                <td>${holiday.id}</td>
                <td>${holiday.date}</td>
                <td>${holiday.reason}</td>
                <td>${holiday.bdate}</td>
              `;
              tableBody.appendChild(row);
            });
          }
        })
        .catch(error => console.error('Error fetching holidays data:', error));

      fetch('api/reservations.php')
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            console.error(data.error);
          } else {
            const tableBody = document.getElementById('reservations-table-body');
            data.forEach(reservation => {
              const row = document.createElement('tr');
              row.innerHTML = `
                <td>${reservation.rdate}</td>
                <td>${reservation.rtime}</td>
                <td>${reservation.event_description}</td>
                <td>${reservation.ucount}</td>
              `;
              tableBody.appendChild(row);
            });
          }
        })
        .catch(error => console.error('Error fetching reservations data:', error));
    });
    </script>

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

