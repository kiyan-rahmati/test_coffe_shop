<?php
declare(strict_types=1);

return [
    'name' => getenv('APP_NAME') ?: 'کافه نارون',
    'env' => getenv('APP_ENV') ?: 'production',
    'debug' => filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOL),
    'url' => getenv('APP_URL') ?: 'http://localhost',
    'session_name' => getenv('SESSION_NAME') ?: 'narvan_session',
    'timezone' => 'Asia/Tehran',
];
