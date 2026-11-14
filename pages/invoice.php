<?php
require_once __DIR__ . '/../config/session.php';
requireLogin();
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT r.*, u.name, u.email, ro.room_number, ro.type FROM invoices i JOIN reservations r ON i.reservation_id = r.id JOIN users u ON r.user_id = u.id JOIN rooms ro ON r.room_id = ro.id WHERE i.id = ? AND r.user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$inv = $stmt->fetch();

if (!$inv && $_SESSION['user_role'] !== 'admin') {
    header("Location: my_bookings.php"); exit;
}
if (!$inv) {
    $stmt = $pdo->prepare("SELECT r.*, u.name, u.email, ro.room_number, ro.type FROM invoices i JOIN reservations r ON i.reservation_id = r.id JOIN users u ON r.user_id = u.id JOIN rooms ro ON r.room_id = ro.id WHERE i.id = ?");
    $stmt->execute([$id]);
    $inv = $stmt->fetch();
}
if (!$inv) { header("Location: my_bookings.php"); exit; }

$charges = json_decode($inv['itemized_charges'] ?? '[]', true);
$guest = ['name' => $inv['name'], 'email' => $inv['email']];
$room = ['room_number' => $inv['room_number'], 'type' => $inv['type']];

// PDF download
if (isset($_GET['pdf'])) {
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../src/InvoicePDF.php';
    $pdfGen = new InvoicePDF();
    $result = $pdfGen->generate($inv, $charges, $guest, $room);
    if ($result['success']) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="invoice_' . $inv['id'] . '.pdf"');
        header('Content-Length: ' . strlen($result['pdf']));
        echo $result['pdf'];
        exit;
    } else {
        flash('error', 'PDF generation failed');
        header("Location: my_bookings.php");
        exit;
    }
}

$pageTitle = 'Invoice #' . $inv['id'];
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="glass rounded-2xl p-8 max-w-md mx-auto">
        <div class="flex justify-between items-start mb-8">
            <div>
                <div class="w-10 h-10 rounded-lg bg-crimson-600 flex items-center justify-center text-white font-bold mb-3">S</div>
                <h2 class="text-2xl font-bold text-white">StayCore Hotel</h2>
                <p class="text-slate-400 text-sm">Invoice Receipt</p>
            </div>
            <div class="text-right">
                <h3 class="text-xl font-bold text-white">Invoice #<?= $inv['id'] ?></h3>
                <p class="text-slate-400 text-sm"><?= date('M d, Y') ?></p>
                <a href="?id=<?= $id ?>&pdf=1" class="bg-crimson-600 hover:bg-crimson-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition inline-block mt-3">⬇ Download PDF</a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-8">
            <div>
                <p class="text-slate-400 text-xs uppercase tracking-wider mb-1">Bill To</p>
                <p class="text-white text-sm"><?= htmlspecialchars($inv['name']) ?></p>
                <p class="text-slate-400 text-sm"><?= htmlspecialchars($inv['email']) ?></p>
            </div>
            <div class="text-right">
                <p class="text-slate-400 text-xs uppercase tracking-wider mb-1">Room</p>
                <p class="text-white text-sm"><?= htmlspecialchars($inv['room_number']) ?></p>
                <p class="text-slate-400 text-sm"><?= htmlspecialchars($inv['type']) ?></p>
            </div>
        </div>

        <table class="w-full mb-8">
            <thead>
                <tr class="border-b border-white/10 text-left text-xs text-slate-400">
                    <th class="pb-3">Item</th><th class="pb-3 text-right">Qty</th><th class="pb-3 text-right">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php foreach ($charges as $c): ?>
                <tr>
                    <td class="py-3 text-slate-300 text-sm"><?= htmlspecialchars($c['label'] ?? 'Item') ?></td>
                    <td class="py-3 text-slate-300 text-sm text-right"><?= $c['qty'] ?? 1 ?></td>
                    <td class="py-3 text-slate-300 text-sm text-right">₹<?= number_format($c['amount'] ?? 0) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td class="py-3 text-slate-300 text-sm">Tourist Tax (12%)</td>
                    <td class="py-3 text-slate-300 text-sm text-right">1</td>
                    <td class="py-3 text-slate-300 text-sm text-right">₹<?= number_format($inv['tax_amount']) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-end">
            <div class="text-right">
                <p class="text-slate-400 text-sm">Total Paid</p>
                <h3 class="text-xl font-bold text-crimson-400">₹<?= number_format($inv['total_amount']) ?></h3>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-white/10 text-center">
            <a href="?id=<?= $id ?>&pdf=1" class="bg-crimson-600 hover:bg-crimson-700 text-white px-6 py-2 rounded-lg font-medium transition">Download PDF Invoice</a>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>