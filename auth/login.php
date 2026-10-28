<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';

if (current_user()) go('web/index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Enter a valid email address and password.';
    } else {
        $st = $pdo->prepare('SELECT id, password_hash FROM users WHERE email = ? LIMIT 1');
        $st->execute([$email]);
        $user = $st->fetch();

        if ($user && !empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
            if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
                $update = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
                $update->execute([password_hash($password, PASSWORD_DEFAULT), (int)$user['id']]);
            }
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
        try { track_event('login', '/auth/login.php'); } catch (Throwable $e) {}
            if (!empty($_POST['remember'])) remember_user((int)$user['id']);
            go('web/index.php');
        }
        $error = 'Email or password is incorrect.';
    }
}
$pageTitle = 'Welcome back';
require __DIR__ . '/_auth-view.php';
