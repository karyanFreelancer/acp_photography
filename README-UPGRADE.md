# ACP Photography Full Stack Upgrade

1. Copy ACP-Photography into XAMPP htdocs.
2. Import original database/acp_photography-existing.sql OR your supplied acp_photography.sql into database acp_photography.
3. Import database/ACP_UPGRADE.sql to add five new content tables.
4. Edit config/config.php APP_URL, DB_HOST, DB_NAME, DB_USER and DB_PASS.
5. Visit http://localhost/ACP-Photography/web/ and http://localhost/ACP-Photography/admin/.
6. Admin > Studio Content manages services/pricing, partners, video links, FAQs and banners. Admin > Settings manages logo, favicon, hero images and colors. Admin > Galleries manages client delivery URLs such as Photoowl.
7. New sections show empty states until actual content is entered. Do not publish fabricated reviews or partner logos.
8. Uploading a Photoowl URL is a link-out, not an API integration or automatic photo import. YouTube/Instagram links open externally. For native playback use a locally hosted MP4 URL.
9. Brand assets supplied in web/assets/images; default logo and favicon are used unless overridden in Admin Settings.
10. Test on your local PHP 8.2 + MySQL/MariaDB installation before production deployment.
