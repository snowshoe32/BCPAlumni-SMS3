<?php
// Check if a session is already active before starting a new one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db_conn.php'; // Your database connection

// Check if user is logged in and is an alumni
if (!isset($_SESSION['alumni_name'])) {
    header("Location: index2.php");
    exit();
}

// Payment Gateway URL
$payment_gateway_url = "https://example-payment-gateway.com"; // Replace with actual URL

// Webhook endpoint (this URL is registered with the payment gateway)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // **CRITICAL SECURITY STEP:** Verify the authenticity of the webhook data.
    // The payment gateway will provide methods to verify that the request is legitimate
    // and hasn't been tampered with.  This might involve checking a signature, HMAC, etc.
    //  If verification fails, stop processing.

    if (verify_webhook_data($data)) { // Replace with your actual verification function
        $donation_id = $data['transaction_id']; // Or whatever the gateway uses
        $donor_id = $data['donor_id'];
        $amount = $data['amount'];
        $status = $data['status'];

        $sql = "INSERT INTO donations (donation_id, donor_id, amount, transaction_id, timestamp, payment_method, status) 
                VALUES (?, ?, ?, ?, NOW(), 'GCash', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siis", $donation_id, $donor_id, $amount, $donation_id, $status);
        $stmt->execute();
        $stmt->close();

        echo "Donation recorded successfully."; // Respond to the payment gateway
    } else {
        http_response_code(400); // Bad Request - invalid webhook data
        echo "Invalid webhook data.";
    }
}

function verify_webhook_data($data) {
    // Implement your webhook data verification logic here.  This is crucial for security!
    // Example (replace with your actual verification method):
    // $expected_signature = calculate_signature($data, 'your_secret_key');
    // return $data['signature'] === $expected_signature;
    return true; // REMOVE THIS -  This is a placeholder, replace with real verification
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>GCash Donation Tracker</title>
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
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['alumni_name']; ?></span>
          </a><!-- End Profile Image Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo htmlspecialchars($_SESSION['alumni_name']); ?></h6>
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

      <li class="nav-item">
        <a class="nav-link " href="alumni_dashboard.php" class="active">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link " href="announcements.php" class="active">
          <i class="bi bi-grid"></i>
          <span>Announcements</span>
        </a>
      </li><!-- Announcements Nav -->

      <li class="nav-item">
        <a class="nav-link " href="career.php" class="active">
          <i class="bi bi-grid"></i>
          <span>Career Opportunities</span>
        </a>
      </li><!-- Career Opportunities Nav -->

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
      </li><!-- Alumni Online Services -->

    </ul>

  </aside><!-- End Sidebar -->

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>GCash Donation Tracker</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">GCash Donation Tracker</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="container">
        <?php
        session_start(); // Start the session to store user data if needed

        // Database connection details (REPLACE WITH YOUR ACTUAL CREDENTIALS)
        $servername = "localhost";
        $username = "your_actual_db_username"; // Replace with your actual database username
        $password = "your_actual_db_password"; // Replace with your actual database password
        $dbname = "your_actual_db_name"; // Replace with your actual database name

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $gcash_number = $_POST["gcash_number"];
            $amount = $_POST["amount"];
            $transaction_id = $_POST["transaction_id"];
            $donor_name = $_POST["donor_name"]; //Optional: Add donor name field

            // Prepare and bind the SQL statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO donations (gcash_number, amount, transaction_id, donor_name) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siss", $gcash_number, $amount, $transaction_id, $donor_name);

            if ($stmt->execute()) {
                $success_message = "Donation recorded successfully!";
            } else {
                $error_message = "Error recording donation: " . $stmt->error;
            }

            $stmt->close();
        }

        $conn->close();
        ?>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="post" action="donation_record.php">
            <div class="form-group">
                <label for="gcash_number">GCash Number:</label>
                <input type="text" class="form-control" id="gcash_number" name="gcash_number" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount (PHP):</label>
                <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="transaction_id">Transaction ID:</label>
                <input type="text" class="form-control" id="transaction_id" name="transaction_id" required>
            </div>
            <div class="form-group">
                <label for="donor_name">Donor Name (Optional):</label>
                <input type="text" class="form-control" id="donor_name" name="donor_name">
            </div>
            <button type="submit" class="btn btn-primary">Record Donation</button>
        </form>
      </div>
    </section>
  </main><!-- End #main -->

  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; <strong><span>Bestlink Alumni Association 2025</span></strong>. All Rights Reserved
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
