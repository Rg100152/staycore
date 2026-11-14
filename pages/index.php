<?php
require_once __DIR__ . '/../config/session.php';
$pageTitle = 'Home';
include __DIR__ . '/../includes/header.php';
?>
<main class="relative min-h-[calc(100vh-64px)]">
    <!-- Hero -->
    <section class="relative pt-20 pb-12 px-4 overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920&q=80')] bg-cover bg-center opacity-40"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 to-slate-950"></div>
        <div class="max-w-7xl mx-auto relative z-10 px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-crimson-500/10 text-crimson-400 text-xs font-medium border border-crimson-500/20 mb-6 animate-in fade-in slide-in-from-top-2">New in Odoo-Style Suite</span>
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 animate-in fade-in slide-in-from-top-2 duration-500">StayCore.<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-crimson-400 to-rose-300">Luxury Redefined.</span></h1>
            <p class="text-slate-300 text-lg md:text-xl mb-10 max-w-2xl mx-auto animate-in fade-in slide-in-from-top-2 duration-700">
                Book your perfect stay with real-time room availability, glassmorphism UI, and seamless hotel operations.
            </p>
            <!-- Search Widget -->
            <div class="glass rounded-2xl p-4 md:p-6 max-w-4xl mx-auto shadow-2xl animate-in fade-in slide-in-from-bottom-2 duration-700">
                <form action="rooms.php" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Check-in</label>
                        <input type="date" name="check_in" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:border-crimson-500 focus:ring-1 focus:ring-crimson-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Check-out</label>
                        <input type="date" name="check_out" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:border-crimson-500 focus:ring-1 focus:ring-crimson-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-400 mb-1">Guests</label>
                        <select name="guests" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:border-crimson-500 focus:ring-1 focus:ring-crimson-500">
                            <option>1</option><option>2</option><option>3</option><option>4+</option>
                        </select>
                    </div>
                    <div class="md:col-span-1 flex items-end">
                        <button class="w-full bg-crimson-600 hover:bg-crimson-700 text-white font-bold py-3 rounded-lg shadow-lg shadow-crimson/30 transition">Search Rooms</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Featured Rooms -->
    <section class="py-16 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-white">Featured Rooms</h2>
                <a href="rooms.php" class="text-crimson-400 hover:text-crimson-300 text-sm font-medium">View all rooms →</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $stmt = $pdo->query("SELECT * FROM rooms WHERE status = 'Available' ORDER BY price_per_night DESC LIMIT 6");
                while ($room = $stmt->fetch()):
                ?>
                <a href="room_detail.php?id=<?= $room['id'] ?>" class="glass-card glass rounded-xl overflow-hidden group block">
                    <div class="relative h-48 overflow-hidden">
                        <img src="<?= htmlspecialchars($room['image_url']) ?>" alt="<?= htmlspecialchars($room['room_number']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute top-3 right-3 flex items-center gap-1 px-2 py-1 rounded-md bg-slate-900/90 text-xs font-semibold">
                            <span class="w-2 h-2 rounded-full <?= $room['status']==='Available'?'bg-emerald-500':'bg-rose-500' ?>"></span>
                            <span><?= htmlspecialchars($room['status']) ?></span>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-white group-hover:text-crimson-400 transition"><?= htmlspecialchars($room['room_number']) ?> — <?= htmlspecialchars($room['type']) ?></h3>
                            <span class="text-crimson-400 font-bold">₹<?= number_format($room['price_per_night']) ?>/night</span>
                        </div>
                        <p class="text-slate-400 text-sm mb-4 line-clamp-2"><?= htmlspecialchars($room['description']) ?></p>
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <span><?= htmlspecialchars($room['max_guests']) ?> Guests</span>
                            <span>•</span>
                            <span><?= htmlspecialchars($room['floor_size']) ?></span>
                            <span>•</span>
                            <span><?= htmlspecialchars($room['bed_type']) ?></span>
                        </div>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>