<?php
require_once __DIR__ . '/../config/session.php';
requireRole(['manager','admin','staff']);
$pageTitle = 'Front Desk Kanban';
include __DIR__ . '/../includes/header.php';

$stmt = $pdo->query("SELECT * FROM rooms ORDER BY room_number ASC");
$rooms = $stmt->fetchAll();

$statusOrder = ['Available','Reserved','Checked-In','Cleaning','Maintenance'];
$grouped = [];
foreach ($statusOrder as $s) $grouped[$s] = [];
foreach ($rooms as $r) $grouped[$r['status']][] = $r;
?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">Front Desk Kanban</h1>
            <p class="text-slate-400">Drag rooms across columns to update status in real time.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="refreshRoomStatus()" class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm transition">
                <i class="fas fa-sync"></i> Refresh
            </button>
            <a href="housekeeping.php" class="bg-crimson-600 hover:bg-crimson-700 text-white px-4 py-2 rounded-lg text-sm transition">Housekeeping</a>
        </div>
    </div>

    <div class="flex gap-4 overflow-x-auto pb-4" id="kanbanBoard">
        <?php foreach ($statusOrder as $status): 
            $color = $status==='Available'?'emerald':($status==='Reserved'?'blue':($status==='Checked-In'?'amber':($status==='Cleaning'?'rose':'slate')));
        ?>
        <div class="kanban-col flex-shrink-0 glass rounded-2xl p-4 min-w-[280px]" data-status="<?= $status ?>">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full 
                        <?= $color==='emerald'?'bg-emerald-500':($color==='blue'?'bg-blue-500':($color==='amber'?'bg-amber-500':($color==='rose'?'bg-rose-500':'bg-slate-500'))) ?>"></span>
                        <?= ucfirst($status) ?>
                </h3>
                <span class="text-xs text-slate-500 bg-white/5 px-2 py-1 rounded"><?= count($grouped[$status]) ?></span>
            </div>
            <div class="space-y-3" id="col-<?= $status ?>">
                <?php foreach ($grouped[$status] as $room): ?>
                <div class="card-draggable glass rounded-lg p-4 border border-white/10 draggable="true" data-room-id="<?= $room['id'] ?>" data-status="<?= $room['status'] ?>">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-white font-bold text-lg"><?= htmlspecialchars($room['room_number']) ?></h4>
                        <span class="text-xs font-bold px-2 py-0.5 rounded 
                            <?= $color==='emerald'?'bg-emerald-500/20 text-emerald-400':($color==='blue'?'bg-blue-500/20 text-blue-400':($color==='amber'?'bg-amber-500/20 text-amber-400':($color==='rose'?'bg-rose-500/20 text-rose-400':'bg-slate-500/20 text-slate-400'))) ?>">
                            <?= ucfirst($room['status']) ?>
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mb-3 line-clamp-2"><?= htmlspecialchars($room['type']) ?> • ₹<?= number_format($room['price_per_night']) ?></p>
                    <div class="flex gap-2 text-xs">
                        <button onclick="updateStatus(<?= $room['id'] ?>, 'Available')" class="flex-1 bg-slate-800/50 hover:bg-crimson-600 text-white py-1.5 rounded transition text-center">Available</button>
                        <button onclick="updateStatus(<?= $room['id'] ?>, 'Cleaning')" class="flex-1 bg-slate-800/50 hover:bg-rose-600 text-white py-1.5 rounded transition text-center">Cleaning</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- updateStatus is handled globally in assets/js/main.js (WebSocket + API) -->
<?php include __DIR__ . '/../includes/footer.php'; ?>