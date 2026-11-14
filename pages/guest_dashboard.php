<?php
require_once __DIR__ . '/../config/session.php';
requireRole('guest');
$pageTitle = 'Guest Dashboard';
include __DIR__ . '/../includes/header.php';

// Active booking
$stmt = $pdo->prepare("SELECT r.*, ro.room_number, ro.type FROM reservations r JOIN rooms ro ON r.room_id = ro.id WHERE r.user_id = ? AND r.status = 'Checked-In' ORDER BY r.check_in_date DESC LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$active = $stmt->fetch();
?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Guest Dashboard</h1>
        <p class="text-slate-400">Manage your current stay and request services.</p>
    </div>

    <!-- Active Stay Card -->
    <div class="glass rounded-2xl p-6 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-crimson-600/10 rounded-bl-full"></div>
        <?php if ($active): ?>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h2 class="text-xl font-bold text-white mb-1">Active Stay</h2>
                <p class="text-slate-400 text-sm">Room <?= htmlspecialchars($active['room_number']) ?> • <?= htmlspecialchars($active['type']) ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500 uppercase tracking-wider">Check-out</p>
                <p class="text-2xl font-bold text-white"><?= htmlspecialchars($active['check_out_date']) ?></p>
            </div>
        </div>
        <?php else: ?>
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm text-center">
            No active stay. <a href="rooms.php" class="text-rose-300 hover:underline">Book a room</a> to get started.
        </div>
        <?php endif; ?>
    </div>

    <!-- Service Requests Grid -->
    <div class="grid md:grid-cols-3 gap-6">
        <div class="glass-card glass rounded-xl p-6 text-center group cursor-pointer hover:border-crimson-500/50 transition">
            <div class="w-12 h-12 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-500 group-hover:text-white transition">
                <i class="fas fa-wifi text-xl"></i>
            </div>
            <h3 class="text-white font-semibold mb-2">Wi-Fi Password</h3>
            <p class="text-slate-400 text-sm mb-4">StayCore-Guest-2026</p>
            <button class="text-xs text-crimson-400 hover:text-crimson-300">Copy Password</button>
        </div>
        <div class="glass-card glass rounded-xl p-6 text-center group cursor-pointer hover:border-crimson-500/50 transition">
            <div class="w-12 h-12 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-500 group-hover:text-white transition">
                <i class="fas fa-tshirt text-xl"></i>
            </div>
            <h3 class="text-white font-semibold mb-2">Laundry Service</h3>
            <p class="text-slate-400 text-sm mb-4">Request pickup &amp; delivery</p>
            <button class="text-xs text-crimson-400 hover:text-crimson-300">Request Now</button>
        </div>
        <div class="glass-card glass rounded-xl p-6 text-center group cursor-pointer hover:border-crimson-500/50 transition">
            <div class="w-12 h-12 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center mx-auto mb-4 group-hover:bg-amber-500 group-hover:text-white transition">
                <i class="fas fa-clock text-xl"></i>
            </div>
            <h3 class="text-white font-semibold mb-2">Wake-up Call</h3>
            <p class="text-slate-400 text-sm mb-4">Schedule your morning call</p>
            <button class="text-xs text-crimson-400 hover:text-crimson-300">Set Time</button>
        </div>
    </div>

    <!-- Live Chat Widget -->
    <div class="glass rounded-2xl p-6 mt-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-white font-bold flex items-center gap-2"><i class="fas fa-comments text-crimson-400"></i> Live Concierge</h3>
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        </div>
        <div class="h-48 overflow-y-auto bg-slate-900/50 rounded-lg p-4 mb-4 space-y-3 border border-white/5">
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-crimson-600 flex items-center justify-center text-xs text-white">S</div>
                <div class="bg-slate-800 p-3 rounded-lg text-sm text-slate-300 max-w-[80%]">Hello! How can I assist you with your stay today?</div>
            </div>
        </div>
        <div class="flex gap-2">
            <input type="text" placeholder="Type a message..." class="flex-1 bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-white focus:border-crimson-500">
            <button class="bg-crimson-600 hover:bg-crimson-700 text-white px-4 py-2 rounded-lg">Send</button>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>