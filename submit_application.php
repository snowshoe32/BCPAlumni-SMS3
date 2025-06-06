<?php
session_start();
include 'db_conn.php';

// Check if user is logged in and is an alumni
if (!isset($_SESSION['alumni_name'])) {
    // Redirect to login page if not logged in as alumni
    header("Location: index.php");
    exit();
}

// Get form data
$last_name = $_POST['last_name'];
$first_name = $_POST['first_name'];
$middle_name = $_POST['middle_name'];
$student_no = $_POST['student_no'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$birthdate = $_POST['birthdate'];

// Check if email or student number already exists
$check_sql = "SELECT * FROM `bcp-sms3-idmanage` WHERE `email` = '$email' OR `student_no` = '$student_no'";
$check_result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    echo "<script>alert('Email or Student Number already exists.'); window.history.back();</script>";
    exit();
}

// Insert data into the database
$sql = "INSERT INTO `bcp-sms3-idmanage`(`id`, `last_name`, `first_name`, `middle_name`, `student_no`, `contact`, `email`, `birthdate`, `photo`, `status`) 
        VALUES (NULL, '$last_name', '$first_name', '$middle_name', '$student_no', '$contact', '$email', '$birthdate', '', 'Pending')";

if (mysqli_query($conn, $sql)) {
    // Redirect to alumni dashboard on success
    echo "<script>alert('Application submitted successfully!'); window.location.href='alumni_dashboard.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
