<?php
$pageTitle = 'Page Not Found';
include __DIR__ . '/../includes/header.php';
?>
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4">
    <div class="glass rounded-2xl p-12 text-center max-w-md">
        <h1 class="text-6xl font-bold text-crimson-500 mb-2">404</h1>
        <p class="text-slate-400 mb-6">Page not found.</p>
        <a href="/staycore/pages/index.php" class="bg-crimson-600 hover:bg-crimson-700 text-white px-6 py-3 rounded-lg inline-block transition">Go Home</a>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>