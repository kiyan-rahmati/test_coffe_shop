# Architecture
The application follows a lightweight MVC-inspired layout.
- Core: shared infrastructure such as database access and view rendering.
- Controllers: HTTP request handling.
- Models: persistence-oriented data access.
- Services: reusable application logic.
- Middleware: authentication and authorization checks.
- resources/views: server-rendered templates.
- public: web document root; keep secrets and private files outside it.

The current version is a starter scaffold. Payment processing, order submission, admin CRUD, and production hardening are not implemented yet.
