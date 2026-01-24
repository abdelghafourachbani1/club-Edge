<?php
declare(strict_types=1);
namespace App\Middlewares;
use MiddlewareInterface;

class RoleMiddleware implements MiddlewareInterface
{
    public function handle(array $params, ...$args): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $userRole = $_SESSION['role'] ?? null;

        if (!$userRole || !in_array($userRole, $args)) {
            http_response_code(403);
            echo "<h1 style='color: red;'>Access Denied</h1>";
            echo "<p>Required role: " . implode(' or ', $args) . "</p>";
            echo "<p>Your role: <strong>" . ($userRole ?? 'none') . "</strong></p>";
            echo "<a href='" . BASE_URL . "/events'>← Back to Events</a>";
            exit;
        }
    }
}
