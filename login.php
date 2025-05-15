<?php
// login.php

// Include your database connection file (adjust path if necessary)
include('db_connection.php');

// Set response header for JSON
header('Content-Type: application/json');

// Initialize an empty array to hold errors
$response = ['success' => false, 'errors' => []];

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleLogin($conn, $_POST);
} else {
    $response['errors']['general'] = 'Invalid request method.';
    echo json_encode($response);
    exit;
}

// Function to handle login
function handleLogin($conn, $data) {
    global $response;

    // Sanitize input data
    $emailOrUsername = mysqli_real_escape_string($conn, $data['email-username-signin']);
    $password = mysqli_real_escape_string($conn, $data['password-signin']);

    // Fetch user by email or username
    $query = "SELECT * FROM accounts WHERE `email-signup` = ? OR `username-signup` = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $emailOrUsername, $emailOrUsername);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Direct password comparison (without hashing)
        if ($password === $user['password-signup']) {
            // Success: Create a session for the user
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username-signup'];  // Correct column

            // Optionally, set cookies for "Remember Me" functionality
            if (isset($data['remember-me-signin']) && $data['remember-me-signin'] == 'on') {
                setcookie('username', $user['username-signup'], time() + (86400 * 30), "/"); // expires in 30 days
            }

            // Return success
            $response['success'] = true;
        } else {
            $response['errors']['password'] = 'Incorrect password.';
        }
    } else {
        $response['errors']['email_or_username'] = 'No account found with that email or username.';
    }

    // Return response
    echo json_encode($response);
    exit;
}
?>
