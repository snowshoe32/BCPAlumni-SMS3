<?php
session_start();
include "db_conn.php";

if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];

// Fetch the current photo path
$sql = "SELECT photo FROM `bcp-sms3_news` WHERE id = $id";
$result = mysqli_query($conn, $sql);
$news = mysqli_fetch_assoc($result);

if ($news && $news['photo']) {
    $photoPath = $news['photo'];

    // Delete the photo file
    if (file_exists($photoPath)) {
        unlink($photoPath);
    }

    // Update the database to remove the photo path
    $updateSql = "UPDATE `bcp-sms3_news` SET photo = NULL WHERE id = $id";
    mysqli_query($conn, $updateSql);
}

header("Location: editnews.php?id=$id&msg=Photo removed successfully");
exit();
?>