<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';

if (!empty($_COOKIE[REMEMBER_COOKIE]) && is_string($_COOKIE[REMEMBER_COOKIE])) {
    $parts = explode(':', $_COOKIE[REMEMBER_COOKIE], 2);
    if (!empty($parts[0]) && ctype_xdigit($parts[0])) {
        $st = $pdo->prepare('DELETE FROM remember_tokens WHERE selector = ?');
        $st->execute([$parts[0]]);
    }
}
forget_remember_cookie();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires'=>time()-42000,
        'path'=>$params['path'],
        'domain'=>$params['domain'] ?? '',
        'secure'=>(bool)$params['secure'],
        'httponly'=>(bool)$params['httponly'],
        'samesite'=>'Lax',
    ]);
}
session_destroy();
header('Location: ' . url('web/'));
exit;
