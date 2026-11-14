<?php
require_once __DIR__ . '/../config/session.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$room = $stmt->fetch();
if (!$room) { header("Location: rooms.php"); exit; }
$pageTitle = 'Room ' . $room['room_number'];
include __DIR__ . '/../includes/header.php';
$amenities = json_decode($room['amenities'] ?? '[]', true);
?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <a href="rooms.php" class="text-slate-400 hover:text-white mb-4 inline-block flex items-center gap-1 text-sm">← Back to Rooms</a>

    <div class="grid lg:grid-cols-2 gap-8">
        <!-- Gallery -->
        <div class="glass rounded-2xl p-4">
            <div class="relative h-80 rounded-xl overflow-hidden mb-4">
                <img src="<?= htmlspecialchars($room['image_url']) ?>" class="w-full h-full object-cover">
                <span class="absolute top-4 right-4 px-3 py-1 rounded-full bg-slate-900/90 text-xs font-bold border border-white/10
                    <?= $room['status']==='Available'?'status-available':($room['status']==='Reserved'?'status-reserved':($room['status']==='Cleaning'?'status-cleaning':'status-maintenance')) ?>">
                    <?= htmlspecialchars($room['status']) ?>
                </span>
            </div>
            <div class="flex gap-2 overflow-x-auto pb-2">
                <?php for($i=1;$i<=3;$i++): ?>
                <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=300&q=80" class="w-20 h-16 rounded-lg object-cover opacity-50 hover:opacity-100 transition cursor-pointer border border-slate-700">
                <?php endfor; ?>
            </div>
        </div>

        <!-- Details -->
        <div class="glass rounded-2xl p-8">
            <h1 class="text-3xl font-bold text-white mb-1"><?= htmlspecialchars($room['room_number']) ?></h1>
            <div class="flex items-center gap-3 mb-6">
                <span class="px-3 py-1 rounded-full bg-slate-800 text-xs font-medium text-slate-300 border border-slate-600"><?= htmlspecialchars($room['type']) ?></span>
                <span class="text-slate-400 text-sm">•</span>
                <span class="text-crimson-400 text-xl font-bold">₹<?= number_format($room['price_per_night']) ?> / night</span>
                <span class="text-slate-400 text-sm ml-auto">Max <?= $room['max_guests'] ?> guests</span>
            </div>

            <h3 class="text-lg font-semibold text-white mb-3">Description</h3>
            <p class="text-slate-400 mb-6 line-clamp-3"><?= htmlspecialchars($room['description']) ?></p>

            <h3 class="text-lg font-semibold text-white mb-3">Amenities</h3>
            <div class="flex flex-wrap gap-2 mb-6">
                <?php foreach ($amenities as $amenity): ?>
                <span class="px-3 py-1 rounded-full bg-slate-800/80 text-xs text-slate-300 border border-slate-600/50"><?= htmlspecialchars($amenity) ?></span>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-8 text-sm text-slate-400">
                <div><span class="block text-white font-medium">Floor Size</span><?= htmlspecialchars($room['floor_size']) ?></div>
                <div><span class="block text-white font-medium">Bed Type</span><?= htmlspecialchars($room['bed_type']) ?></div>
            </div>

            <?php if ($room['status'] === 'Available'): ?>
            <form action="booking.php" method="GET" class="space-y-4">
                <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Check-in</label>
                        <input type="date" name="check_in" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Check-out</label>
                        <input type="date" name="check_out" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
                    </div>
                </div>
                <button type="submit" class="w-full bg-crimson-600 hover:bg-crimson-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-crimson/30 transition">Book Now</button>
            </form>
            <?php else: ?>
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm text-center">
                Room is currently <strong><?= htmlspecialchars($room['status']) ?></strong>. Please choose another room.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>