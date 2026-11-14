<?php
require_once __DIR__ . '/../config/session.php';
requireRole(['manager','admin']);
$pageTitle = 'Invoicing Engine';
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Invoicing &amp; Billing Engine</h1>
        <p class="text-slate-400">Itemized bills, tourist tax, refunds &amp; payment tracking.</p>
    </div>
    <div class="glass rounded-2xl p-6">
        <div class="text-center py-20 text-slate-500">
            <i class="fas fa-file-invoice-dollar text-5xl mb-4 opacity-30"></i>
            <h3 class="text-white font-semibold">Accounting Module</h3>
            <p class="text-slate-400 text-sm">Automatic invoice generation &amp; refund management.</p>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>