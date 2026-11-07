<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

final class AuthService
{
    public function register(string $name, string $email, string $password): bool
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare(
            'INSERT INTO users (name, email, password_hash, role, created_at) VALUES (:name, :email, :hash, "customer", NOW())'
        );
        return $statement->execute([
            'name' => trim($name),
            'email' => mb_strtolower(trim($email)),
            'hash' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    public function attempt(string $email, string $password): bool
    {
        $statement = Database::connection()->prepare(
            'SELECT id, name, email, password_hash, role FROM users WHERE email = :email LIMIT 1'
        );
        $statement->execute(['email' => mb_strtolower(trim($email))]);
        $user = $statement->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        unset($user['password_hash']);
        $_SESSION['user'] = $user;
        return true;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
