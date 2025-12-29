<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'result_management');
define('DB_PORT', 3307);

// Connect to database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8mb4");

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site settings
define('SITE_NAME', 'Online Result Management System');
define('SCHOOL_NAME', 'ABC International School');
?>
