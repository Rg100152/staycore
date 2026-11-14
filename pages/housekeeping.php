<?php
require_once __DIR__ . '/../config/session.php';
requireRole(['staff','manager','admin']);
$pageTitle = 'Housekeeping Hub';
include __DIR__ . '/../includes/header.php';

$stmt = $pdo->query("SELECT ht.*, r.room_number, u.name as assignee FROM housekeeping_tasks ht JOIN rooms r ON ht.room_id = r.id LEFT JOIN users u ON ht.assigned_to = u.id ORDER BY ht.created_at DESC");
$tasks = $stmt->fetchAll();
?>
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">Housekeeping Task Hub</h1>
            <p class="text-slate-400">Track cleaning tasks and room readiness.</p>
        </div>
    </div>

    <div class="glass rounded-2xl p-6">
        <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/10 text-slate-400 text-sm">
                    <th class="pb-3 font-medium">Task #</th>
                    <th class="pb-3 font-medium">Room</th>
                    <th class="pb-3 font-medium">Assigned To</th>
                    <th class="pb-3 font-medium">Status</th>
                    <th class="pb-3 font-medium">Priority</th>
                    <th class="pb-3 font-medium">Created</th>
                    <th class="pb-3 font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $t): ?>
                <tr class="border-b border-white/5 hover:bg-white/5 transition">
                    <td class="py-4 text-slate-300">#<?= $t['id'] ?></td>
                    <td class="py-4 text-white font-medium"><?= htmlspecialchars($t['room_number']) ?></td>
                    <td class="py-4 text-slate-300"><?= htmlspecialchars($t['assignee'] ?? 'Unassigned') ?></td>
                    <td class="py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold border
                            <?= $t['status']==='Pending'?'bg-slate-500/20 text-slate-400 border-slate-500/30':($t['status']==='In Progress'?'bg-blue-500/20 text-blue-400 border-blue-500/30':($t['status']==='Ready'?'bg-emerald-500/20 text-emerald-400 border-emerald-500/30':'bg-rose-500/20 text-rose-400 border-rose-500/30')) ?>">
                            <?= ucfirst(htmlspecialchars($t['status'])) ?>
                        </span>
                    </td>
                    <td class="py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold border
                            <?= $t['priority']==='High'?'bg-rose-500/20 text-rose-400 border-rose-500/30':($t['priority']==='Medium'?'bg-amber-500/20 text-amber-400 border-amber-500/30':'bg-emerald-500/20 text-emerald-400 border-emerald-500/30') ?>">
                            <?= ucfirst(htmlspecialchars($t['priority'])) ?>
                        </span>
                    </td>
                    <td class="py-4 text-slate-300"><?= date('M d, Y', strtotime($t['created_at'])) ?></td>
                    <td class="py-4">
                        <button class="text-crimson-400 hover:text-crimson-300 text-sm" onclick="updateTask(<?= $t['id'] ?>, '<?= $t['status']==='Pending'?'In Progress':($t['status']==='In Progress'?'Ready':'Pending') ?>')">
                            <?= $t['status']==='Pending'?'Start':($t['status']==='In Progress'?'Mark Ready':'Reopen') ?>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<script>
async function updateTask(taskId, newStatus) {
    try {
        const res = await fetch('/staycore/api/tasks.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ task_id: taskId, status: newStatus })
        });
        const data = await res.json();
        if (data.success) {
            showToast('Task updated to ' + newStatus);
            setTimeout(() => location.reload(), 600);
        } else {
            showToast(data.message || 'Failed', 'error');
        }
    } catch (e) {
        showToast('Network error', 'error');
    }
}
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>