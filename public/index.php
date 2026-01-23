<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../config/App.php';
// ... le reste du code

$router = new Router();

require_once __DIR__ . '/../routes/web.php';

use App\Core\Logger;
try {
    $router->dispatch();
} catch (Throwable $e) {
    Logger::error('Uncaught Exception: ' . $e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);

    http_response_code(500);
    if (ini_get('display_errors')) {
        echo '<h1>500 Internal Server Error</h1>';
        echo '<pre>' . htmlspecialchars((string)$e) . '</pre>';
    } else {
        echo '500 Internal Server Error';
    }
}


