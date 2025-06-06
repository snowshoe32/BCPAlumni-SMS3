<?php

include "db_conn.php"; 
require 'vendor/autoload.php'; // Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (isset($_GET['success'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_GET['success']) . "</div>";
}

if (isset($_POST['submit'])) {
    $student_no = mysqli_real_escape_string($conn, $_POST['student_no']);
    $password = $_POST['password'];  // Input password (not hashed)

    // Check if user exists based on the student number
    $check_user = "SELECT * FROM `bcp-sms3_alumnidata` WHERE student_no = '$student_no'";
    $result = mysqli_query($conn, $check_user);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if (password_verify($password, $row['password'])) {
            if ($row['user_type'] == 'alumni') {
                $_SESSION['alumni_name'] = $row['student_no']; // Store student number in session
                header("Location: alumni_dashboard.php");
                exit();
            } else {
                $error[] = 'Access restricted to alumni only!';
            }
        } else {
            $error[] = 'Incorrect password!';
        }
    } else {
        $error[] = 'No user found with this student number!';
    }
}

if (isset($error)) {
    foreach ($error as $msg) {
        echo "<div class='alert alert-danger'>$msg</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Alumni Management System.">
    <title>Login - AMS</title>
</head>
<style>
    body {
    background-color: #f4f4f4;
    /*background-image: url('assets/img/bcp\ bg.jpg');*/
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
}

.logo {
    text-align: center;
    margin-bottom: 5px;
}

.logo img {
    max-width: 30%;
    height: auto;
}

.logo p {
    margin-top: 10px;
    font-size: 1.2em;
    color: #333; 
}

.login-container {  
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
    border: 1px solid #999797;
    margin: 0 auto;
}

h2 {
    color: #333;
    margin-bottom: 20px;
}

label {
    display: block;
    text-align: left;
    color: #333;
    margin: 10px 0 5px;
}

#studentNumber, #password {
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border: 1px solid black;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

.forgot-password, .register-link {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.forgot-password a, .register-link a {
    color: #007BFF;
    text-decoration: none;
    font-size: 12px;
}

forgot-password a:hover, .register-link a:hover {
    text-decoration: underline;
}

button {
    background-color: #333;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    width: 100%;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #555;
}
</style>
<body>
    <div class="logo">
        <img src="https://elc-public-images.s3.ap-southeast-1.amazonaws.com/bcp-olp-logo-mini2.png" alt="Logo">
        <p>Alumni Management System</p> 
    </div>
    
    <div class="login-container">
        <h2>Log Into Your Account</h2>
      
           
        <form id="loginForm" action="index2.php" method="post" autocomplete="off">
            <label for="studentNumber">Student Number</label>
            <input type="text" id="studentNumber" name="student_no" required aria-label="Student Number" pattern="\d{1,8}" autocomplete="new-password" title="Please enter a valid student number (up to 8 digits)" maxlength="8" inputmode="numeric">

            <label for="password">Password</label>
            <input type="password" id="yourPassword" name="password" required aria-label="Password" autocomplete="new-password">

            <div class="forgot-password">
                <a href="forgot_pass.php" aria-label="Forgot password?">Forgot your password?</a>
            </div>

            <div class="register-link">
                <a href="pages-register.php" aria-label="Register">Don't have an account? Register here</a>
            </div>

            <button class="btn btn-primary w-100" type="submit" name="submit">LOGIN</button>
        </form>
    </div>

    <script src="js/script.js"></script>
</body>
</html>