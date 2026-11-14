<?php
require_once __DIR__ . '/../config/session.php';
requireLogin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') header("Location: rooms.php");

$roomId = (int)($_POST['room_id']);
$price = (float)($_POST['price']);
$guestName = trim($_POST['guest_name']);
$guestEmail = trim($_POST['guest_email']);
$checkIn = $_POST['check_in'];
$checkOut = $_POST['check_out'];
$phone = trim($_POST['phone']);

if (!$roomId || !$price || !$guestName || !$guestEmail || !$checkIn || !$checkOut) {
    flash('error', 'Please fill all booking details');
    header("Location: booking.php?room_id=$roomId");
    exit;
}

$ci = new DateTime($checkIn);
$co = new DateTime($checkOut);
$nights = $ci->diff($co)->days;
if ($nights < 1) { flash('error', 'Invalid date range'); header("Location: booking.php?room_id=$roomId"); exit; }
$total = $price * $nights;

// Check availability overlap
$stmt = $pdo->prepare("SELECT id FROM reservations WHERE room_id = ? AND status IN ('Pending','Confirmed','Checked-In') AND check_in_date < ? AND check_out_date > ?");
$stmt->execute([$roomId, $checkOut, $checkIn]);
if ($stmt->fetch()) {
    flash('error', 'Room is already booked for those dates');
    header("Location: booking.php?room_id=$roomId");
    exit;
}

// Store pending booking in session for payment confirmation
$_SESSION['pending_booking'] = [
    'user_id' => $_SESSION['user_id'],
    'room_id' => $roomId,
    'check_in_date' => $checkIn,
    'check_out_date' => $checkOut,
    'total_amount' => $total,
    'guest_name' => $guestName,
    'guest_email' => $guestEmail,
    'phone' => $phone,
    'nights' => $nights,
];

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Payment.php';

$payment = new Payment($pdo, 'pk_test_placeholder', 'sk_test_placeholder');
$intent = $payment->createIntent($total, 'inr', [
    'room_id' => $roomId,
    'user_id' => $_SESSION['user_id'],
    'nights' => $nights,
]);

$pageTitle = 'Payment';
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="glass rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-white mb-6">Secure Payment</h2>
        <div class="flex justify-between items-center mb-6 p-4 rounded-xl bg-slate-900/50 border border-slate-700">
            <div>
                <h3 class="text-white font-semibold">Room #<?= $roomId ?></h3>
                <p class="text-slate-400 text-sm"><?= $nights ?> night(s) • <?= date('M d', strtotime($checkIn)) . ' to ' . date('M d', strtotime($checkOut)) ?></p>
            </div>
            <span class="text-crimson-400 font-bold text-xl">₹<?= number_format($total) ?></span>
        </div>

        <?php if ($intent['success']): ?>
        <div id="payment-message" class="mb-6"></div>
        <form id="payment-form" class="space-y-4">
            <div class="p-4 rounded-xl bg-slate-900/50 border border-slate-700" id="card-element">
                <div class="text-slate-400 text-sm mb-3">Loading Stripe Elements...</div>
            </div>
            <button id="submit" class="w-full bg-crimson-600 hover:bg-crimson-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-crimson/30 transition disabled:opacity-50 disabled:cursor-not-allowed">
                Pay ₹<?= number_format($total) ?>
            </button>
            <p class="text-center text-xs text-slate-500">Test card: 4242 4242 4242 4242 • any future date • any CVC</p>
        </form>

        <?php else: ?>
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm text-center">
            Payment gateway not configured. Add Stripe keys in <code>pages/checkout.php</code> to enable live payments.
        </div>
        <a href="my_bookings.php" class="block text-center mt-6 text-crimson-400 hover:text-crimson-300">Back to Bookings</a>
        <?php endif; ?>
    </div>
</div>

<?php if ($intent['success']): ?>
<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('<?= $intent['client_secret'] ? 'pk_test_placeholder' : '' ?>');
const elements = stripe.elements();
const card = elements.create('card', {
    style: { base: { color: '#fff', fontSize: '16px', '::placeholder': { color: '#94a3b8' } }, invalid: { color: '#f87171' } }
});
card.mount('#card-element');

const form = document.getElementById('payment-form');
const submit = document.getElementById('submit');
const message = document.getElementById('payment-message');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    submit.disabled = true;
    submit.textContent = 'Processing...';

    const {error, paymentIntent} = await stripe.confirmCardPayment('<?= $intent['client_secret'] ?>', {
        payment_method: { card: card, billing_details: { name: '<?= htmlspecialchars($guestName, ENT_QUOTES) ?>', email: '<?= htmlspecialchars($guestEmail, ENT_QUOTES) ?>' } }
    });

    if (error) {
        message.innerHTML = '<div class="p-3 rounded bg-rose-500/20 text-rose-400 text-sm border border-rose-500/30">' + error.message + '</div>';
        submit.disabled = false;
        submit.textContent = 'Pay ₹<?= number_format($total) ?>';
    } else if (paymentIntent.status === 'succeeded') {
        // Confirm booking server-side
        const res = await fetch('/staycore/api/payment_confirm.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ intent_id: '<?= $intent['intent_id'] ?>' })
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = '/staycore/pages/my_bookings.php';
        } else {
            message.innerHTML = '<div class="p-3 rounded bg-rose-500/20 text-rose-400 text-sm border border-rose-500/30">Booking failed: ' + (data.message||'unknown') + '</div>';
            submit.disabled = false;
        }
    }
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>