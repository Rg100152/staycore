<?php
require_once __DIR__ . '/../config/session.php';
requireLogin();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Payment.php';

$input = json_decode(file_get_contents('php://input'), true);
$intentId = $input['intent_id'] ?? '';

if (!$intentId || !isset($_SESSION['pending_booking'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$payment = new Payment($pdo, 'pk_test_placeholder', 'sk_test_placeholder');
$verify = $payment->verifyIntent($intentId);

if (!$verify['success'] || $verify['status'] !== 'succeeded') {
    echo json_encode(['success' => false, 'message' => 'Payment not verified']);
    exit;
}

$b = $_SESSION['pending_booking'];

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("INSERT INTO reservations (user_id, room_id, check_in_date, check_out_date, total_amount, payment_status, status) VALUES (?, ?, ?, ?, ?, 'Paid', 'Confirmed')");
    $stmt->execute([$b['user_id'], $b['room_id'], $b['check_in_date'], $b['check_out_date'], $b['total_amount']]);
    $resId = $pdo->lastInsertId();

    $pdo->prepare("UPDATE rooms SET status = 'Reserved' WHERE id = ?")->execute([$b['room_id']]);

    $charges = json_encode([['label'=>'Room Charge','amount'=>$price = $b['total_amount'] / $b['nights'],'qty'=>$b['nights']]]);
    $tax = round($b['total_amount'] * 0.12, 2);
    $pdo->prepare("INSERT INTO invoices (reservation_id, itemized_charges, tax_amount, total_amount, status) VALUES (?, ?, ?, ?, 'Paid')")
        ->execute([$resId, $charges, $tax, $b['total_amount'] + $tax]);

    $pdo->prepare("INSERT INTO housekeeping_tasks (room_id, status, priority) VALUES (?, 'Pending', 'Medium')")->execute([$b['room_id']]);

    $pdo->commit();
    unset($_SESSION['pending_booking']);
    echo json_encode(['success' => true, 'reservation_id' => $resId]);
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}