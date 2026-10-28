<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';

if (!defined('GOOGLE_CLIENT_ID') || GOOGLE_CLIENT_ID === '' || GOOGLE_CLIENT_ID === 'YOUR_GOOGLE_CLIENT_ID' ||
    !defined('GOOGLE_CLIENT_SECRET') || GOOGLE_CLIENT_SECRET === '' || GOOGLE_CLIENT_SECRET === 'YOUR_GOOGLE_CLIENT_SECRET') {
    http_response_code(503);
    exit('Google sign-in is not configured. Add OAuth credentials in config/config.php.');
}
$_SESSION['google_oauth_state'] = bin2hex(random_bytes(32));
$_SESSION['google_oauth_created'] = time();
$params = [
    'client_id' => GOOGLE_CLIENT_ID,
    'redirect_uri' => url('auth/google-callback.php'),
    'response_type' => 'code',
    'scope' => 'openid email profile',
    'state' => $_SESSION['google_oauth_state'],
    'prompt' => 'select_account',
];
header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
exit;
