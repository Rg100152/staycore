<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'StayCore') ?> | StayCore Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/staycore/assets/css/style.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        crimson: { DEFAULT: '#dc2626', dark: '#b91c1c', light: '#fecaca' },
                        slate: { 900: '#0f172a', 950: '#020617' },
                        glass: 'rgba(255,255,255,0.05)'
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen selection:bg-crimson-500 selection:text-white">
    <!-- Flash Messages -->
    <?php if ($msg = flash('success')): ?>
        <div class="fixed top-5 left-1/2 -translate-x-1/2 z-50 px-6 py-3 rounded-lg bg-emerald-500/90 text-white shadow-lg text-sm font-medium animate-in fade-in slide-in-from-top-2 duration-300">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>
    <?php if ($msg = flash('error')): ?>
        <div class="fixed top-5 left-1/2 -translate-x-1/2 z-50 px-6 py-3 rounded-lg bg-rose-500/90 text-white shadow-lg text-sm font-medium animate-in fade-in slide-in-from-top-2 duration-300">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-40 border-b border-white/10 bg-slate-950/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-crimson-600 flex items-center justify-center text-white font-bold shadow-lg shadow-crimson/20">S</div>
                    <span class="font-bold text-xl tracking-tight text-white">Stay<span class="text-crimson-500">Core</span></span>
                </div>
                <div class="hidden md:flex items-center gap-6">
                    <a href="/staycore/pages/index.php" class="text-slate-300 hover:text-white transition">Home</a>
                    <a href="/staycore/pages/rooms.php" class="text-slate-300 hover:text-white transition">Rooms</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="/staycore/pages/my_bookings.php" class="text-slate-300 hover:text-white transition">My Bookings</a>
                        <?php if ($_SESSION['user_role'] === 'guest'): ?>
                            <a href="/staycore/pages/guest_dashboard.php" class="text-slate-300 hover:text-white transition">Guest Dashboard</a>
                        <?php endif; ?>
                        <?php if ($_SESSION['user_role'] === 'staff'): ?>
                            <a href="/staycore/pages/housekeeping.php" class="text-slate-300 hover:text-white transition">Housekeeping</a>
                        <?php endif; ?>
                        <?php if ($_SESSION['user_role'] === 'manager'): ?>
                            <a href="/staycore/pages/front_desk.php" class="text-slate-300 hover:text-white transition">Front Desk</a>
                        <?php endif; ?>
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                            <a href="/staycore/pages/admin.php" class="text-slate-300 hover:text-white transition">Admin Center</a>
                        <?php endif; ?>
                        <a href="/staycore/auth/logout.php" class="text-slate-400 hover:text-white transition text-sm">Logout</a>
                    <?php else: ?>
                        <a href="/staycore/auth/login.php" class="text-slate-300 hover:text-white transition">Login</a>
                        <a href="/staycore/auth/register.php" class="bg-crimson-600 hover:bg-crimson-700 text-white px-4 py-2 rounded-lg shadow-lg shadow-crimson/20 transition">Sign Up</a>
                    <?php endif; ?>
                </div>
                <div class="md:hidden flex items-center">
                    <button class="text-slate-300 hover:text-white"><i class="fas fa-bars text-xl"></i></button>
                </div>
            </div>
        </div>
    </nav>
</body>
</html>