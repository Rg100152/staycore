<?php
require_once __DIR__ . '/../config/session.php';
requireRole(['staff','manager','admin']);
$pageTitle = 'Inventory';
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Inventory &amp; Supply Chain</h1>
        <p class="text-slate-400">Amenities, linens, cleaning supplies &amp; low-stock alerts.</p>
    </div>
    <div class="glass rounded-2xl p-6">
        <div class="text-center py-20 text-slate-500">
            <i class="fas fa-boxes text-5xl mb-4 opacity-30"></i>
            <h3 class="text-white font-semibold">Inventory Module</h3>
            <p class="text-slate-400 text-sm">Track stock levels with threshold alerts.</p>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>