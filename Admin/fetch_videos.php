<?php
// Database connection
include('../db_connection.php');
// Fetch videos
$sql = "SELECT id, title, description, tags, category, file_name, file_path FROM media";
$result = $conn->query($sql);

$videos = [];
while ($row = $result->fetch_assoc()) {
  $videos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($videos);
?>
