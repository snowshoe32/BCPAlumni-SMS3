<?php
session_start();
include "db_conn.php";
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Fetch user details based on the email
    $check_user = "SELECT * FROM `bcp_sms3_user` WHERE email = '$email'";
    $result = mysqli_query($conn, $check_user);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $name = $row['name'];

        if ($row['is_verified'] == 0) {
            $error[] = 'Your account is not verified. Please check your email.';
        } else {
            if (password_verify($password, $row['password'])) {
                // Generate 6-digit code for token2
                $code = rand(100000, 999999);
                $token2 = $code;
                $token = bin2hex(random_bytes(16));
                $_SESSION['2fa_user'] = $row;
                $_SESSION['2fa_code'] = $code; // Store the code in the session

                // Store token, token2, and code in the database
                $user_id = $row['id'];
                $update_token = "UPDATE `bcp_sms3_user` SET token='$token', token2='$token2' WHERE id='$user_id'";
                mysqli_query($conn, $update_token);

                try {
                    //Server settings
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'snowshoe0103@gmail.com'; // SMTP username
                    $mail->Password   = 'bvoa zaxb ugki vtwm'; // SMTP password (use App Password if 2-Step Verification is enabled)
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    //Recipients
                    $mail->setFrom('snowshoe0103@gmail.com', 'Mailer');
                    $mail->addAddress($email, $name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your 2FA Code';
                    $mail->Body    = "Hi $name,<br><br>Your 2FA code is: <strong>$code</strong>.<br><br>Please enter this code on the verification page to complete your login.<br><br>Thank you,<br>Alumni Management System";

                    $mail->send();
                    $_SESSION['success'] = 'Two-Factor Authenticator Code has been sent to Email.';
                    echo "Two-Factor Authenticator Code has been sent to Email.";
                    header("Location: verify_2fa.php");
                    exit();
                } catch (Exception $e) {
                    $error[] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $error[] = 'Incorrect password!';
            }
        }
    } else {
        $error[] = 'No user found with this email!';
    }
}

// Display error messages, if any
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

#yourEmail, #yourPassword {
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

.forgot-password a:hover, .register-link a:hover {
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
      
        <form id="loginForm" action="index.php" method="post" autocomplete="off">
            <label for="yourEmail">Email</label>
            <input type="text" id="yourEmail" name="email" required aria-label="Email" autocomplete="new-password">

            <label for="yourPassword">Password</label>
            <input type="password" id="yourPassword" name="password" required aria-label="Password" autocomplete="new-password">

            <div class="forgot-password">
                <a href="forgot_pass.php" aria-label="Forgot password?">Forgot your password?</a>
            </div>

           

            <button class="btn btn-primary w-100" type="submit" name="submit">LOGIN</button>
        </form>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
