<?php
include('db_connection.php');
header('Content-Type: application/json');

$response = ['success' => false, 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $fullName = mysqli_real_escape_string($conn, $_POST['full-name-signup']);
    $email = mysqli_real_escape_string($conn, $_POST['email-signup']);
    $username = mysqli_real_escape_string($conn, $_POST['username-signup']);
    $password = mysqli_real_escape_string($conn, $_POST['password-signup']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm-password-signup']);

    // Validate passwords match
    if ($password !== $confirmPassword) {
        $response['errors']['password'] = 'Passwords do not match.';
    } else {
        // Check if email or username exists
        $checkQuery = "SELECT * FROM accounts WHERE email = '$email' OR username = '$username'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $response['errors']['email_or_username'] = 'Email or username already taken.';
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into accounts table with default wallet values
            $insertQuery = "INSERT INTO accounts (full_name, email, username, password, points, pkr) 
                            VALUES ('$fullName', '$email', '$username', '$hashedPassword', 0, 0.00)";
            
            if (mysqli_query($conn, $insertQuery)) {
                $response['success'] = true;
            } else {
                $response['errors']['general'] = 'Error registering user: ' . mysqli_error($conn);
            }
        }
    }
} else {
    $response['errors']['general'] = 'Invalid request method.';
}

echo json_encode($response);
?>
