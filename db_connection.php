<?php
$servername = "localhost";
$username = "root"; // Your database username
$password = "A11229867540a0@12"; // Your database password
$database = "perkview"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>