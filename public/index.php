<?php
declare(strict_types=1);

use App\Core\View;

define('BASE_PATH', dirname(__DIR__));

$envFile = BASE_PATH . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $value = trim($value, " \"'");
        if (getenv(trim($key)) === false) putenv(trim($key) . '=' . $value);
    }
}

$config = require BASE_PATH . '/config/app.php';
date_default_timezone_set($config['timezone']);
session_name($config['session_name']);
session_set_cookie_params([
    'httponly' => true,
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'samesite' => 'Lax',
]);
session_start();

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) return;
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) require $file;
});
require BASE_PATH . '/app/Core/Helpers.php';
require BASE_PATH . '/routes/web.php';
