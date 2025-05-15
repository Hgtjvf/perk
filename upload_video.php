<?php
session_start();
include('db_connection.php');

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    echo '<script>alert("You must be logged in to upload a video."); window.location.href = "sign-in.html";</script>';
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $mediaTitle = $_POST['media-title'] ?? '';
    $mediaDescription = $_POST['media-description'] ?? '';
    $mediaTags = $_POST['media-tags'] ?? '';
    $mediaCategory = $_POST['media-category'] ?? '';
    $mediaCaption = $_POST['media-caption'] ?? '';
    $username = $_SESSION['username'];

    // File upload logic
    if (isset($_FILES['videoFile'])) {
        $file = $_FILES['videoFile'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = mime_content_type($fileTmpName);
        $allowed = ['video/mp4', 'video/avi', 'image/jpeg', 'image/png', 'image/jpg'];

        if (in_array($fileType, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize <= 1073741824) { // 1GB max
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $fileNewName = uniqid('media_', true) . '.' . $fileExt;
                    $fileDestination = 'video/' . $fileNewName;

                    if (move_uploaded_file($fileTmpName, $fileDestination)) {
                        $stmt = $conn->prepare("
                            INSERT INTO media (title, description, tags, category, caption, file_name, file_path, user_name) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                        ");
                        $stmt->bind_param(
                            "ssssssss", 
                            $mediaTitle, $mediaDescription, $mediaTags, 
                            $mediaCategory, $mediaCaption, 
                            $fileNewName, $fileDestination, $username
                        );

                        if ($stmt->execute()) {
                            echo '<script>
                                    alert("Upload successful! You can review your video on your profile page.");
                                    window.location.href = "user.html";
                                  </script>';
                        } else {
                            echo "Database error: " . $stmt->error;
                        }

                        $stmt->close();
                    } else {
                        echo "Failed to move uploaded file.";
                    }
                } else {
                    echo "File size exceeds 1GB limit.";
                }
            } else {
                echo "Error uploading file: Code $fileError.";
            }
        } else {
            echo "Invalid file type. Allowed: MP4, AVI, JPG, PNG, JPEG.";
        }
    } else {
        echo "No file was uploaded.";
    }
}

$conn->close();
?>
