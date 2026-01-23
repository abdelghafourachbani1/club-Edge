<?php

class Auth
{
    public static function check(): void
    {
        $_SESSION['user'] = [
            'id' => 1,
            'role' => 'president',
            'club_id' => 1
        ];
    }

    public static function id(): int
    {
        return $_SESSION['user']['id'] ?? 0;
    }

    public static function clubId(): int
    {
        return $_SESSION['user']['club_id'] ?? 0;
    }

    public static function isPresident(): bool
    {
        return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'president';
    }
}
