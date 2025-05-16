<?php
// Include the database connection
include('db_connection.php');

// Response array for JSON response
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $file_path = null;

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        $response['message'] = 'Please fill out all required fields.';
        echo json_encode($response);
        exit;
    }

    // File upload handling
    if (!empty($_FILES['file']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['file']['name']);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'png', 'pdf'];

        // Validate file type
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
                $file_path = $target_file;
            } else {
                $response['message'] = 'Failed to upload the file.';
                echo json_encode($response);
                exit;
            }
        } else {
            $response['message'] = 'Invalid file type. Only JPG, PNG, and PDF are allowed.';
            echo json_encode($response);
            exit;
        }
    }

    // Insert data into the database
    $query = "INSERT INTO `contact_messages` (`name`, `email`, `subject`, `message`, `file_path`, `created_at`) 
              VALUES ('$name', '$email', '$subject', '$message', '$file_path', NOW())";

    if (mysqli_query($conn, $query)) {
        $response['success'] = true;
        $response['message'] = 'Your message has been sent successfully.';
    } else {
        $response['message'] = 'Error: Could not submit your message.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
