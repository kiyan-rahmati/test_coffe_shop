<?php
// تنظیمات بک‌اند. پیش‌فرض ادمین: admin / admin
// ⚠️ قبل از انتشار روی سرور واقعی حتماً رمز را عوض کنید:
//    php -r "echo password_hash('رمز-جدید', PASSWORD_DEFAULT);"  و خروجی را در ADMIN_HASH بگذارید.
const ADMIN_USER = 'admin';
const ADMIN_HASH = null;          // اگر null باشد، رمز پیش‌فرض "admin" پذیرفته می‌شود (فقط برای دمو)
const ADMIN_DEFAULT_PASS = 'admin';
const DATA_DIR = __DIR__ . '/data';
