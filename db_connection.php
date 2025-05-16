<?php
// Prevent direct access
if (!defined('ACCESS_ALLOWED')) {
    die('Direct access not allowed');
}

// Include config with database credentials
require_once __DIR__ . '/../table/config.php';

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
