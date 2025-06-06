<?php
session_start();
include "db_conn.php";

if (!isset($_SESSION['2fa_code']) || !isset($_SESSION['2fa_user'])) {
    header('location:index.php');
    exit();
}

if (isset($_POST['verify'])) {
    $input_code = $_POST['code'];
    $user = $_SESSION['2fa_user'];
    $user_id = $user['id'];

    // Fetch token2 from the database
    $query = "SELECT token2 FROM `bcp_sms3_user` WHERE id='$user_id'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) { // Ensure $result is valid and has rows
        $row = mysqli_fetch_assoc($result);

        if ($input_code == $_SESSION['2fa_code'] || $input_code == $row['token2']) {
            // Mark is_verified2 as true
            $update_verified = "UPDATE `bcp_sms3_user` SET is_verified2=1 WHERE id='$user_id'";
            mysqli_query($conn, $update_verified);

            unset($_SESSION['2fa_code'], $_SESSION['2fa_user']);

            // Set session based on user type
            if ($user['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $user['name'];
                header('location:admin_dashboard.php');
            } else if ($user['user_type'] == 'alumni') {
                $_SESSION['alumni_name'] = $user['name'];
                header('location:alumni_dashboard.php');
            } else if ($user['user_type'] == 'super_admin') {
                $_SESSION['super_admin_name'] = $user['name'];
                header('location:admin_dashboard.php');
            }
            exit();
        } else {
            $error = "Invalid 2FA code!";
        }
    } else {
        $error = "Failed to fetch 2FA details. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify 2FA</title>
    <link rel="stylesheet" href="style.css"> <!-- Include the CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>
    <div class="login-container" style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <div class="form-box" style="width: 300px; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <h2 style="text-align: center;">Verify Two-Factor Authenticator Code</h2>
            <?php if (isset($error)) echo "<div class='alert alert-danger' style='color: red; text-align: center;'>$error</div>"; ?>
            <form action="verify_2fa.php" method="post" style="display: flex; flex-direction: column; gap: 15px;">
                <label for="code" style="font-weight: bold;">Enter 2FA Code</label>
                <input type="text" id="code" name="code" required style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                <button type="submit" name="verify" style="padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Verify</button>
            </form>
            <div style="text-align: center; margin-top: 10px;">
                <a href="verify_2fa2.php" style="color: #007bff; text-decoration: none;">
                    <i class="fas fa-envelope"></i> Resend 2FA Code
                </a>
            </div>
        </div>
    </div>
</body>
</html>
