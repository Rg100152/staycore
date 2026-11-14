<?php
require_once __DIR__ . '/../config/session.php';
$pageTitle = 'Rooms Catalog';
include __DIR__ . '/../includes/header.php';

// Filters
$type = $_GET['type'] ?? '';
$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM rooms WHERE 1=1";
$params = [];
if ($type) { $sql .= " AND type = ?"; $params[] = $type; }
if ($status) { $sql .= " AND status = ?"; $params[] = $status; }
if ($search) { $sql .= " AND (room_number LIKE ? OR type LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
$sql .= " ORDER BY room_number ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rooms = $stmt->fetchAll();
?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Rooms &amp; Suites</h1>
            <p class="text-slate-400">Browse our available accommodations and book online.</p>
        </div>
        <div class="glass rounded-xl p-4 flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <form method="GET" class="flex flex-col sm:flex-row gap-2 w-full" id="filterForm">
                <input type="text" name="search" placeholder="Search room number or type..." value="<?= htmlspecialchars($search) ?>" class="w-full sm:w-48 bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
                <select name="type" class="bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
                    <option value="">All Types</option>
                    <option value="Standard" <?= $type==='Standard'?'selected':'' ?>>Standard</option>
                    <option value="Deluxe" <?= $type==='Deluxe'?'selected':'' ?>>Deluxe</option>
                    <option value="Suite" <?= $type==='Suite'?'selected':'' ?>>Suite</option>
                    <option value="Executive" <?= $type==='Executive'?'selected':'' ?>>Executive</option>
                </select>
                <select name="status" class="bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
                    <option value="">All Status</option>
                    <option value="Available" <?= $status==='Available'?'selected':'' ?>>Available</option>
                    <option value="Reserved" <?= $status==='Reserved'?'selected':'' ?>>Reserved</option>
                    <option value="Cleaning" <?= $status==='Cleaning'?'selected':'' ?>>Cleaning</option>
                    <option value="Maintenance" <?= $status==='Maintenance'?'selected':'' ?>>Maintenance</option>
                </select>
                <button type="submit" class="bg-crimson-600 hover:bg-crimson-700 text-white px-4 py-2 rounded-lg font-medium transition">Filter</button>
                <a href="rooms.php" class="text-slate-400 hover:text-white text-sm underline">Clear</a>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (count($rooms)): foreach ($rooms as $room): ?>
        <div class="glass-card glass rounded-xl overflow-hidden flex flex-col h-full">
            <div class="relative h-48 overflow-hidden">
                <img src="<?= htmlspecialchars($room['image_url']) ?>" class="w-full h-full object-cover">
                <div class="absolute top-3 right-3 px-2 py-1 rounded-md text-xs font-bold bg-slate-900/90 border border-white/10
                    <?= $room['status']==='Available'?'status-available':($room['status']==='Reserved'?'status-reserved':($room['status']==='Cleaning'?'status-cleaning':'status-maintenance')) ?>">
                    <?= htmlspecialchars($room['status']) ?>
                </div>
            </div>
            <div class="p-5 flex flex-col flex-1">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-bold text-white"><?= htmlspecialchars($room['room_number']) ?></h3>
                    <span class="text-crimson-400 font-bold">₹<?= number_format($room['price_per_night']) ?></span>
                </div>
                <p class="text-slate-400 text-sm mb-3 line-clamp-2"><?= htmlspecialchars($room['description']) ?></p>
                <div class="mt-auto pt-4 border-t border-white/10">
                    <a href="room_detail.php?id=<?= $room['id'] ?>" class="w-full bg-crimson-600 hover:bg-crimson-700 text-white text-center py-3 rounded-lg font-semibold transition block">View Details</a>
                </div>
            </div>
        </div>
        <?php endforeach; else: ?>
        <div class="col-span-full text-center py-20 text-slate-500">No rooms found matching your criteria.</div>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>