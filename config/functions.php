<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| ACP Photography — Shared Functions
|--------------------------------------------------------------------------
| Requirements:
| - PHP 8.1+
| - config.php must define APP_URL, REMEMBER_COOKIE,
|   COOKIE_SECURE and REMEMBER_DAYS.
| - PDO connection must be available as $pdo.
| - Database tables: users, remember_tokens.
|--------------------------------------------------------------------------
*/

/* Start session before accessing $_SESSION or sending cookies. */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* Escape HTML output. */
function e(?string $value): string
{
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

/* Build application URL. */
function url(string $path = ''): string
{
    if (!defined('APP_URL')) {
        throw new RuntimeException('APP_URL is not configured.');
    }

    return rtrim((string) APP_URL, '/') . '/' . ltrim($path, '/');
}

/* Redirect to an internal path or an explicitly supplied URL. */
function go(string $path): never
{
    if (preg_match('~^https?://~i', $path)) {
        header('Location: ' . $path);
    } else {
        header('Location: ' . url($path));
    }

    exit;
}

/* CSRF token. */
function csrf_token(): string
{
    if (
        !isset($_SESSION['_csrf']) ||
        !is_string($_SESSION['_csrf']) ||
        $_SESSION['_csrf'] === ''
    ) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

/* Validate CSRF token on POST requests. */
function csrf_check(): void
{
    $sent = $_POST['_csrf'] ?? '';
    $stored = $_SESSION['_csrf'] ?? '';

    if (
        !is_string($sent) ||
        !is_string($stored) ||
        $stored === '' ||
        !hash_equals($stored, $sent)
    ) {
        http_response_code(419);
        exit('This form expired. Refresh the page and try again.');
    }
}

/* Flash messages. */
function flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['_flash'][$key] = $value;
        return null;
    }

    $message = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);

    return is_string($message) ? $message : null;
}

/* Get the currently logged-in user. */
function current_user(): ?array
{
    global $pdo;

    if (empty($_SESSION['user_id'])) {
        return null;
    }

    static $cachedUserId = null;
    static $cachedUser = null;

    $userId = (int) $_SESSION['user_id'];

    if ($cachedUserId === $userId) {
        return $cachedUser;
    }

    $stmt = $pdo->prepare(
        'SELECT id, name, email, role, avatar
         FROM users
         WHERE id = ?
         LIMIT 1'
    );

    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $cachedUserId = $userId;
    $cachedUser = $user ?: null;

    if (!$cachedUser) {
        unset($_SESSION['user_id']);
    }

    return $cachedUser;
}

/* Require an authenticated client. */
function require_login(): void
{
    if (!current_user()) {
        go('auth/login.php');
    }
}

/* Admin dashboard authentication is defined separately in config/admin.php. */

/* Remember Me cookie helper. */
function remember_cookie(string $value, int $expires): void
{
    if (!defined('REMEMBER_COOKIE') || !defined('COOKIE_SECURE')) {
        throw new RuntimeException(
            'Remember Me cookie settings are missing from config.php.'
        );
    }

    setcookie((string) REMEMBER_COOKIE, $value, [
        'expires'  => $expires,
        'path'     => '/',
        'secure'   => (bool) COOKIE_SECURE,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

/* Clear Remember Me cookie. */
function forget_remember_cookie(): void
{
    if (!defined('REMEMBER_COOKIE') || !defined('COOKIE_SECURE')) {
        return;
    }

    setcookie((string) REMEMBER_COOKIE, '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'secure'   => (bool) COOKIE_SECURE,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

/* Create a persistent login token. */
function remember_user(int $userId): void
{
    global $pdo;

    if (!defined('REMEMBER_DAYS')) {
        throw new RuntimeException(
            'REMEMBER_DAYS is missing from config.php.'
        );
    }

    $selector  = bin2hex(random_bytes(9));
    $validator = bin2hex(random_bytes(32));
    $expires   = time() + ((int) REMEMBER_DAYS * 86400);

    $stmt = $pdo->prepare(
        'INSERT INTO remember_tokens
            (user_id, selector, validator_hash, expires_at)
         VALUES (?, ?, ?, FROM_UNIXTIME(?))'
    );

    $stmt->execute([
        $userId,
        $selector,
        hash('sha256', $validator),
        $expires,
    ]);

    remember_cookie(
        $selector . ':' . $validator,
        $expires
    );
}

/* Restore login from a valid Remember Me cookie. */
function restore_remembered_user(): void
{
    global $pdo;

    if (
        !empty($_SESSION['user_id']) ||
        empty($_COOKIE[REMEMBER_COOKIE])
    ) {
        return;
    }

    $cookie = $_COOKIE[REMEMBER_COOKIE];

    if (!is_string($cookie)) {
        forget_remember_cookie();
        return;
    }

    $parts = explode(':', $cookie, 2);

    if (
        count($parts) !== 2 ||
        !ctype_xdigit($parts[0]) ||
        !ctype_xdigit($parts[1]) ||
        strlen($parts[0]) !== 18 ||
        strlen($parts[1]) !== 64
    ) {
        forget_remember_cookie();
        return;
    }

    [$selector, $validator] = $parts;

    $stmt = $pdo->prepare(
        'SELECT user_id, validator_hash
         FROM remember_tokens
         WHERE selector = ?
           AND expires_at > NOW()
         LIMIT 1'
    );

    $stmt->execute([$selector]);
    $token = $stmt->fetch(PDO::FETCH_ASSOC);

    if (
        !$token ||
        !hash_equals(
            (string) $token['validator_hash'],
            hash('sha256', $validator)
        )
    ) {
        if ($token) {
            $delete = $pdo->prepare(
                'DELETE FROM remember_tokens WHERE selector = ?'
            );
            $delete->execute([$selector]);
        }

        forget_remember_cookie();
        return;
    }

    $userId = (int) $token['user_id'];

    /* Confirm that the account still exists. */
    $userStmt = $pdo->prepare(
        'SELECT id FROM users WHERE id = ? LIMIT 1'
    );
    $userStmt->execute([$userId]);

    if (!$userStmt->fetchColumn()) {
        $delete = $pdo->prepare(
            'DELETE FROM remember_tokens WHERE selector = ?'
        );
        $delete->execute([$selector]);

        forget_remember_cookie();
        return;
    }

    /* Consume the old token before rotating it. */
    $delete = $pdo->prepare(
        'DELETE FROM remember_tokens WHERE selector = ?'
    );
    $delete->execute([$selector]);

    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;

    /* Issue a fresh token. */
    remember_user($userId);
}
