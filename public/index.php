<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/App.php';

$router = new Router();

require_once __DIR__ . '/../routes/web.php';

try {
    $router->dispatch();
} catch (Throwable $e) {
    http_response_code(500);
    if (ini_get('display_errors')) {
        echo '<h1>500 Internal Server Error</h1>';
        echo '<pre>' . htmlspecialchars((string)$e) . '</pre>';
    } else {
        echo '500 Internal Server Error';
    }
}
