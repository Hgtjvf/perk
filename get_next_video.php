<?php
header("Content-Type: application/json");
include('db_connection.php');

// Modify this query if you have a creator_id column or equivalent
$query = "SELECT id AS video_id, file_path, user_name AS creator_id FROM media ORDER BY RAND() LIMIT 1";
$result = $conn->query($query);

if ($result && $video = $result->fetch_assoc()) {
    echo json_encode([
        'video_id' => $video['video_id'],
        'file_path' => $video['file_path'],
        'creator_id' => $video['creator_id'] // Assuming user_name is creator id/name; change if needed
    ]);
} else {
    echo json_encode([]);
}

$conn->close();
?>
