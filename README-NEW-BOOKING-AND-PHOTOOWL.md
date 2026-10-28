# ACP Full Stack — Booking & Photoowl Update

1. Copy `ACP-Photography` to `xampp/htdocs/ACP-Photography`.
2. Import the original database SQL and existing upgrade migrations from the previous release. Keep your existing database records.
3. Confirm `config/config.php` database credentials and APP_URL.
4. Open `/ACP-Photography/web/index.php`.
5. **Book a Shoot** now opens a modal; sign-in/sign-up is required and booking is inserted into MySQL.
6. After submitting, the client lands on `web/profile.php` to see booking status, update profile, and change password.
7. The fixed **Find My Photos** bar displays active client gallery links from `client_galleries`; they open on Photoowl or the linked external gallery website. **Face scanning is not implemented on this site** and is only available if the external gallery provider supports it.
8. Google/Facebook require valid provider credentials in `config/config.php` and authorized redirect URIs. They are not automatically active.
9. The admin dashboard includes existing content managers plus Studio Content and Films & Partners links. Test uploads and permissions before public deployment.
10. Use HTTPS, configure secure cookies, and test complete booking and OAuth flows before production.
