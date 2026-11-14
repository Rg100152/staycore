<?php
require_once __DIR__ . '/../config/session.php';
requireRole('admin');
$pageTitle = 'Settings';
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Settings &amp; Tax Configuration</h1>
        <p class="text-slate-400">Multi-currency, tax rules, user permissions &amp; notifications.</p>
    </div>
    <div class="glass rounded-2xl p-8">
        <form class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Hotel Name</label>
                    <input type="text" value="StayCore Hotel" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Currency</label>
                    <select class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
                        <option>INR</option><option>USD</option><option>EUR</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Tax Rate (%)</label>
                <input type="number" value="12" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white focus:border-crimson-500">
            </div>
            <button class="bg-crimson-600 hover:bg-crimson-700 text-white px-6 py-2 rounded-lg font-medium transition">Save Configuration</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>