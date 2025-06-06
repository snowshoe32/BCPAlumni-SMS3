<?php
include "db_conn.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM `bcp-sms3_alumnibenefits` WHERE `id` = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: alumni_benefits.php?msg=Record deleted successfully");
            exit();
        } else {
            header("Location: alumni_benefits.php?msg=Error deleting record");
            exit();
        }
    } else {
        header("Location: alumni_benefits.php?msg=Error preparing statement");
        exit();
    }
} else {
    header("Location: alumni_benefits.php?msg=Invalid request");
    exit();
}
?>
