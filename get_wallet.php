<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch wallet (points and pkr) data from accounts table
$query = $conn->prepare("SELECT points, pkr FROM accounts WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();

$result = $query->get_result();
if ($wallet = $result->fetch_assoc()) {
    echo json_encode([
        'points' => (int)$wallet['points'],
        'pkr' => (float)$wallet['pkr']
    ]);
} else {
    echo json_encode(['error' => 'User not found']);
}
?>
