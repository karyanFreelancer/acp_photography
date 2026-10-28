# ACP Photography — Studio Management System

A professional photography studio management platform built with **PHP 8.1+, MySQL/MariaDB, PDO, Vanilla JavaScript, Chart.js, GSAP, and Lenis**. It includes a public-facing photography website, a secure admin dashboard, customer authentication, booking management, portfolio management, analytics, and database-driven website customization.

## 🌐 Live Website

**[Visit ACP Photography — Official Website](https://acpphotography.site.je/ACP-Photography/web/)**

Explore the public photography portfolio, website navigation, gallery, and customer-facing features.

## 🔐 Login & Account Access

- **[Open the Admin Dashboard Login](https://acpphotography.site.je/ACP-Photography/admin/login.php)** — For authorized studio administrators.
- **[Customer Sign In](https://acpphotography.site.je/ACP-Photography/auth/login.php)** — For registered customers.
- **[Create a Customer Account](https://acpphotography.site.je/ACP-Photography/auth/register.php)** — Register a new customer account.

> **Note:** Live links require the corresponding files and application routes to be deployed on the hosting server. Admin access requires a valid administrator account.

---

## 1. Technology Stack

- PHP 8.1+
- MySQL / MariaDB
- PDO with prepared statements
- HTML5, CSS3 and Vanilla JavaScript
- Chart.js for analytics visualizations
- GSAP and Lenis for public-page animations and smooth scrolling
- Shared CSS variables for global theme customization
- Database-driven appearance and website settings

## 2. Local Installation — XAMPP

### Step 1 — Install the project

Extract the project folder into:

`C:\xampp\htdocs\ACP-Photography\`

### Step 2 — Start the server

Open XAMPP Control Panel and start:

- Apache
- MySQL

### Step 3 — Create the database

Open [phpMyAdmin](http://localhost/phpmyadmin/) and create or select the database:

`acp_photography`

### Step 4 — Import the database

For a fresh installation using the supplied database snapshot:

1. Back up any existing database named `acp_photography`.
2. Import `database/acp_photography-existing.sql`.
3. Import `database/migrations/2026_10_dashboard.sql` into the same database.

**Important:** The existing SQL file may replace or conflict with current data if imported into a live database. For an existing installation, back up the database and apply only the appropriate additive migration after reviewing its SQL statements.

### Step 5 — Configure the application

Edit:

`config/config.php`

Set the correct database credentials and application URL for your environment.

For local development, the application URL will generally be:

`http://localhost/ACP-Photography`

### Step 6 — Configure uploads

Ensure PHP can write to the `uploads/` directory. Image uploads should remain subject to the application's 8 MB size limit and server-side MIME validation.

### Step 7 — Configure the administrator account

The migration attempts to copy a compatible existing admin account into `admin_users` when the relevant conditions are met.

If no administrator account is created, generate a password hash using a temporary local PHP script:

`password_hash('your-long-password', PASSWORD_DEFAULT)`

Insert the account using the generated hash:

```sql
INSERT INTO admin_users (name, email, password_hash, role)
VALUES (
    'Studio Owner',
    'you@example.com',
    'PASTE_HASH_HERE',
    'owner'
);
```

Replace the example email and password hash with your own values. Never store a plaintext password in the database.

Delete the temporary password-generation script after use.

### Step 8 — Open the application

- **Public website:** [http://localhost/ACP-Photography/web/](http://localhost/ACP-Photography/web/)
- **Admin dashboard:** [http://localhost/ACP-Photography/admin/login.php](http://localhost/ACP-Photography/admin/login.php)
- **Customer login:** [http://localhost/ACP-Photography/auth/login.php](http://localhost/ACP-Photography/auth/login.php)
- **Customer registration:** [http://localhost/ACP-Photography/auth/register.php](http://localhost/ACP-Photography/auth/register.php)

---

## 3. Admin Dashboard Features

### Dashboard & Analytics
- Database-driven dashboard overview
- Booking and client statistics
- Recent booking activity
- Analytics charts based on recorded website events

### Booking & Client Management
- View and update booking statuses
- Manage customer and client records
- Manage client galleries
- Store guest-access and full-access Foto Owl links

### Website Content Management
- Add, edit and manage portfolio photographs
- Manage reviews and social posts
- Create and update special offers
- Manage service packages and website pages
- Export supported data to CSV

### Appearance & Branding
- Upload and update the website logo and favicon
- Upload and manage hero images
- Customize global colours through shared CSS variables
- Configure font families, base font size, line height and heading scale
- Update contact information and SEO metadata
- Customize navigation and footer labels

### Admin Experience & Security
- Responsive admin sidebar
- Mobile-friendly bottom navigation
- Light and dark appearance preferences
- Table search and role-aware delete controls
- Notifications and admin activity logs
- Admin account management and security guidance

## 4. Database Compatibility

The admin authentication system uses the separate `admin_users` table. Customer authentication uses the `users` table.

The existing application schema includes tables such as:

`bookings`, `clients`, `client_galleries`, `offers`, `portfolio_items`, `reviews`, `site_events`, `site_settings`, and `social_posts`.

The dashboard migration is designed to add supporting tables for service packages, website pages, notifications and login-attempt tracking while preserving compatible existing data. Review the SQL and verify the schema before applying it to an existing production database.

## 5. Important Notes & Limitations

- **Analytics:** Charts reflect recorded `site_events`. No recorded events means no data is available for that metric; traffic is not fabricated.
- **Instagram and Facebook:** Automatic feeds require supported official APIs, permissions and tokens. Manual post links and thumbnails can be stored independently.
- **Foto Owl:** Stored access links open the supplied destination. Direct photo synchronization requires a supported provider API.
- **Customer accounts:** Public customer registration and login are separate from studio administrator authentication.
- **Hosting:** The live website and its admin routes must be deployed and configured on the server before their links will work.

## 6. Production Deployment Checklist

Before making the application publicly available:

- Enable HTTPS and secure session cookies.
- Keep database credentials and secrets outside publicly accessible files.
- Apply appropriate server-side validation and authorization checks.
- Configure rate limiting for login and other sensitive endpoints.
- Verify upload restrictions and prevent execution of uploaded files.
- Configure regular database and uploaded-file backups.
- Add an appropriate privacy notice and consent handling where required.
- Test registration, login, bookings, uploads, permissions and all database operations against the actual hosting environment.
- Run PHP syntax checks and complete functional testing before deployment.

## 7. Local Development Requirements

- PHP 8.1 or newer
- MySQL or MariaDB
- Apache with the required PHP extensions
- A modern browser
- Writable upload directories
- Correct database credentials and application URL

**Project:** ACP Photography — Studio Management System

**Live Website:** https://acpphotography.site.je/ACP-Photography/web/

**Local Environment:** XAMPP on Windows

**Backend:** PHP + MySQL/MariaDB + PDO

**Frontend:** HTML, CSS, JavaScript, Chart.js, GSAP and Lenis
