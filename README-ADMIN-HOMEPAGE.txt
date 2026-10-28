ACP PHOTOGRAPHY - HOMEPAGE CMS UPDATE

1. Backup your old project and database.
2. Copy ACP-Photography folder to XAMPP htdocs.
3. Import your existing database/schema and database/ACP_UPGRADE.sql if studio_* tables do not exist.
4. Login to website with a user whose role is admin.
5. Open /ACP-Photography/admin/homepage.php to edit hero, video, Instagram, poster.
6. Open /ACP-Photography/admin/studio-content.php to edit services, partner logos, extra videos, FAQ, banners.
7. /ACP-Photography/admin/account.php updates the same users record as website login.
8. Real partner logos/reviews must be entered by admin; no fictitious testimonials are inserted.
9. Uploaded showreels are limited to 80 MB and PHP upload_max_filesize/post_max_size may need increasing.
10. Test in local PHP/MySQL browser before production deployment.
