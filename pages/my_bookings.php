<?php
require_once __DIR__ . '/../config/session.php';
requireLogin();
$pageTitle = 'My Bookings';
include __DIR__ . '/../includes/header.php';

$stmt = $pdo->prepare("SELECT r.*, ro.room_number, ro.type, ro.price_per_night FROM reservations r JOIN rooms ro ON r.room_id = ro.id WHERE r.user_id = ? ORDER BY r.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();
?>
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">My Bookings</h1>
            <p class="text-slate-400">Manage your past and upcoming stays.</p>
        </div>
        <a href="rooms.php" class="bg-crimson-600 hover:bg-crimson-700 text-white px-5 py-2 rounded-lg font-medium transition">Book New Room</a>
    </div>

    <div class="glass rounded-2xl p-6">
        <?php if (count($bookings)): ?>
        <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/10 text-slate-400 text-sm">
                    <th class="pb-3 font-medium">Booking #</th>
                    <th class="pb-3 font-medium">Room</th>
                    <th class="pb-3 font-medium">Type</th>
                    <th class="pb-3 font-medium">Check-in</th>
                    <th class="pb-3 font-medium">Check-out</th>
                    <th class="pb-3 font-medium">Total</th>
                    <th class="pb-3 font-medium">Status</th>
                    <th class="pb-3 font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                <tr class="border-b border-white/5 hover:bg-white/5 transition group">
                    <td class="py-4 text-slate-300">#<?= $b['id'] ?></td>
                    <td class="py-4 text-white font-medium"><?= htmlspecialchars($b['room_number']) ?></td>
                    <td class="py-4 text-slate-300"><?= htmlspecialchars($b['type']) ?></td>
                    <td class="py-4 text-slate-300"><?= htmlspecialchars($b['check_in_date']) ?></td>
                    <td class="py-4 text-slate-300"><?= htmlspecialchars($b['check_out_date']) ?></td>
                    <td class="py-4 text-crimson-400 font-medium">₹<?= number_format($b['total_amount']) ?></td>
                    <td class="py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold border
                            <?= $b['status']==='Confirmed'?'bg-emerald-500/20 text-emerald-400 border-emerald-500/30':($b['status']==='Checked-In'?'bg-blue-500/20 text-blue-400 border-blue-500/30':($b['status']==='Checked-Out'?'bg-slate-500/20 text-slate-400 border-slate-500/30':'bg-rose-500/20 text-rose-400 border-rose-500/30')) ?>">
                            <?= ucfirst(htmlspecialchars($b['status'])) ?>
                        </span>
                    </td>
                    <td class="py-4">
                        <a href="invoice.php?id=<?= $b['id'] ?>" class="text-crimson-400 hover:text-crimson-300 text-sm">Download Invoice</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php else: ?>
        <div class="text-center py-20 text-slate-500">
            <i class="fas fa-calendar-times text-4xl mb-4 opacity-30"></i>
            <p>No bookings found.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>