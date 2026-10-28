# ACP Photography — Header, Account & Profile Update

This update keeps the existing PHP/MySQL project. The public account controls are moved into the main navigation rather than appearing as a second row. Mobile Photoowl uses a fixed full-width bottom bar. Profile layout includes a cover, avatar, account settings, bookings, and an admin dashboard link for admin accounts. Public header/footer have been extracted into `web/includes/header.php` and `web/includes/footer.php` for the home page. Other public pages still need to be migrated to those shared includes to complete the reusable-component refactor.

## Setup
Back up your current folder and database, extract under XAMPP htdocs, confirm `config/config.php`, and visit `/ACP-Photography/web/index.php`. Existing DB is retained; do not reimport old SQL over live data.

## Important limitations
Google/Facebook OAuth still require provider credentials. Remember-me behavior depends on the preexisting remember token implementation and browser cookie settings. Photoowl facial recognition is not hosted on ACP; gallery links redirect to Photoowl. Full browser/database integration tests have not been performed. Existing admin dashboard features have not been rebuilt in this update.
