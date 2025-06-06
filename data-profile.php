<?php
session_start();
include 'db_conn.php';

// Check if user is logged in and is an alumni
if (isset($_SESSION['alumni_name'])) {
    $alumni_name = $_SESSION['alumni_name'];
} else {
    // Redirect to login page if not logged in as alumni
    header("Location: index2.php"); // Changed to index2.php
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle profile update
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $mname = mysqli_real_escape_string($conn, $_POST['mname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $job = mysqli_real_escape_string($conn, $_POST['job']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $about = mysqli_real_escape_string($conn, $_POST['about']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $twitter_link = mysqli_real_escape_string($conn, $_POST['twitter_link']);
    $fb_link = mysqli_real_escape_string($conn, $_POST['fb_link']);
    $ig_link = mysqli_real_escape_string($conn, $_POST['ig_link']);
    $linked_link = mysqli_real_escape_string($conn, $_POST['linked_link']);


    // Handle profile photo upload
    $photo_path = isset($row['photo']) ? $row['photo'] : ''; // Default to existing photo or empty string
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/profile_photos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $photo_name = basename($_FILES['profile_photo']['name']);
        // Delete the old photo if it exists and is not the default
        if (!empty($photo_path) && $photo_path !== 'assets/img/profile-img.jpg' && file_exists($photo_path)) {
            unlink($photo_path);
        }

        $target_file = $upload_dir . uniqid() . '_' . $photo_name;
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_file)) {
            $photo_path = $target_file;
        } else {
            echo "<script>alert('Error uploading photo.');</script>";
        }
    }


    $update_sql = "UPDATE `bcp-sms3_alumnidata` SET fname='$fname', mname='$mname', lname='$lname', gender='$gender', email='$email', contact='$contact', birthdate='$birthdate', address='$address', job='$job', country='$country', about='$about', company='$company', twitter_link='$twitter_link', fb_link='$fb_link', ig_link='$ig_link', linked_link='$linked_link', photo='$photo_path' WHERE student_no='$alumni_name'";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('successModal'));
                modal.show();
            });
        </script>";
    } else {
        echo "<script>alert('Error updating profile: " . mysqli_error($conn) . "');</script>";
    }
}


// Fetch alumni details using prepared statement for security
$stmt = $conn->prepare("SELECT * FROM `bcp-sms3_alumnidata` WHERE student_no = ?");
$stmt->bind_param("s", $alumni_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $alumni_fname = htmlspecialchars($row['fname']); // Display first name safely
    $alumni_email = $row['email'];
    $password = $row['password'];
    $birthdate = isset($row['birthdate']) ? $row['birthdate'] : '';
    $photo = $row['photo'];
    $user_type = $row['user_type'];
    $is_verified = $row['is_verified'];
   
    $about = $row['about'];
    $company = $row['company'];
    $twitter_link = isset($row['twitter_link']) ? $row['twitter_link'] : '';
    $fb_link = isset($row['fb_link']) ? $row['fb_link'] : '';
    $ig_link = isset($row['ig_link']) ? $row['ig_link'] : '';
    $linked_link = isset($row['linked_link']) ? $row['linked_link'] : '';

    $photo = isset($row['photo']) ? $row['photo'] : '';

} else {
    echo "No alumni found with the student number: " . htmlspecialchars($alumni_name);
    exit(); // Stop execution if no user is found
}
$stmt->close();
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
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $alumni_fname; ?></span>
          </a><!-- End Profile Iamge Icon -->

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
              <hr class="dropdown-divider">
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

    <hr class="sidebar-divider">

    <li class="nav-item">
      <a class="nav-link " href="career.php" class="active">
        <i class="bi bi-grid"></i>
        <span>Career Opportunities</span>
      </a>
    </li><!-- Career Opportunities Nav -->

    <hr class="sidebar-divider">

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-layout-text-window-reverse"></i><span>Student Alumni Services</span><i class="bi bi-chevron-down ms-auto"></i>
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
  </ul>

