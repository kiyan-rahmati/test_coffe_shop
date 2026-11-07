<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Database;
final class User
{
    public static function findByEmail(string $email): array|false
    {
        $query = Database::connection()->prepare('SELECT id,name,email,password_hash,role FROM users WHERE email=:email LIMIT 1');
        $query->execute(['email' => mb_strtolower(trim($email))]);
        return $query->fetch();
    }
}
