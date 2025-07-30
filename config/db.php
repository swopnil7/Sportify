<?php
// Database connection for Sportify
$conn = new mysqli('localhost', 'root', '', 'sportify_db');
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>