<?php
require_once __DIR__ . '/../config/session.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') header("Location: register.php");

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';

if (!$name || !$email || !$password) {
    flash('error', 'Please fill all required fields');
    header("Location: register.php");
    exit;
}

$exists = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$exists->execute([$email]);
if ($exists->fetch()) {
    flash('error', 'Email already registered');
    header("Location: register.php");
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'guest')");
$stmt->execute([$name, $email, $phone, $hash]);

flash('success', 'Account created successfully. Please log in.');
header("Location: login.php");
exit;