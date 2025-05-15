<?php
include('../db_connection.php');

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $query = "DELETE FROM accounts WHERE id = $userId";
    if (mysqli_query($conn, $query)) {
        header("Location: user-management.html"); // or .php if dynamic page
        exit;
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
} else {
    echo "No user ID specified.";
}
?>
