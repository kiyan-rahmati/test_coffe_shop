# Database
Import `database/schema.sql` into MySQL 8+.
User passwords are stored as hashes in `users.password_hash`; never store plaintext passwords.
Before production, add migrations, backups, indexes based on query patterns, and a least-privilege DB account.
