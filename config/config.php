<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params(['lifetime'=>0, 'path'=>'/', 'secure'=>$https, 'httponly'=>true, 'samesite'=>'Lax']);
    session_start();
}

define('APP_NAME', 'ACP Photography');
define('APP_URL', 'http://localhost/ACP-Photography'); // Update for your domain.
define('COOKIE_SECURE', false); // Set true on HTTPS production.
define('REMEMBER_COOKIE', 'acp_remember');
define('REMEMBER_DAYS', 30);

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'acp_photography');
define('DB_USER', 'root');
define('DB_PASS', '');

define('GOOGLE_CLIENT_ID', 'YOUR_GOOGLE_CLIENT_ID');
define('GOOGLE_CLIENT_SECRET', 'YOUR_GOOGLE_CLIENT_SECRET');
define('FACEBOOK_APP_ID', 'YOUR_FACEBOOK_APP_ID');
define('FACEBOOK_APP_SECRET', 'YOUR_FACEBOOK_APP_SECRET');

try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES=>false
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Check config/config.php and import database/schema.sql.');
}
require_once __DIR__.'/functions.php';
require_once __DIR__.'/auth.php';
require_once __DIR__.'/admin.php';
