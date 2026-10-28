# Unified authentication patch
The public `users` table is now the source of truth for both customers and admins.
Set role to admin in phpMyAdmin for an existing trusted account:
`UPDATE users SET role='admin' WHERE id=YOUR_USER_ID;`
Admin login redirects to the website login modal. After login, users with role admin may enter /admin/index.php.
Google/Facebook OAuth still requires provider configuration.
IMPORTANT: this is a code patch; browser and MySQL end-to-end testing has not been performed.
