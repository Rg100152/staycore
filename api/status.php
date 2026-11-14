<?php
require_once __DIR__ . '/../config/session.php';
requireRole(['staff','manager','admin']);
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$roomId = (int)($data['room_id'] ?? 0);
$newStatus = $data['status'] ?? '';

if (!$roomId || !in_array($newStatus, ['Available','Reserved','Checked-In','Cleaning','Maintenance'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$pdo->prepare("UPDATE rooms SET status = ? WHERE id = ?")->execute([$newStatus, $roomId]);
echo json_encode(['success' => true]);