<?php
// connect.php
include('db_connection.php');
header('Content-Type: application/json');

$response = ['success' => false, 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['errors']['general'] = 'Invalid request method.';
    echo json_encode($response);
    exit;
}

// Sanitize inputs
$fullName        = mysqli_real_escape_string($conn, $_POST['full-name-signup'] ?? '');
$email           = mysqli_real_escape_string($conn, $_POST['email-signup'] ?? '');
$username        = mysqli_real_escape_string($conn, $_POST['username-signup'] ?? '');
$password        = mysqli_real_escape_string($conn, $_POST['password-signup'] ?? '');
$confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm-password-signup'] ?? '');

// Check for empty fields
if (!$fullName || !$email || !$username || !$password || !$confirmPassword) {
    $response['errors']['general'] = 'Please fill in all required fields.';
    echo json_encode($response);
    exit;
}

// Validate passwords match
if ($password !== $confirmPassword) {
    $response['errors']['password'] = 'Passwords do not match.';
    echo json_encode($response);
    exit;
}

// Check if email or username already exists
$checkQuery = "SELECT 1 FROM accounts WHERE `email-signup` = '$email' OR `username-signup` = '$username'";
$checkResult = mysqli_query($conn, $checkQuery);
if (!$checkResult) {
    $response['errors']['general'] = 'Database error: ' . mysqli_error($conn);
    echo json_encode($response);
    exit;
}
if (mysqli_num_rows($checkResult) > 0) {
    $response['errors']['email_or_username'] = 'Email or username already taken.';
    echo json_encode($response);
    exit;
}

$now = date('Y-m-d H:i:s');

// Insert user without hashing password (plain text)
$insertQuery = "
    INSERT INTO accounts
    (`full-name-signup`, `email-signup`, `username-signup`, `password-signup`, `points`, `pkr`, `account_status`, `join_date`, `last_active`)
    VALUES
    ('$fullName', '$email', '$username', '$password', 0, 0.00, 'active', '$now', '$now')
";

if (mysqli_query($conn, $insertQuery)) {
    $response['success'] = true;
} else {
    $response['errors']['general'] = 'Insert error: ' . mysqli_error($conn);
}

echo json_encode($response);
