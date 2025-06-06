<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}

include "db_conn.php";
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fetch all alumni from the database
$query = "SELECT `fname`, `lname`, `email` FROM `bcp-sms3_alumnidata`";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error fetching data from database.";
    exit();
}

$alumni = [];
while ($row = mysqli_fetch_assoc($result)) {
    $alumni[] = $row;
}

// Send email to each alumnus using PHPMailer
$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username   = 'snowshoe0103@gmail.com'; // SMTP username
    $mail->Password   = 'bvoa zaxb ugki vtwm';  // Replace with your email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Sender info
    $mail->setFrom('no-reply@yourdomain.com', 'Alumni Team');

    foreach ($alumni as $alumnus) {
        // Recipient
        $mail->addAddress($alumnus['email'], $alumnus['fname'] . ' ' . $alumnus['lname']);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Request to Update Your Alumni Tracer Information";
        $mail->Body = "Good day" ." ". "<b>".$alumnus['fname']." "."</b>". " " . $alumnus['lname'] .  " from Bestlink Alumni Association:<br>,<br><br>";
      
        $mail->Body .= "Warm greetings from the Bestlink Alumni Association of Bestlink College of the Philippines.<br>
We are currently updating our Alumni Tracer database to ensure our records remain accurate and up to date.<br> 
We kindly request you to take a few moments to update your information through the link below.<br><br>";
 $mail->Body .= "<a href='https://alumni.bcpsms3.com/alumni_tracer.php'>Update Alumni Tracer</a><br><br>";
 $mail->Body .= "Your updated data will help us better connect with you and support future activities, events, and programs.
Thank you for your continued support and involvement.<br><br>";
       
        $mail->Body .= "<br><br>Your Bestlink Alumni Association";

        $mail->send();
        $mail->clearAddresses(); // Clear recipient for the next email
    }

    header('Location: student-data.php?msg=Emails sent successfully.');
    exit();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    exit();
}
?>
