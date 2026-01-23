<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include autoloader for automatic class loading
require_once __DIR__ . '/Autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Database credentials for PostgreSQL
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_USER', 'postgres');
define('DB_PASS', 'password');
define('DB_NAME', 'clubEdge');


// App URL
define('BASE_URL', rtrim($_ENV['APP_URL'] ?? 'http://localhost/club-Edge', '/'));
define('BASE_PATH', dirname(__DIR__));
define('LOG_PATH', BASE_PATH . '/logs');
