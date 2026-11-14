<?php
require_once __DIR__ . '/../config/session.php';
requireRole(['staff','manager','admin']);
header('Content-Type: application/json');

// HTTP trigger to push WebSocket update to all connected clients
// In production, run the websocket_server.php and call this endpoint after status changes.

$data = json_decode(file_get_contents('php://input'), true);
$roomId = (int)($data['room_id'] ?? 0);
$status = $data['status'] ?? '';

if (!$roomId || !in_array($status, ['Available','Reserved','Checked-In','Cleaning','Maintenance'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Push to WebSocket server via its control socket or Redis pub/sub in production
// Here we just update DB and return success; frontend polls + websocket handles push
$pdo->prepare("UPDATE rooms SET status = ? WHERE id = ?")->execute([$status, $roomId]);

echo json_encode(['success' => true, 'room_id' => $roomId, 'status' => $status]);