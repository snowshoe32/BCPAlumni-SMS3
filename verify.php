<?php
include "db_conn.php";

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    $query = "SELECT * FROM `bcp_sms3_user` WHERE token = '$token' AND is_verified = 0";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $update_query = "UPDATE `bcp_sms3_user` SET is_verified = 1 WHERE token = '$token'";
        if (mysqli_query($conn, $update_query)) {
            echo "Your account has been verified successfully!";
            header("Location: index.php?success=Your account has been verified successfully!");
            exit();
        } else {
            echo "Verification failed. Please try again.";
        }
    } else {
        echo "Invalid token or account already verified.";
    }
} else {
    echo "No token provided.";
}
?>
