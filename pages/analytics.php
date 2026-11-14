<?php
require_once __DIR__ . '/../config/session.php';
requireRole(['manager','admin']);
$pageTitle = 'Analytics';
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Analytics &amp; Reporting</h1>
        <p class="text-slate-400">ADR, RevPAR, occupancy &amp; pickup trends.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
        <?php foreach ([
            ['label'=>'ADR','value'=>'₹8,450','icon'=>'fa-tag'],
            ['label'=>'RevPAR','value'=>'₹6,120','icon'=>'fa-wallet'],
            ['label'=>'Occupancy','value'=>'78%','icon'=>'fa-bed'],
        ] as $s): ?>
        <div class="glass-card glass rounded-2xl p-6">
            <p class="text-slate-400 text-sm mb-2"><?= $s['label'] ?></p>
            <h3 class="text-2xl font-bold text-white"><?= $s['value'] ?></h3>
            <i class="fas <?= $s['icon'] ?> text-crimson-400 ml-auto mt-2 block"></i>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>