<?php

include "db_conn.php"; 
session_start();

if (isset($_POST['submit'])) {
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];  // Input password (not hashed)

    // Check if user exists based on the username
    $check_user = "SELECT * FROM `bcp_sms3_user` WHERE username = '$username'";
    $result = mysqli_query($conn, $check_user);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        // Verifying hashed password with input password
        if (password_verify($password, $row['password'])) {
            // Set session based on user type
            if ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                header('location:admin_dashboard.php');  
            } else if ($row['user_type'] == 'user') {
                $_SESSION['user_name'] = $row['name'];
                header('location:user_dashboard.php');  
            }
        } else {
            // Incorrect password
            $error[] = 'Incorrect password!';
        }
    } else {
        // Username does not exist
        $error[] = 'No user found with this username!';
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

#accountId, #password {
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

.forgot-password {
    text-align: right;
    margin-bottom: 20px;
}

.forgot-password a {
    color: #007BFF;
    text-decoration: none;
    font-size: 12px;
}

.forgot-password a:hover {
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
    <?php if (!empty($error)): ?>
        <div class="error-message" style="color: red;">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <form id="loginForm" action="index.php" method="post">
        <label for="yourUsername">Account ID</label>
        <input type="text" id="yourUsername" name="username" required aria-label="Username">

        <label for="yourPassword">Password</label>
        <input type="password" id="yourPassword" name="password" required aria-label="Password">

        <div class="forgot-password">
            <a href="forgot_pass.php" aria-label="Forgot password?">Forgot your password?</a>
        </div>

        <button class="btn btn-primary w-100" type="submit" name="submit">LOGIN</button>
    </form>
</div>

    <script src="js/script.js"></script>
</body>
</html>