<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';

function oauth_fail(string $message, int $status = 400): never {
    http_response_code($status);
    exit(e($message));
}
function oauth_http_post(string $url, array $data): array {
    if (!function_exists('curl_init')) oauth_fail('PHP cURL is required for social sign-in.', 500);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);
    $body = curl_exec($ch);
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $json = is_string($body) ? json_decode($body, true) : null;
    if ($status < 200 || $status >= 300 || !is_array($json)) oauth_fail('Google sign-in could not be completed. Please try again.', 502);
    return $json;
}
$state = (string)($_GET['state'] ?? '');
$expected = (string)($_SESSION['google_oauth_state'] ?? '');
$created = (int)($_SESSION['google_oauth_created'] ?? 0);
unset($_SESSION['google_oauth_state'], $_SESSION['google_oauth_created']);
if ($state === '' || $expected === '' || !hash_equals($expected, $state) || $created < time() - 600) oauth_fail('Google sign-in expired. Please try again.');
if (!empty($_GET['error'])) oauth_fail('Google sign-in was cancelled.');
$code = (string)($_GET['code'] ?? '');
if ($code === '') oauth_fail('Google did not return an authorization code.');

$tokens = oauth_http_post('https://oauth2.googleapis.com/token', [
    'code' => $code,
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => url('auth/google-callback.php'),
    'grant_type' => 'authorization_code',
]);
$idToken = (string)($tokens['id_token'] ?? '');
if ($idToken === '') oauth_fail('Google did not return a valid identity token.');
$ch = curl_init('https://oauth2.googleapis.com/tokeninfo?id_token=' . rawurlencode($idToken));
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_CONNECTTIMEOUT=>8, CURLOPT_TIMEOUT=>15, CURLOPT_SSL_VERIFYPEER=>true, CURLOPT_SSL_VERIFYHOST=>2]);
$body = curl_exec($ch); $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
$identity = is_string($body) ? json_decode($body, true) : null;
if ($status !== 200 || !is_array($identity) || !hash_equals((string)GOOGLE_CLIENT_ID, (string)($identity['aud'] ?? '')) ||
    empty($identity['sub']) || empty($identity['email']) || ($identity['email_verified'] ?? '') !== 'true') {
    oauth_fail('Google identity could not be verified.', 401);
}
$email = strtolower((string)$identity['email']);
$name = trim((string)($identity['name'] ?? 'Google user'));
$providerId = (string)$identity['sub'];
$avatar = isset($identity['picture']) ? (string)$identity['picture'] : null;

$pdo->beginTransaction();
try {
    $find = $pdo->prepare('SELECT id, provider, provider_id FROM users WHERE email = ? LIMIT 1 FOR UPDATE');
    $find->execute([$email]); $user = $find->fetch();
    if ($user) {
        if (!empty($user['provider_id']) && ($user['provider'] !== 'google' || !hash_equals((string)$user['provider_id'], $providerId))) {
            $pdo->rollBack(); oauth_fail('This email is linked to another sign-in method. Use your original method.');
        }
        $update = $pdo->prepare("UPDATE users SET provider='google', provider_id=?, avatar=COALESCE(avatar, ?) WHERE id=?");
        $update->execute([$providerId, $avatar, (int)$user['id']]);
        $userId = (int)$user['id'];
    } else {
        $insert = $pdo->prepare("INSERT INTO users (name,email,password_hash,role,provider,provider_id,avatar) VALUES (?,?,NULL,'user','google',?,?)");
        $insert->execute([$name !== '' ? $name : 'Google user', $email, $providerId, $avatar]);
        $userId = (int)$pdo->lastInsertId();
    }
    $pdo->commit();
} catch (Throwable $ex) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log('Google OAuth callback: ' . $ex->getMessage());
    oauth_fail('Unable to sign in with Google right now.', 500);
}
session_regenerate_id(true);
$_SESSION['user_id'] = $userId;
go('web/index.php');
