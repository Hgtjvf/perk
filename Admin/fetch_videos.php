<?php
header("Content-Type: application/json");
include('../db_connection.php');

$query = "
SELECT 
    media.id AS video_id,
    media.title,
    media.category,
    media.caption,
    media.file_path,
    media.user_name,
    accounts.`username-signup` AS uploader,
    accounts.points,
    accounts.account_status,
    accounts.join_date,
    accounts.last_active
FROM media
LEFT JOIN accounts ON media.user_name = accounts.`username-signup`
ORDER BY media.id DESC
";

$result = $conn->query($query);
$videos = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $videos[] = [
            'video_id' => $row['video_id'],
            'title' => $row['title'],
            'category' => $row['category'],
            'caption' => $row['caption'],
            'file_path' => $row['file_path'],
            'uploader' => $row['uploader'],
            'reward_points' => $row['points'],
            'status' => $row['account_status'],
            'upload_date' => $row['join_date'],
            'views' => rand(0, 999), // placeholder
            'likes' => rand(0, 100), // placeholder
        ];
    }
}

echo json_encode($videos);
$conn->close();
?>
