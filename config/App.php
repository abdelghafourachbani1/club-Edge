<?php
// start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include autoloader for automatic class loading
require_once __DIR__ . '/Autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Database credentials for PostgreSQL
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_USER', 'farfoure');
define('DB_PASS', 'yns1234');
define('DB_NAME', 'clubedge');

// App URL
define('BASE_URL', rtrim('http://localhost/club-Edge', '/'));
define('BASE_PATH', dirname(__DIR__));
