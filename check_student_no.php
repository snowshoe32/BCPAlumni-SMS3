<?php
include "db_conn.php";

if (isset($_GET['studentNo'])) {
    $studentNo = htmlspecialchars($_GET['studentNo']);
    $response = ['exists' => false];

    if (preg_match('/^\d{1,8}$/', $studentNo)) {
        $conn = new mysqli("localhost", "username", "password", "database");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT `studentNo` FROM `bcp-sms3_tracer` WHERE `studentNo` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $studentNo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $response['exists'] = true;
        }

        $stmt->close();
        $conn->close();
    }

    echo json_encode($response);
}
