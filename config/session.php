<?php
// Session start + RBAC helper
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /staycore/auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI'] ?? '/'));
        exit;
    }
}

function requireRole($allowedRoles) {
    requireLogin();
    $userRole = $_SESSION['user_role'] ?? 'guest';
    if (!is_array($allowedRoles)) {
        $allowedRoles = [$allowedRoles];
    }
    if (!in_array($userRole, $allowedRoles, true)) {
        http_response_code(403);
        include __DIR__ . '/../includes/403.php';
        exit;
    }
}

function getCurrentUser() {
    if (!isLoggedIn()) return null;
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, name, email, phone, role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function flash($key, $value = null) {
    if ($value === null) {
        $val = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $val;
    }
    $_SESSION['flash'][$key] = $value;
}
?>