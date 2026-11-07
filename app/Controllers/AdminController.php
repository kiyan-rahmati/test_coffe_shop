<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Middleware\AuthMiddleware;

final class AdminController
{
    public function dashboard(): void
    {
        AuthMiddleware::requireAdmin();
        View::render('admin/dashboard', ['user' => $_SESSION['user']]);
    }
}
