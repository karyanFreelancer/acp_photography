ACP PHOTOGRAPHY — Reference UI + PHP/MySQL

1. Extract to XAMPP htdocs/ACP-Photography.
2. Import your original acp_photography.sql if this is a fresh database.
3. Import database/ACP_REFERENCE_UPGRADE.sql for film and partner tables.
4. Configure config/config.php.
5. Open http://localhost/ACP-Photography/web/index.php
6. Admin: http://localhost/ACP-Photography/admin/index.php
7. Extra admin: /admin/studio-extra.php (films, partners, showreel, Instagram).

Existing admin dashboard still manages portfolio, bookings, reviews, client galleries, and site settings.
The supplied reference HTML/CSS has been kept as the visual basis, with PHP-powered sections added.
Video file not supplied in reference ZIP: set a public MP4 URL in studio-extra settings.
Gallery links are external Photoowl or other guest URLs, not an automatic API import.
The contact page points to the preserved original booking form (web/legacy-home.php#contact).
Some reference gallery tiles are decorative placeholders; publish real work through the admin.
