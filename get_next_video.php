<?php
header("Content-Type: application/json");
include('db_connection.php');

$query = "SELECT * FROM media ORDER BY RAND() LIMIT 1";
$result = $conn->query($query);

if ($result && $video = $result->fetch_assoc()) {
    echo json_encode([
        'file_path' => $video['file_path']
    ]);
} else {
    echo json_encode([]);
}

$conn->close();
?>
