<?php

declare(strict_types=1);

namespace App\Middlewares;

use MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(array $params, ...$args): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
}
