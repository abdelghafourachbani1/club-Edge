<?php

namespace App\Helpers;

class Security
{
    
    public static function generateCsrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    
    public static function getCsrfToken(): ?string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['csrf_token'] ?? null;
    }

    
    public static function validateCsrfToken(?string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sessionToken = self::getCsrfToken();

        if ($sessionToken && $token && hash_equals($sessionToken, $token)) {
            return true;
        }

        return false;
    }

    
    public static function csrfField(): string
    {
        $token = self::generateCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}
