<?php
require_once __DIR__ . '/../config/session.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $stmt = $pdo->query("SELECT * FROM rooms ORDER BY room_number ASC");
    echo json_encode(['success' => true, 'rooms' => $stmt->fetchAll()]);
    exit;
}

if ($action === 'statuses') {
    $stmt = $pdo->query("SELECT id, room_number, status FROM rooms");
    echo json_encode(['success' => true, 'rooms' => $stmt->fetchAll()]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);