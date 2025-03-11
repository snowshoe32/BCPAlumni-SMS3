<?php   
$dbHost = "localhost";
$username = "root";
$password = "";
$dbname = "bcp-sms3";

$conn = mysqli_connect($dbHost, $username, $password, $dbname);
if (!$conn) {
    die("Connection Failed ". mysqli_connect_error());

}
//echo "Connected succesfully"; 