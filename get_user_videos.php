<?php
session_start();
header('Content-Type: application/json');

include('db_connection.php');

// Check if username is set in session
$username = $_SESSION['username'] ?? null;

if (!$username) {
    echo json_encode([
        'error' => 'User not logged in.'
    ]);
    exit;
}

// Prepare SQL statement to fetch videos for the logged-in user
$sql = "SELECT * FROM media WHERE user_name = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'error' => 'Failed to prepare SQL statement: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $username);

if (!$stmt->execute()) {
    echo json_encode([
        'error' => 'Failed to execute SQL statement: ' . $stmt->error
    ]);
    exit;
}

$result = $stmt->get_result();

$videos = [];
while ($row = $result->fetch_assoc()) {
    $videos[] = $row;
}

// Return videos as JSON
echo json_encode($videos);

// Close statement and connection
$stmt->close();
$conn->close();
?>
