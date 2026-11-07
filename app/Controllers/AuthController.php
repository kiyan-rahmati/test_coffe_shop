<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Services\AuthService;
use Throwable;

final class AuthController
{
    public function login(): void
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $auth = new AuthService();
            if ($auth->attempt((string)($_POST['email'] ?? ''), (string)($_POST['password'] ?? ''))) {
                redirect(($_SESSION['user']['role'] ?? '') === 'admin' ? '/admin' : '/');
            }
            $error = 'ایمیل یا رمز عبور صحیح نیست.';
        }
        View::render('auth/login', compact('error'));
    }

    public function register(): void
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $name = trim((string)($_POST['name'] ?? ''));
            $email = trim((string)($_POST['email'] ?? ''));
            $password = (string)($_POST['password'] ?? '');
            if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 10) {
                $error = 'نام و ایمیل معتبر وارد کنید و رمز عبور حداقل ۱۰ کاراکتر باشد.';
            } else {
                try {
                    (new AuthService())->register($name, $email, $password);
                    redirect('/login?registered=1');
                } catch (Throwable $exception) {
                    $error = 'ثبت‌نام انجام نشد؛ ممکن است این ایمیل قبلاً ثبت شده باشد.';
                }
            }
        }
        View::render('auth/register', compact('error'));
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method not allowed');
        }
        verify_csrf();
        (new AuthService())->logout();
        redirect('/');
    }
}
