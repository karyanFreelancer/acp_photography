<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';

function fb_fail(string $message, int $status = 400): never {
    http_response_code($status);
    exit(e($message));
}
function fb_get_json(string $url): array {
    if (!function_exists('curl_init')) fb_fail('PHP cURL is required for social sign-in.', 500);
    $ch = curl_init($url);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_CONNECTTIMEOUT=>8, CURLOPT_TIMEOUT=>15, CURLOPT_SSL_VERIFYPEER=>true, CURLOPT_SSL_VERIFYHOST=>2]);
    $body = curl_exec($ch); $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
    $data = is_string($body) ? json_decode($body, true) : null;
    if ($status < 200 || $status >= 300 || !is_array($data) || isset($data['error'])) fb_fail('Facebook sign-in could not be completed.', 502);
    return $data;
}
$state = (string)($_GET['state'] ?? '');
$expected = (string)($_SESSION['facebook_oauth_state'] ?? '');
$created = (int)($_SESSION['facebook_oauth_created'] ?? 0);
unset($_SESSION['facebook_oauth_state'], $_SESSION['facebook_oauth_created']);
if ($state === '' || $expected === '' || !hash_equals($expected, $state) || $created < time() - 600) fb_fail('Facebook sign-in expired. Please try again.');
if (!empty($_GET['error'])) fb_fail('Facebook sign-in was cancelled.');
$code = (string)($_GET['code'] ?? '');
if ($code === '') fb_fail('Facebook did not return an authorization code.');

$token = fb_get_json('https://graph.facebook.com/v22.0/oauth/access_token?' . http_build_query([
    'client_id'=>FACEBOOK_APP_ID,
    'client_secret'=>FACEBOOK_APP_SECRET,
    'redirect_uri'=>url('auth/facebook-callback.php'),
    'code'=>$code,
]));
$accessToken = (string)($token['access_token'] ?? '');
if ($accessToken === '') fb_fail('Facebook did not return an access token.');
$profile = fb_get_json('https://graph.facebook.com/me?' . http_build_query([
    'fields'=>'id,name,email,picture.type(large)',
    'access_token'=>$accessToken,
]));
if (empty($profile['id']) || empty($profile['email'])) fb_fail('Facebook did not provide an email. Check app permissions or use email/password sign-in.');
$email = strtolower((string)$profile['email']);
$name = trim((string)($profile['name'] ?? 'Facebook user'));
$providerId = (string)$profile['id'];
$avatar = $profile['picture']['data']['url'] ?? null;

$pdo->beginTransaction();
try {
    $find = $pdo->prepare('SELECT id, provider, provider_id FROM users WHERE email = ? LIMIT 1 FOR UPDATE');
    $find->execute([$email]); $user = $find->fetch();
    if ($user) {
        if (!empty($user['provider_id']) && ($user['provider'] !== 'facebook' || !hash_equals((string)$user['provider_id'], $providerId))) {
            $pdo->rollBack(); fb_fail('This email is linked to another sign-in method. Use your original method.');
        }
        $update = $pdo->prepare("UPDATE users SET provider='facebook', provider_id=?, avatar=COALESCE(avatar, ?) WHERE id=?");
        $update->execute([$providerId, $avatar, (int)$user['id']]);
        $userId = (int)$user['id'];
    } else {
        $insert = $pdo->prepare("INSERT INTO users (name,email,password_hash,role,provider,provider_id,avatar) VALUES (?,?,NULL,'user','facebook',?,?)");
        $insert->execute([$name !== '' ? $name : 'Facebook user', $email, $providerId, $avatar]);
        $userId = (int)$pdo->lastInsertId();
    }
    $pdo->commit();
} catch (Throwable $ex) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log('Facebook OAuth callback: ' . $ex->getMessage());
    fb_fail('Unable to sign in with Facebook right now.', 500);
}
session_regenerate_id(true);
$_SESSION['user_id'] = $userId;
go('web/index.php');
