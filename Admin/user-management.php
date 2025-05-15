<?php
header('Content-Type: application/json'); // JSON response header

include('../db_connection.php'); // Include database connection

$query = "SELECT * FROM accounts";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(["error" => "Failed to fetch users: " . mysqli_error($conn)]);
    exit;
}

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = [
        'id' => $row['id'],
        'username' => $row['username-signup'],
        'email' => $row['email-signup'],
        'account_status' => $row['account_status'] ?? 'Active',
        'registration_date' => $row['registration_date'] ?? 'N/A',
        'last_login' => $row['last_login'] ?? 'N/A',
    ];
}

echo json_encode($users); // Send user data as JSON
mysqli_close($conn);
