<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';
if (!defined('FACEBOOK_APP_ID') || FACEBOOK_APP_ID === '' || FACEBOOK_APP_ID === 'YOUR_FACEBOOK_APP_ID' ||
    !defined('FACEBOOK_APP_SECRET') || FACEBOOK_APP_SECRET === '' || FACEBOOK_APP_SECRET === 'YOUR_FACEBOOK_APP_SECRET') {
    http_response_code(503);
    exit('Facebook sign-in is not configured. Add OAuth credentials in config/config.php.');
}
$_SESSION['facebook_oauth_state'] = bin2hex(random_bytes(32));
$_SESSION['facebook_oauth_created'] = time();
$params = [
    'client_id' => FACEBOOK_APP_ID,
    'redirect_uri' => url('auth/facebook-callback.php'),
    'response_type' => 'code',
    'scope' => 'email,public_profile',
    'state' => $_SESSION['facebook_oauth_state'],
];
header('Location: https://www.facebook.com/v22.0/dialog/oauth?' . http_build_query($params));
exit;
