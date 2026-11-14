<?php
require_once __DIR__ . '/../config/session.php';
requireRole(['manager','admin']);
$pageTitle = 'Website CMS';
include __DIR__ . '/../includes/header.php';
?>
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Website &amp; Blog Manager</h1>
        <p class="text-slate-400">CMS for banners, blogs, FAQs &amp; attraction guides.</p>
    </div>
    <div class="glass rounded-2xl p-6">
        <div class="text-center py-20 text-slate-500">
            <i class="fas fa-pen text-5xl mb-4 opacity-30"></i>
            <h3 class="text-white font-semibold">CMS Module</h3>
            <p class="text-slate-400 text-sm">Edit landing content and publish travel blogs.</p>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>