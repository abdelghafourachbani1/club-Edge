<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}


    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {

                $host = defined('DB_HOST') ? DB_HOST : 'localhost';
                $port = defined('DB_PORT') ? DB_PORT : '5432';
                $user = defined('DB_USER') ? DB_USER : 'postgres';
                $pass = defined('DB_PASS') ? DB_PASS : 'password';
                $name = defined('DB_NAME') ? DB_NAME : 'club_db';

                // Ensure DSN uses these variables for PostgreSQL
                $dsn = "pgsql:host={$host};port={$port};dbname={$name}";

                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                error_log("Database Connection Error: " . $e->getMessage());
                throw new Exception("Could not connect to the database: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
