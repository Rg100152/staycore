<?php
require_once __DIR__ . '/../config/session.php';
requireRole(['manager','admin']);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/OTA/Manager.php';
require_once __DIR__ . '/../src/OTA/BookingCom.php';
require_once __DIR__ . '/../src/OTA/Airbnb.php';

$pageTitle = 'OTA Channel Manager';
include __DIR__ . '/../includes/header.php';

$otaManager = new Manager();
$otaManager->addConnector('booking_com', new BookingCom([
    'api_key' => $_ENV['BOOKING_API_KEY'] ?? 'demo_key',
    'api_secret' => $_ENV['BOOKING_API_SECRET'] ?? 'demo_secret',
    'base_url' => 'https://distribution-xml.booking.com/json',
]));
$otaManager->addConnector('airbnb', new Airbnb([
    'api_key' => $_ENV['AIRBNB_API_KEY'] ?? 'demo_key',
    'api_secret' => $_ENV['AIRBNB_API_SECRET'] ?? 'demo_secret',
    'base_url' => 'https://api.airbnb.com/v2',
]));

$syncResults = [];
if (isset($_POST['sync_all'])) {
    $syncResults = $otaManager->syncAllRooms($pdo);
}

$stmt = $pdo->query("SELECT id, room_number, type, status, price_per_night FROM rooms ORDER BY room_number ASC");
$rooms = $stmt->fetchAll();
?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">OTA Channel Manager</h1>
            <p class="text-slate-400">Sync direct bookings with Booking.com, Airbnb, Expedia to avoid double bookings.</p>
        </div>
        <form method="POST" class="flex gap-3">
            <button name="sync_all" class="bg-crimson-600 hover:bg-crimson-700 text-white px-5 py-2 rounded-lg font-medium transition flex items-center gap-2">
                <i class="fas fa-sync-alt"></i> Sync All Channels
            </button>
            <button class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-2 rounded-lg font-medium transition">Export .ics</button>
        </form>
    </div>

    <?php if ($syncResults): ?>
    <div class="glass rounded-2xl p-6 mb-8">
        <h3 class="text-white font-bold mb-4">Sync Results</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($syncResults as $r): ?>
            <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700">
                <p class="text-xs text-slate-400 uppercase tracking-wider mb-1"><?= htmlspecialchars($r['channel']) ?></p>
                <p class="text-sm text-white font-medium"><?= htmlspecialchars($r['room']) ?></p>
                <div class="flex gap-2 mt-2">
                    <span class="text-xs px-2 py-1 rounded <?= $r['availability']==='synced'?'bg-emerald-500/20 text-emerald-400':'bg-rose-500/20 text-rose-400' ?>"><?= ucfirst($r['availability']) ?></span>
                    <span class="text-xs px-2 py-1 rounded <?= $r['price']==='synced'?'bg-emerald-500/20 text-emerald-400':'bg-rose-500/20 text-rose-400' ?>"><?= ucfirst($r['price']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="glass rounded-2xl p-6">
        <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/10 text-slate-400 text-sm">
                    <th class="pb-3 font-medium">Room</th>
                    <th class="pb-3 font-medium">Type</th>
                    <th class="pb-3 font-medium">Status</th>
                    <th class="pb-3 font-medium">Price (₹)</th>
                    <th class="pb-3 font-medium">Booking.com</th>
                    <th class="pb-3 font-medium">Airbnb</th>
                    <th class="pb-3 font-medium">Last Sync</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $r): ?>
                <tr class="border-b border-white/5 hover:bg-white/5 transition">
                    <td class="py-4 text-white font-medium"><?= htmlspecialchars($r['room_number']) ?></td>
                    <td class="py-4 text-slate-300"><?= htmlspecialchars($r['type']) ?></td>
                    <td class="py-4"><span class="px-2 py-1 rounded-full text-xs font-bold border
                        <?= $r['status']==='Available'?'bg-emerald-500/20 text-emerald-400 border-emerald-500/30':($r['status']==='Reserved'?'bg-blue-500/20 text-blue-400 border-blue-500/30':'bg-slate-500/20 text-slate-400 border-slate-500/30') ?>">
                        <?= ucfirst(htmlspecialchars($r['status'])) ?>
                    </span></td>
                    <td class="py-4 text-slate-300"><?= number_format($r['price_per_night']) ?></td>
                    <td class="py-4 text-slate-400 text-xs">Demo mode</td>
                    <td class="py-4 text-slate-400 text-xs">Demo mode</td>
                    <td class="py-4 text-slate-400 text-xs">--</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-8 glass rounded-2xl p-6">
        <h3 class="text-white font-bold mb-4">Channel Configuration</h3>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700">
                <h4 class="text-white font-semibold mb-3">Booking.com</h4>
                <p class="text-sm text-slate-400 mb-3">XML/REST API for availability & rates</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                        <span class="text-sm text-slate-300">API Key: <span class="font-mono text-xs text-crimson-400">**** demo_key</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                        <span class="text-sm text-slate-300">Webhook: <span class="text-crimson-400 text-xs">Not configured</span></span>
                    </div>
                </div>
            </div>
            <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700">
                <h4 class="text-white font-semibold mb-3">Airbnb</h4>
                <p class="text-sm text-slate-400 mb-3">Calendar & pricing API integration</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                        <span class="text-sm text-slate-300">API Key: <span class="font-mono text-xs text-crimson-400">**** demo_key</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                        <span class="text-sm text-slate-300">Webhook: <span class="text-crimson-400 text-xs">Not configured</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>