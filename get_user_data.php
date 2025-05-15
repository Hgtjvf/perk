<?php
session_start();
include('db_connection.php');
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

function is_valid_date($date) {
    return !empty($date) && $date !== '0000-00-00 00:00:00' && strtotime($date) !== false;
}

$query = $conn->prepare("SELECT 
    `username-signup` AS username,
    `email-signup` AS email,
    `account_status`,
    `points`,
    `join_date`,
    `last_active`
FROM accounts WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();

$result = $query->get_result();
$user = $result->fetch_assoc();

if ($user) {
    if (is_valid_date($user['join_date'])) {
        $user['join_date'] = date("F j, Y, g:i a", strtotime($user['join_date']));
    } else {
        $user['join_date'] = "Unknown";
    }

    if (is_valid_date($user['last_active'])) {
        $user['last_active'] = date("F j, Y, g:i a", strtotime($user['last_active']));
    } else {
        $user['last_active'] = "Never";
    }

    echo json_encode($user);
} else {
    echo json_encode(['error' => 'User not found']);
}
?>