</aside><!-- End Sidebar -->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="<?php echo htmlspecialchars($row['photo'] ? $row['photo'] : 'assets/img/profile-img.jpg'); ?>" alt="Profile" class="rounded-circle">
              <h2 class="text-center"><?php echo htmlspecialchars($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']); ?></h2>
              <!-- Removed Web Designer -->
              <div class="social-links mt-2">
                <a href="<?php echo htmlspecialchars($twitter_link); ?>" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="<?php echo htmlspecialchars($fb_link); ?>" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="<?php echo htmlspecialchars($ig_link); ?>" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="<?php echo htmlspecialchars($linked_link); ?>" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
                <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">Profile Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname']); ?></div>
                  </div> 

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Gender</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row['gender']); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row['email']); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Phone</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row['contact']); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Birthdate</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row['birthdate']); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Address</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row['address']); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Job</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row['job']); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Company</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row['company']); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Country</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row['country']); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">About</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars(substr($row['about'], 0, 200)); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Twitter</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($twitter_link); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Facebook</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($fb_link); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Instagram</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($ig_link); ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">LinkedIn</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($linked_link); ?></div>
                  </div>
                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                
                  <!-- Profile Edit Form -->
                  <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9 position-relative text-center">
                        <img id="profilePreview" 
                             src="<?php echo htmlspecialchars($row['photo'] ? $row['photo'] : 'assets/img/profile-img.jpg'); ?>" 
                             alt="Profile" 
                             class="rounded-circle" 
                             style="width: 100px; height: 100px; cursor: pointer;" 
                             onclick="document.getElementById('profilePhotoInput').click();">
                        <input type="file" name="profile_photo" id="profilePhotoInput" class="form-control d-none" 
                               onchange="previewProfilePhoto(event)">
                        <input type="hidden" name="photo" value="<?php echo htmlspecialchars($row['photo']); ?>">
                        <input type="hidden" name="current_photo" value="<?php echo htmlspecialchars($row['photo']); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fname" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="fname" type="text" class="form-control" id="fname" value="<?php echo htmlspecialchars($row['fname']); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="mname" class="col-md-4 col-lg-3 col-form-label">Middle Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="mname" type="text" class="form-control" id="mname" value="<?php echo htmlspecialchars($row['mname']); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="lname" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="lname" type="text" class="form-control" id="lname" value="<?php echo htmlspecialchars($row['lname']); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="gender" class="col-md-4 col-lg-3 col-form-label">Gender</label>
                      <div class="col-md-8 col-lg-9">
                        <select name="gender" class="form-control" id="gender">
                          <option value="Male" <?php echo $row['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                          <option value="Female" <?php echo $row['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                          
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                      <div class="col-md-8 col-lg-9">
                        <textarea name="about" class="form-control" id="about" style="height: 100px"><?php echo htmlspecialchars($row['about']); ?></textarea>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="company" class="col-md-4 col-lg-3 col-form-label">Company</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="company" type="text" class="form-control" id="company" value="<?php echo htmlspecialchars($row['company']); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">Job</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="job" type="text" class="form-control" id="Job" value="<?php echo htmlspecialchars($row['job']); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Country" class="col-md-4 col-lg-3 col-form-label">Country</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="country" type="text" class="form-control" id="Country" value="<?php echo htmlspecialchars($row['country']); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="address" type="text" class="form-control" id="Address" value="<?php echo htmlspecialchars($row['address']); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Contact" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="contact" type="text" class="form-control" id="Contact" value="<?php echo htmlspecialchars($row['contact']); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="<?php echo htmlspecialchars($row['email']); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Birthdate" class="col-md-4 col-lg-3 col-form-label">Birthdate</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="birthdate" type="date" class="form-control" id="Birthdate" value="<?php echo htmlspecialchars($birthdate); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="twitter_link" type="text" class="form-control" id="Twitter" value="<?php echo htmlspecialchars($twitter_link); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="fb_link" type="text" class="form-control" id="Facebook" value="<?php echo htmlspecialchars($fb_link); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="ig_link" type="text" class="form-control" id="Instagram" value="<?php echo htmlspecialchars($ig_link); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin Profile</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="linked_link" type="text" class="form-control" id="Linkedin" value="<?php echo htmlspecialchars($row['linked_link']); ?>">
                      </div> 
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                      <button type="button" class="btn btn-secondary" onclick="location.reload();">Reset</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

              </div><!-- End Bordered Tabs -->

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

  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="successModalLabel">Success</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Profile updated successfully!
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function previewProfilePhoto(event) {
      const reader = new FileReader();
      reader.onload = function () {
        const preview = document.getElementById('profilePreview');
        preview.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>

</body>

</html>
