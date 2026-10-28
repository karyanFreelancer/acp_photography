<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';

if (current_user()) go('web/index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim((string)($_POST['name'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');

    if (mb_strlen($name) < 2 || mb_strlen($name) > 120 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 10) {
        $error = 'Enter your name, a valid email, and a password with at least 10 characters.';
    } else {
        try {
            $st = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
            $st->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), 'user']);
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$pdo->lastInsertId();
            go('web/index.php');
        } catch (PDOException $ex) {
            if ($ex->getCode() === '23000') {
                $error = 'An account with this email already exists. Please sign in instead.';
            } else {
                error_log('ACP registration error: ' . $ex->getMessage());
                $error = 'We could not create your account right now. Please try again.';
            }
        }
    }
}
$pageTitle = 'Create your account';
require __DIR__ . '/_auth-view.php';
