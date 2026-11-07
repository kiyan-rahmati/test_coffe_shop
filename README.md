# کافه نارون | Coffee Shop

A modular PHP starter for a Persian, Iranian-inspired coffee shop website.

## Requirements
- PHP 8.1+
- MySQL 8+ (or MariaDB)
- Composer (optional for future dependencies)

## Run locally
1. Copy `.env.example` to `.env` and configure your database.
2. Import `database/schema.sql` into MySQL.
3. Point your web server document root to `public/`.
4. For local preview, run from the repository root:

```bash
php -S 127.0.0.1:8000 -t public
```

Then open http://127.0.0.1:8000

## Structure
- `app/`: controllers, models, services, middleware and core utilities.
- `public/`: only public entry point and static assets.
- `resources/`: page templates, image library and music library.
- `admin/`: protected admin area.
- `auth/`: authentication endpoints.
- `config/`: environment and application configuration.
- `database/`: schema and seed data.
- `storage/`: logs, cache and private runtime data.

## Security notes
- Passwords are stored using PHP `password_hash()`; plaintext passwords are never stored.
- The `storage/`, `config/`, `database/`, `app/` and `resources/` directories must not be exposed directly by the web server.
- Change the default admin password immediately after creating the first admin account.
- This starter does not yet implement online payments or real order fulfillment.

## Current starter features
Persian RTL homepage, menu cards, book recommendations, music-player UI, login/register forms, session-based authentication foundation and an admin dashboard shell.
