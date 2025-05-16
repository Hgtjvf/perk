<?php
header('Content-Type: application/json');
include('../db_connection.php');

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
        'full_name' => $row['full-name-signup'],
        'username' => $row['username-signup'],
        'email' => $row['email-signup'],
        'points' => $row['points'],
        'pkr' => $row['pkr'],
        'account_status' => $row['account_status'] ?? 'Active',
        'registration_date' => $row['join_date'] ?? 'N/A',
        'last_login' => $row['last_active'] ?? 'N/A',
    ];
}

echo json_encode($users);
mysqli_close($conn);
