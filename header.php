<?php
// header.php

include('db_connection.php');
session_start();

$isLoggedIn = false;
$userData = [];

if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;

    $query = "SELECT `username-signup` FROM accounts WHERE `id` = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
    }
}

header('Content-Type: application/json');
echo json_encode([
    'isLoggedIn' => $isLoggedIn,
    'userData' => $userData,
]);
?>
