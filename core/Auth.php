<?php

class Auth{
    public static function check(): void{
        if(!isset($_SESSION['user'])){
            header('Location:/login');
            exit;
        }
    }

    public static function id(): int{
        return $_SESSION['user']['id'];
    }

    public static function clubId(): int{
        return $_SESSION['user']['club_id'];
    }

    public static function isPresident(): bool{
        return $_SESSION['user']['role'] === 'president';
    }
}
?>