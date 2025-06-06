<?php
include 'db_conn.php';

// Check if 'id' parameter is provided in the URL
if (isset($_GET['id'])) {
    $news_id = intval($_GET['id']);

    // Fetch the link from the database based on the provided ID
    $sql = "SELECT embed FROM `bcp-sms3_news` WHERE id = $news_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $embed = htmlspecialchars($row['embed']);

        // Redirect to the link
        header("Location: $embed");
        exit();
    } else {
        echo "Invalid news ID or no link found.";
    }
} else {
    echo "No news ID provided.";
}
?>