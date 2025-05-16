<?php
// Include database connection (adjust the path if needed)
include('db_connection.php');

// Check if the form is submitted via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize the input email to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if the email is not empty
    if (!empty($email)) {
        // Prepare an SQL query to insert the email into the "subscibe" table
        $query = "INSERT INTO `subscibe` (`subscribe-footer`) VALUES ('$email')";

        // Execute the query
        if (mysqli_query($conn, $query)) {
            echo json_encode(["success" => true, "message" => "Thank you for subscribing!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error: Could not insert email. Please try again."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Please provide a valid email address."]);
    }
}
?>
