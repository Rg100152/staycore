<?php
require_once __DIR__ . '/../config/session.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') header("Location: login.php");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    flash('success', 'Welcome back, ' . htmlspecialchars($user['name']));
    $redirect = $_GET['redirect'] ?? null;
    $role = $user['role'];
    if ($redirect && strpos($redirect, '/staycore/') === 0) {
        header("Location: $redirect");
    } else {
        header("Location: /staycore/pages/{$role}_dashboard.php");
    }
    exit;
} else {
    flash('error', 'Invalid email or password');
    header("Location: login.php");
    exit;
}