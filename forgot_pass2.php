<?php
// Include database connection and any required files
include 'db_conn.php'; // Assuming a separate file for db connection
include 'email_functions.php'; // Assuming you have an email sending utility

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // User exists, generate a token
        $token = bin2hex(random_bytes(32)); // Secure token
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token expires in 1 hour
        
        // Store the token in the database
        $stmt = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE email=?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();
        
        // Create the reset link
        $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $token;

        // Send email with the reset link
        $subject = "Password Reset Request";
        $message = "Hello,\n\nWe received a password reset request for your account.\n";
        $message .= "Click the link below to reset your password:\n\n";
        $message .= $reset_link . "\n\nIf you did not request a password reset, please ignore this email.";
        $headers = "From: no-reply@yourwebsite.com";
        
        if (mail($email, $subject, $message, $headers)) {
            echo "An email with a password reset link has been sent to your email address.";
        } else {
            echo "There was an error sending the reset email.";
        }
    } else {
        echo "Email does not exist.";
    }
}
?>