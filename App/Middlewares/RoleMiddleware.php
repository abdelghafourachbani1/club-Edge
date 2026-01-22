<?php

declare(strict_types=1);

namespace App\Middlewares;

use MiddlewareInterface;

class RoleMiddleware implements MiddlewareInterface
{
    
    public function handle(array $params, ...$args): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $user = $_SESSION['user'];
        $userRole = is_array($user) ? ($user['role'] ?? null) : ($user->role ?? null);

        if (!$userRole || !in_array($userRole, $args)) {
            http_response_code(403);
            echo "Access Denied: You do not have the required role (" . implode(', ', $args) . ").";
            exit;
        }
    }
}
