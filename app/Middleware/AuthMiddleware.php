<?php
declare(strict_types=1);

namespace App\Middleware;

final class AuthMiddleware
{
    public static function requireUser(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireUser();
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit('شما اجازه دسترسی به این بخش را ندارید.');
        }
    }
}
