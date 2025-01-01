<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Dhaka');

// Establish a database connection
$conn = new mysqli('localhost', 'bracuoca_admin', 'admin2024', 'bracuoca_database');

// Check for a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
