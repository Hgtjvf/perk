<?php
include('db_connection.php');
header('Content-Type: application/json');

$response = ['success' => false, 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['errors']['general'] = 'Invalid request method.';
    echo json_encode($response);
    exit;
}

$emailOrUsername = mysqli_real_escape_string($conn, $_POST['email-username-signin'] ?? '');
$password = $_POST['password-signin'] ?? '';

if (empty($emailOrUsername) || empty($password)) {
    $response['errors']['general'] = 'Email/Username and password required.';
    echo json_encode($response);
    exit;
}

$query = "SELECT id, `username-signup`, `password-signup` FROM accounts WHERE `email-signup` = ? OR `username-signup` = ? LIMIT 1";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ss', $emailOrUsername, $emailOrUsername);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    // **No hashing, just plain comparison**
    if ($password === $user['password-signup']) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username-signup'];
        $response['success'] = true;
    } else {
        $response['errors']['password'] = 'Incorrect password.';
    }
} else {
    $response['errors']['email_or_username'] = 'No account found.';
}

echo json_encode($response);
