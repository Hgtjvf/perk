<?php
define('ACCESS_ALLOWED', true); // Must be set before requiring db_connection
require_once 'db_connection.php';

// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Check if file and form fields exist
if (!isset($_FILES['videoFile']) || !isset($_POST['media-title'])) {
    die("Missing required fields.");
}

// Handle uploaded file
$video = $_FILES['videoFile'];
$title = trim($_POST['media-title']);
$description = trim($_POST['media-description']);
$tags = trim($_POST['media-tags']);
$category = trim($_POST['media-category']);
$caption = trim($_POST['media-caption']);
$user_name = trim($_POST['tag-users']); // The rewarded user

// Validate file type
$allowedMime = ['video/mp4'];
if (!in_array($video['type'], $allowedMime)) {
    die("Only MP4 videos are allowed.");
}

// Validate size (Max ~1GB)
$maxSize = 1024 * 1024 * 1024;
if ($video['size'] > $maxSize) {
    die("File size exceeds 1GB limit.");
}

// Prepare file name and move file
$targetDir = "video/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}
$extension = pathinfo($video['name'], PATHINFO_EXTENSION);
$filename = uniqid('vid_', true) . '.' . $extension;
$targetFile = $targetDir . $filename;

if (!move_uploaded_file($video['tmp_name'], $targetFile)) {
    die("Failed to upload the video.");
}

// Store metadata in database
$stmt = $conn->prepare("INSERT INTO media (title, description, tags, category, caption, file_name, file_path, user_name)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $title, $description, $tags, $category, $caption, $filename, $targetFile, $user_name);

if ($stmt->execute()) {
    echo "Video uploaded and saved successfully.";
} else {
    echo "Database error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
