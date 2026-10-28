# ACP Photography — Studio Management

PHP 8.1+, MySQL/MariaDB, PDO, vanilla JavaScript, Chart.js, GSAP and Lenis on the public page. The dashboard uses a shared CSS theme and database-driven appearance settings.

## XAMPP setup
1. Extract `ACP-Photography` into `C:\xampp\htdocs\ACP-Photography`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin and create/select database `acp_photography`.
4. **For this supplied database:** import `database/acp_photography-existing.sql` after backing up any current database with the same name. This is the uploaded schema/data snapshot. If you already have live data, back it up and apply only the additive migration rather than overwriting your live database.
5. Import `database/migrations/2026_10_dashboard.sql` into `acp_photography` to add service packages, website pages, notifications and login-attempt tracking tables.
6. Edit `config/config.php` for database credentials and `APP_URL`.
7. Ensure `uploads/` is writable by PHP. Uploads are limited to 8 MB and validated as image MIME types.
8. The migration attempts to copy the existing public admin account into `admin_users` if the table is empty for that email. If no admin row is created, create one in phpMyAdmin. Generate a password hash with a temporary local PHP script using `password_hash('your-long-password', PASSWORD_DEFAULT)` and insert it:
   `INSERT INTO admin_users (name,email,password_hash,role) VALUES ('Studio Owner','you@example.com','PASTE_HASH_HERE','owner');`
9. Open `http://localhost/ACP-Photography/admin/login.php`.
10. Open the public site at `http://localhost/ACP-Photography/web/`.

## Existing schema compatibility
The admin login uses `admin_users` and is deliberately separate from public `users`. Existing tables such as `bookings`, `clients`, `client_galleries`, `offers`, `portfolio_items`, `reviews`, `site_events`, `site_settings`, and `social_posts` are preserved. The migration is additive.

## Included modules
- Dashboard overview with database counts and recent bookings
- Analytics charts based on recorded `site_events`
- Booking status updates
- Client, portfolio, reviews, social posts, offers, service packages and website page management
- Foto Owl guest/full-access link storage (no invented provider API)
- Appearance settings: logo/favicon/hero uploads, validated global colours, font families, base size, line-height, heading scale, contact details and SEO metadata
- Navigation/footer labels, notifications, admin activity log, CSV export, admin account and security guidance
- Responsive admin sidebar, mobile bottom navigation, light/dark preference, table search and role-aware delete controls

## Important limitations / production checklist
- Analytics show recorded events only. Zero means no event was recorded; it is not fabricated live traffic. If your public site is behind a consent requirement, add consent handling and a privacy notice before tracking.
- Automatic Instagram/Facebook feeds require official platform permissions/tokens. This package stores post links and manual thumbnails.
- Foto Owl access links open the actual supplied URL; there is no direct photo sync unless Foto Owl provides a supported API.
- Before public deployment, enable HTTPS, set secure cookies, move credentials to environment variables, add edge/server rate limiting, configure backups, and test all forms against your actual production schema.
- Run `php -l` on PHP files and test on a local database. This generated archive has been syntax-checked where PHP CLI is available, but cannot be runtime-tested against your local XAMPP instance from here.
## Customer account sign-in and registration
- Sign in: `http://localhost/ACP-Photography/auth/login.php`
- Create account: `http://localhost/ACP-Photography/auth/register.php`
- The public website navigation now shows **Sign in** and **Create account** for signed-out visitors. These are customer accounts in the `users` table; the studio admin dashboard uses its separate admin login.

