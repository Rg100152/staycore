// StayCore Frontend JS
const API_BASE = '/staycore/api';
const WS_URL = 'ws://localhost:8080';
let ws = null;

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-5 right-5 z-50 px-6 py-3 rounded-lg text-white text-sm font-medium shadow-lg animate-in fade-in slide-in-from-top-2 duration-300 ${
        type === 'success' ? 'bg-emerald-500/90' : 'bg-rose-500/90'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

async function apiRequest(endpoint, method = 'GET', body = null) {
    const opts = { method, headers: { 'Content-Type': 'application/json' } };
    if (body) opts.body = JSON.stringify(body);
    const res = await fetch(`${API_BASE}/${endpoint}`, opts);
    return res.json();
}

async function refreshRoomStatus() {
    try {
        const data = await apiRequest('rooms.php?action=statuses');
        if (data.success) {
            data.rooms.forEach(r => {
                const el = document.querySelector(`[data-room-id="${r.id}"]`);
                if (el) el.dataset.status = r.status;
            });
        }
    } catch (e) { console.error(e); }
}

function initWebSocket() {
    if (typeof WebSocket === 'undefined') return;
    try {
        ws = new WebSocket(WS_URL);
        ws.onopen = () => console.log('WebSocket connected');
        ws.onmessage = (e) => {
            const data = JSON.parse(e.data);
            if (data.type === 'room_status_update' && data.room_id && data.status) {
                const el = document.querySelector(`[data-room-id="${data.room_id}"]`);
                if (el) {
                    el.dataset.status = data.status;
                    showToast(`Room status updated: ${data.status}`);
                }
            }
        };
        ws.onclose = () => { ws = null; setTimeout(initWebSocket, 3000); };
        ws.onerror = () => { ws.close(); };
    } catch (e) { console.error('WebSocket error', e); }
}

function updateStatus(roomId, newStatus) {
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({ room_id: roomId, status: newStatus }));
    }
    apiRequest('broadcast.php', 'POST', { room_id: roomId, status: newStatus }).then(data => {
        if (data.success) showToast('Room status updated to ' + newStatus);
        else showToast(data.message || 'Update failed', 'error');
    }).catch(() => showToast('Network error', 'error'));
}

function updateTask(taskId, newStatus) {
    apiRequest('tasks.php', 'POST', { task_id: taskId, status: newStatus }).then(data => {
        if (data.success) showToast('Task updated to ' + newStatus);
        else showToast(data.message || 'Failed', 'error');
    }).catch(() => showToast('Network error', 'error'));
}

// Kanban drag & drop
document.addEventListener('DOMContentLoaded', () => {
    initWebSocket();

    const draggables = document.querySelectorAll('.card-draggable');
    const containers = document.querySelectorAll('.kanban-col');

    draggables.forEach(card => {
        card.addEventListener('dragstart', () => card.classList.add('dragging'));
        card.addEventListener('dragend', () => card.classList.remove('dragging'));
    });

    containers.forEach(col => {
        col.addEventListener('dragover', e => {
            e.preventDefault();
            const dragging = document.querySelector('.dragging');
            if (!dragging) return;
            const after = getDragAfterElement(col, e.clientY);
            if (after == null) col.appendChild(dragging);
            else col.insertBefore(dragging, after);
        });
    });

    if (document.querySelector('.kanban-col')) {
        setInterval(refreshRoomStatus, 30000);
    }
});

function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.card-draggable:not(.dragging)')];
    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = y - box.top - box.height / 2;
        return offset < 0 && offset > closest.offset ? { offset, element: child } : closest;
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}