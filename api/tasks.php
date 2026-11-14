<?php
require_once __DIR__ . '/../config/session.php';
requireRole(['staff','manager','admin']);
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$taskId = (int)($data['task_id'] ?? 0);
$newStatus = $data['status'] ?? '';

if (!$taskId || !in_array($newStatus, ['Pending','In Progress','Ready'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$pdo->prepare("UPDATE housekeeping_tasks SET status = ? WHERE id = ?")->execute([$newStatus, $taskId]);
echo json_encode(['success' => true]);