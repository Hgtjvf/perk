<?php
header("Content-Type: application/json");
include('db_connection.php');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['viewer_username']) || !isset($data['creator_username'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$viewer = $conn->real_escape_string($data['viewer_username']);
$creator = $conn->real_escape_string($data['creator_username']);

// Add 2 points to viewer
$updateViewer = "UPDATE accounts SET points = points + 2 WHERE username_signup = '$viewer'";

// Add 1 point to creator
$updateCreator = "UPDATE accounts SET points = points + 1 WHERE username_signup = '$creator'";

$success = true;
$msg = '';

if (!$conn->query($updateViewer)) {
    $success = false;
    $msg .= "Failed to update viewer points: " . $conn->error . ". ";
}

if (!$conn->query($updateCreator)) {
    $success = false;
    $msg .= "Failed to update creator points: " . $conn->error;
}

echo json_encode(['success' => $success, 'message' => $msg]);

$conn->close();
?>
