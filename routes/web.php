<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Core\View;

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($path === '/' && $method === 'GET') {
    View::render('main/home');
    return;
}

$auth = new AuthController();
if ($path === '/login') { $auth->login(); return; }
if ($path === '/register') { $auth->register(); return; }
if ($path === '/logout') { $auth->logout(); return; }

if ($path === '/admin' && $method === 'GET') {
    (new AdminController())->dashboard();
    return;
}

http_response_code(404);
View::render('errors/404');
