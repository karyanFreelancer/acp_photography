ACP PHOTOGRAPHY — CMS FIX
=========================
1. Back up your current MySQL database and existing htdocs/ACP-Photography folder.
2. Extract this full ZIP to htdocs/ACP-Photography (keep your existing config credentials).
3. If ACP_CONTENT_V2.sql was not imported, import database/ACP_CONTENT_V2.sql first.
4. Import database/ACP_CONTENT_V3.sql into THE SAME database. It adds a category column to acp_content_items. It is safe to re-run.
5. Open /ACP-Photography/admin/content-manager.php?kind=gallery and upload a photo with the correct category.
6. Open /ACP-Photography/web/gallery.php to see live published photos and working category filters.
7. Add brand logos using kind=brand, and reviews using kind=testimonial. Published items now appear in the homepage/gallery/about/services marquee.
8. Public pages share web/includes/site-header.php and site-footer.php; index uses includes/header.php and footer.php wrappers.
9. Admin dashboard already contains a 7-day Chart.js activity chart and admin/account.php allows updating name, email and password.
10. This release was syntax checked, not tested against your live XAMPP/MySQL data. Google/Facebook OAuth still requires provider credentials.
