<?php   
$dbHost = "localhost";
$username = "alum_alumnibc";
$password = "+8C9cy*edl1mxeIo";
$dbname = "alum_bcp_sms3";

$conn = mysqli_connect($dbHost, $username, $password, $dbname);
if (!$conn) {
    die("Connection Failed ". mysqli_connect_error());

}
//echo "Connected succesfully"; 