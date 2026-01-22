<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include autoloaders
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Load .env variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Database credentials for PostgreSQL
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_PORT', $_ENV['DB_PORT'] ?? '5432');
define('DB_USER', $_ENV['DB_USER'] ?? 'postgres');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'clubEdge');


// App URL
define('BASE_URL', rtrim($_ENV['APP_URL'] ?? 'http://localhost/club-Edge', '/'));
define('BASE_PATH', dirname(__DIR__));
define('LOG_PATH', BASE_PATH . '/logs');
