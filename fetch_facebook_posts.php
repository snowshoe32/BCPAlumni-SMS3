<?php
$accessToken = 'YOUR_ACCESS_TOKEN';
$pageId = 'BestlinkAlumni';
$limit = 5; // Number of posts to fetch

$apiUrl = "https://graph.facebook.com/v11.0/{$pageId}/posts?fields=message,created_time&limit={$limit}&access_token={$accessToken}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$posts = json_decode($response, true);

if (isset($posts['data'])) {
    foreach ($posts['data'] as $post) {
        echo '<div class="post-item clearfix">';
        echo '<h4><a href="https://www.facebook.com/' . $post['id'] . '" target="_blank">' . $post['message'] . '</a></h4>';
        echo '<p>' . date('F j, Y', strtotime($post['created_time'])) . '</p>';
        echo '</div>';
    }
} else {
    echo '<p>No recent posts found.</p>';
}
?>
