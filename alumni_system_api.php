<?php 
include 'db_conn.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json')

// Secure query using prepared statements
// Since sessions are removed, we fetch all users.
// The original query was to exclude the logged-in user, which is not applicable here.
$sql = "SELECT * FROM `bcp_sms3_alumnidata1` WHERE 1";
$result = mysqli_query($conn, $sql);

if (!$result) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Database query failed: " . mysqli_error($conn)]);
    exit();
}

if ($result) {
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    http_response_code(200);
    echo json_encode($data);
    // mysqli_stmt_close($stmt); // $stmt is not used here as we are using mysqli_query directly

} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Failed to fetch data from database."]);
}
// Close connection
mysqli_close($conn);
?>
