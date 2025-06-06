<?php
include 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM `bcp-sms3_auditlogs` WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: auditlogs.php?msg=Record deleted successfully");
    } else {
        header("Location: auditlogs.php?msg=Error deleting record");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: auditlogs.php");
    exit();
}
