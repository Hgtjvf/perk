<?php
header("Content-Type: application/json");
include('../db_connection.php');  // Include the connection file

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Prepare the insert statement using MySQLi
    $stmt = $conn->prepare("INSERT INTO cpcv_ads (name, google_ad_code, placement, reward_points, status, start_date, end_date, max_views_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param(
        "sssisssi",  // s = string, i = integer
        $data['name'],
        $data['google_ad_code'],
        $data['placement'],
        $data['reward_points'],
        $data['status'],
        $data['start_date'],
        $data['end_date'],
        $data['max_views_user']
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
    }

    $stmt->close();
} elseif ($method === 'GET') {
    $result = $conn->query("SELECT * FROM cpcv_ads ORDER BY created_at DESC");

    if (!$result) {
        echo json_encode(['error' => 'Query failed: ' . $conn->error]);
        exit;
    }

    $ads = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($ads);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>
