<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}

include "db_conn.php";
$id = $_GET['id'];

// Delete data from the API
$api_url = 'https://sis.bcpsms3.com/api/alumni/' . $id;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    echo "Failed to delete record from API.";
} else {
    header("Location: student-data.php?msg=Record deleted successfully");
}
?>

