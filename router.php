<?php
// برای اجرای محلی: php -S localhost:8000 router.php
// دسترسی مستقیم به فایل‌های داده و تنظیمات را می‌بندد.
$p = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('#^/backend/(data/|config\.php)#', $p)) { http_response_code(403); echo 'Forbidden'; return true; }
return false;
