<?php
require_once __DIR__ . '/../config/session.php';
requireRole(['manager','admin']);
$pageTitle = 'Staff Schedule';
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Staff Schedule &amp; Planning</h1>
        <p class="text-slate-400">Shift management and roster for all teams.</p>
    </div>
    <div class="glass rounded-2xl p-6">
        <div class="text-center py-20 text-slate-500">
            <i class="fas fa-calendar-alt text-5xl mb-4 opacity-30"></i>
            <h3 class="text-white font-semibold">HR Planning Module</h3>
            <p class="text-slate-400 text-sm">Shift rosters, time-off requests &amp; team scheduling.</p>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>