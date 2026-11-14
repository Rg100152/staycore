<?php
require_once __DIR__ . '/../config/session.php';
requireLogin();
$roomId = (int)($_GET['room_id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$roomId]);
$room = $stmt->fetch();
if (!$room) { header("Location: rooms.php"); exit; }
$pageTitle = 'Book Room';
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="glass rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-white mb-6">Booking Confirmation</h2>
        <div class="flex justify-between items-center mb-6 p-4 rounded-xl bg-slate-900/50 border border-slate-700">
            <div>
                <h3 class="text-white font-semibold"><?= htmlspecialchars($room['room_number']) ?></h3>
                <p class="text-slate-400 text-sm"><?= htmlspecialchars($room['type']) ?></p>
            </div>
            <span class="text-crimson-400 font-bold text-lg">₹<?= number_format($room['price_per_night']) ?>/night</span>
        </div>
        <form action="checkout.php" method="POST" class="space-y-6">
            <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
            <input type="hidden" name="price" value="<?= $room['price_per_night'] ?>">
            <h3 class="text-lg font-semibold text-white mb-3">Guest Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Full Name</label>
                    <input type="text" name="guest_name" required value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Email</label>
                    <input type="email" name="guest_email" required value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Check-in Date</label>
                    <input type="date" name="check_in" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Check-out Date</label>
                    <input type="date" name="check_out" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Phone</label>
                <input type="tel" name="phone" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
            </div>
            <div class="pt-4 border-t border-white/10">
                <p class="text-sm text-slate-400 mb-3">Estimated Total: <span class="text-white font-bold" id="total">₹0</span></p>
                <button type="submit" class="w-full bg-crimson-600 hover:bg-crimson-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-crimson/30 transition">Proceed to Payment</button>
            </div>
        </form>
    </div>
</div>
<script>
const price = <?= $room['price_per_night'] ?>;
document.querySelectorAll('input[name="check_in"], input[name="check_out"]').forEach(i => i.addEventListener('change', () => {
    const ci = new Date(document.querySelector('input[name="check_in"]').value);
    const co = new Date(document.querySelector('input[name="check_out"]').value);
    if (ci && co && co > ci) {
        const nights = (co - ci) / (1000*60*60*24);
        document.getElementById('total').textContent = '₹' + (price * nights).toLocaleString();
    }
}));
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>