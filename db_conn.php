<?php   
$dbHost = "localhost";
$username = "root";
$password = "";
$dbname = "alumnibcp";

$conn = mysqli_connect($dbHost, $username, $password, $dbname);
if (!$conn) {
    die("Connection Failed ". mysqli_connect_error());

}
//echo "Connected succesfully"; 