<?php
header('Content-Type: application/json');
include('db_connection.php');

$today = date('Y-m-d');

$query = "SELECT * FROM cpcv_ads 
          WHERE status = 1 
            AND start_date <= ? 
            AND end_date >= ? 
          ORDER BY RAND() 
          LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $today, $today);
$stmt->execute();
$result = $stmt->get_result();

if ($ad = $result->fetch_assoc()) {
    // If you have a video file path, use it. Otherwise, send google_ad_code or other data
    // Assuming you add a column `video_path` for the ad video file
    echo json_encode([
        'id' => $ad['id'],
        'video_path' => $ad['video_path'] ?? '',  // use null coalesce operator for safety
        'reward_points' => $ad['reward_points']
    ]);
} else {
    echo json_encode(['error' => 'No video found. Please try again.']);
}

$stmt->close();
$conn->close();
?>
