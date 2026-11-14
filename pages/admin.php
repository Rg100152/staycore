<?php
require_once __DIR__ . '/../config/session.php';
requireRole('admin');
$pageTitle = 'Admin Control Center';
include __DIR__ . '/../includes/header.php';

$stats = [
    'users' => (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'rooms' => (int)$pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn(),
    'bookings' => (int)$pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn(),
    'revenue' => (float)$pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM reservations WHERE payment_status='Paid'")->fetchColumn(),
];
?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Admin Control Center</h1>
        <p class="text-slate-400">System configuration, backups, and audit trails.</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <?php foreach ([
            ['label'=>'Total Users','value'=>$stats['users'],'icon'=>'fa-users','color'=>'bg-blue-500/20 text-blue-400'],
            ['label'=>'Total Rooms','value'=>$stats['rooms'],'icon'=>'fa-bed','color'=>'bg-emerald-500/20 text-emerald-400'],
            ['label'=>'Total Bookings','value'=>$stats['bookings'],'icon'=>'fa-calendar','color'=>'bg-purple-500/20 text-purple-400'],
            ['label'=>'Revenue','value'=>'₹'.number_format($stats['revenue']),'icon'=>'fa-inr','color'=>'bg-crimson-500/20 text-crimson-400'],
        ] as $s): ?>
        <div class="glass-card glass rounded-2xl p-6">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 text-sm"><?= $s['label'] ?></p>
                    <h3 class="text-2xl font-bold text-white mt-1"><?= $s['value'] ?></h3>
                </div>
                <div class="w-10 h-10 rounded-lg <?= $s['color'] ?> flex items-center justify-center text-lg"><i class="fas <?= $s['icon'] ?>"></i></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Quick Actions -->
    <div class="glass rounded-2xl p-6">
        <h3 class="text-white font-bold mb-6">Quick Actions</h3>
        <div class="grid md:grid-cols-3 gap-4">
            <a href="settings.php" class="p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:bg-slate-800 transition text-left">
                <div class="text-slate-400 text-xs uppercase tracking-wider mb-2">Settings & Tax</div>
                <div class="text-white font-semibold">Configure hotel</div>
            </a>
            <a href="#" class="p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:bg-slate-800 transition text-left">
                <div class="text-slate-400 text-xs uppercase tracking-wider mb-2">Backup DB</div>
                <div class="text-white font-semibold">Download backup</div>
            </a>
            <a href="#" class="p-4 rounded-xl bg-slate-800/50 border border-slate-700 hover:bg-slate-800 transition text-left">
                <div class="text-slate-400 text-xs uppercase tracking-wider mb-2">Audit Logs</div>
                <div class="text-white font-semibold">Security trails</div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="glass rounded-2xl p-6 mt-8">
        <h3 class="text-white font-bold mb-4">Recent Reservations</h3>
        <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead><tr class="border-b border-white/10 text-slate-400 text-xs mb-1">
                <th class="pb-3">Guest</th><th class="pb-3">Room</th><th class="pb-3">Date</th><th class="pb-3">Status</th>
            </tr></thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT r.*, u.name, ro.room_number FROM reservations r JOIN users u ON r.user_id = u.id JOIN rooms ro ON r.room_id = ro.id ORDER BY r.created_at DESC LIMIT 8");
                while ($row = $stmt->fetch()):
                ?>
                <tr class="border-b border-white/5">
                    <td class="py-3 text-slate-300"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="py-3 text-white font-medium"><?= htmlspecialchars($row['room_number']) ?></td>
                    <td class="py-3 text-slate-400 text-sm"><?= htmlspecialchars($row['check_in_date']) ?></td>
                    <td class="py-3"><span class="text-xs font-bold px-2 py-1 rounded bg-slate-800 text-slate-300 border border-slate-600"><?= ucfirst($row['status']) ?></span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>