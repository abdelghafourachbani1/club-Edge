<?php

require_once 'vendor/autoload.php';

use App\Models\Database;

$db = Database::getInstance();

// Test connection
try {
    $db->query("SELECT 1");
    $db->query("SELECT * FROM users");
    $users = $db->fetchAll(PDO::FETCH_ASSOC);
    echo "Database connection successful!";
    print_r($users);
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
}