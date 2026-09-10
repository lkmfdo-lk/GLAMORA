<?php
// app/config/config.php

// Application Name
define('APP_NAME', 'Glamora');

// Auto-detect Base URL to support both port 80 and port 8888 automatically
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST']; // Includes port if present
define('BASE_URL', $protocol . '://' . $host . '/GLAMORA');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
