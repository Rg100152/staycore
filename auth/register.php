<?php
require_once __DIR__ . '/../config/session.php';
if (isLoggedIn()) header("Location: /staycore/pages/index.php");
$pageTitle = 'Register';
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-md mx-auto py-12 px-4">
    <div class="glass rounded-2xl p-8 mx-auto">
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-lg bg-crimson-600 flex items-center justify-center mx-auto mb-4 text-white font-bold">S</div>
            <h2 class="text-2xl font-bold text-white">Create Account</h2>
            <p class="text-slate-400">Join StayCore as a guest</p>
        </div>
        <form action="register_process.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-white focus:border-crimson-500 focus:ring-1 focus:ring-crimson-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Email</label>
                <input type="email" name="email" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-white focus:border-crimson-500 focus:ring-1 focus:ring-crimson-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Phone</label>
                <input type="tel" name="phone" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-white focus:border-crimson-500 focus:ring-1 focus:ring-crimson-500 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-white focus:border-crimson-500 focus:ring-1 focus:ring-crimson-500 transition">
            </div>
            <button type="submit" class="w-full bg-crimson-600 hover:bg-crimson-700 text-white font-semibold py-3 rounded-lg shadow-lg shadow-crimson/20 transition">Create Account</button>
        </form>
        <p class="mt-6 text-center text-sm text-slate-400">
            Already have an account? <a href="login.php" class="text-crimson-400 hover:text-crimson-300">Sign in</a>
        </p>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>