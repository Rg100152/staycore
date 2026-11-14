<?php
require_once __DIR__ . '/../config/session.php';
if (isLoggedIn()) {
    $role = $_SESSION['user_role'];
    header("Location: /staycore/pages/{$role}_dashboard.php");
    exit;
}
$pageTitle = 'Login';
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-md mx-auto py-12 px-4">
    <div class="glass rounded-2xl p-8 mx-auto">
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-lg bg-crimson-600 flex items-center justify-center mx-auto mb-4 text-white font-bold">S</div>
            <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
            <p class="text-slate-400">Sign in to your StayCore account</p>
        </div>

        <?php if ($err = flash('error')): ?>
            <div class="mb-6 p-4 rounded-lg bg-rose-500/20 border border-rose-500/30 text-rose-200 text-sm">
                <?= htmlspecialchars($err) ?>
            </div>
        <?php endif; ?>

        <form action="login_process.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Email</label>
                <input type="email" name="email" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-crimson-500 focus:ring-1 focus:ring-crimson-500 transition" placeholder="you@email.com">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-crimson-500 focus:ring-1 focus:ring-crimson-500 transition" placeholder="••••••••">
            </div>
            <button type="submit" class="w-full bg-crimson-600 hover:bg-crimson-700 text-white font-semibold py-3 rounded-lg shadow-lg shadow-crimson/20 transition">Sign In</button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-400">
            Don't have an account? <a href="register.php" class="text-crimson-400 hover:text-crimson-300">Create one</a>
        </p>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>