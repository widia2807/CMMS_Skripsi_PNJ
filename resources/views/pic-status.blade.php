<!DOCTYPE html>
<html>
<head>
    <title>Status Pengajuan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f1f5f9; }

        .card {
            background: white;
            border-radius: 20px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        tr { transition: background 0.15s; }
        tr:hover td { background: #f8fafc; }

        .detail-panel {
            animation: slideDown 0.2s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
            text-transform: capitalize;
        }
        .status-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .btn-detail {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            transition: all 0.15s;
            cursor: pointer;
        }
        .btn-detail:hover {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #2563eb;
        }
        .btn-detail.active {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #2563eb;
        }

        .detail-field {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .detail-field-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
        }
        .detail-field-value {
            font-size: 13px;
            color: #334155;
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>
</head>

<body>

@include('components.sidebar')

<div class="flex min-h-screen">
<div class="flex-1 md:ml-64">
<!-- TOPBAR -->
            <div class="bg-white border-b border-slate-100 px-4 md:px-8 py-4 flex justify-between items-center sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <!-- Hamburger mobile -->
                    <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-400">
                        <i data-feather="menu" class="w-5 h-5"></i>
                    </button>
                    <div>
                        <h1 class="font-bold text-slate-800 text-lg">Status Pengajuan</h1>
                        <p class="text-xs text-slate-400 mt-0.5">Pantau perkembangan pengajuan perbaikan</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div id="userBadge" class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                        <div class="w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center">
                            <span id="userInitial" class="text-white font-bold text-xs"></span>
                        </div>
                        <span id="userInfo" class="text-sm font-medium text-slate-600"></span>
                    </div>
                </div>
            </div>

    <div class="p-6 md:p-8">

        <!-- STAT STRIP -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6" id="statStrip"></div>

        <!-- TABLE CARD -->
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                <h2 class="font-bold text-slate-800">Daftar Pengajuan</h2>
                <button onclick="loadRequests()" class="text-xs text-indigo-500 hover:text-indigo-700 font-semibold flex items-center gap-1">
                    <i data-feather="refresh-cw" class="w-3.5 h-3.5"></i> Refresh
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-400 text-xs uppercase tracking-wide">Judul</th>
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-400 text-xs uppercase tracking-wide">Kategori</th>
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-400 text-xs uppercase tracking-wide">Status</th>
                            <th class="px-5 py-3.5 text-left font-semibold text-slate-400 text-xs uppercase tracking-wide">Tanggal</th>
                            <th class="px-5 py-3.5 text-center font-semibold text-slate-400 text-xs uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="list"></tbody>
                </table>
            </div>

            <!-- Empty / Loading state -->
            <div id="emptyState" class="hidden py-16 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i data-feather="file-text" class="w-8 h-8 text-slate-300"></i>
                </div>
                <p class="text-slate-500 font-medium">Belum ada pengajuan</p>
                <p class="text-slate-400 text-xs mt-1">Klik "Ajukan Perbaikan" untuk membuat pengajuan baru</p>
                <button onclick="goTo('/request')" class="mt-4 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                    <i data-feather="plus" class="w-4 h-4"></i> Ajukan Perbaikan
                </button>
            </div>

            <div id="loadingState" class="py-12 text-center">
                <div class="w-8 h-8 border-2 border-indigo-200 border-t-indigo-500 rounded-full animate-spin mx-auto mb-3"></div>
                <p class="text-slate-400 text-sm">Memuat data...</p>
            </div>

        </div>
    </div>
</div>
</div>

<script>
const token = localStorage.getItem('token');
const user  = JSON.parse(localStorage.getItem('user'));

if (!user || !token) { localStorage.clear(); window.location.href = '/login'; }

document.getElementById('userInfo').innerText    = user.name + ' · ' + user.role;
document.getElementById('userInitial').innerText = user.name?.split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase();

// ─── STATUS CONFIG ────────────────────────────────────────
const STATUS = {
    pending:        { label: 'Menunggu',      cls: 'bg-amber-50 text-amber-700',   dot: '#f59e0b' },
    approved:       { label: 'Disetujui',     cls: 'bg-green-50 text-green-700',   dot: '#22c55e' },
    rejected:       { label: 'Ditolak',       cls: 'bg-red-50 text-red-600',       dot: '#ef4444' },
    scheduled:      { label: 'Dijadwalkan',   cls: 'bg-blue-50 text-blue-700',     dot: '#3b82f6' },
    in_progress:    { label: 'Dikerjakan',    cls: 'bg-indigo-50 text-indigo-700', dot: '#6366f1' },
    material_ready: { label: 'Material Siap', cls: 'bg-purple-50 text-purple-700', dot: '#a855f7' },
    done:           { label: 'Selesai',       cls: 'bg-teal-50 text-teal-700',     dot: '#14b8a6' },
};

function statusBadge(status) {
    const s = STATUS[status] || { label: status, cls: 'bg-slate-100 text-slate-500', dot: '#94a3b8' };
    return `<span class="status-badge ${s.cls}">
        <span class="status-dot" style="background:${s.dot}"></span>
        ${s.label}
    </span>`;
}

function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

// ─── STAT STRIP ──────────────────────────────────────────
function renderStats(data) {
    const total    = data.length;
    const pending  = data.filter(d => d.status === 'pending').length;
    const progress = data.filter(d => ['approved','scheduled','in_progress','material_ready'].includes(d.status)).length;
    const done     = data.filter(d => d.status === 'done').length;

    document.getElementById('statStrip').innerHTML = `
        <div class="bg-white rounded-xl px-4 py-3 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Total</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">${total}</p>
        </div>
        <div class="bg-white rounded-xl px-4 py-3 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Menunggu</p>
            <p class="text-2xl font-bold text-amber-500 mt-1">${pending}</p>
        </div>
        <div class="bg-white rounded-xl px-4 py-3 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Berjalan</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">${progress}</p>
        </div>
        <div class="bg-white rounded-xl px-4 py-3 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Selesai</p>
            <p class="text-2xl font-bold text-teal-600 mt-1">${done}</p>
        </div>
    `;
}

// ─── LOAD REQUESTS ───────────────────────────────────────
async function loadRequests() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('emptyState').classList.add('hidden');
    document.getElementById('list').innerHTML = '';

    try {
        const res  = await fetch('/api/requests', {
            headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' }
        });
        const data = await res.json();

        document.getElementById('loadingState').classList.add('hidden');

        if (!Array.isArray(data) || !data.length) {
            document.getElementById('emptyState').classList.remove('hidden');
            feather.replace();
            return;
        }

        renderStats(data);

        document.getElementById('list').innerHTML = data.map(item => `
            <tr id="row-${item.id}" class="border-b border-slate-50 last:border-0">
                <td class="px-5 py-4">
                    <span class="font-semibold text-slate-700">${item.title}</span>
                </td>
                <td class="px-5 py-4">
                    <span class="bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 rounded-full">${item.category ?? '-'}</span>
                </td>
                <td class="px-5 py-4">${statusBadge(item.status)}</td>
                <td class="px-5 py-4 text-slate-400 text-xs">${formatDate(item.created_at)}</td>
                <td class="px-5 py-4 text-center">
                    <button id="btn-${item.id}" onclick="showDetail(${item.id})"
                        class="btn-detail">
                        <i data-feather="eye" class="w-3.5 h-3.5"></i> Detail
                    </button>
                </td>
            </tr>
        `).join('');

        feather.replace();

    } catch (err) {
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('list').innerHTML = `
            <tr><td colspan="5" class="px-5 py-8 text-center text-red-400 text-sm">
                Gagal memuat data. Periksa koneksi Anda.
            </td></tr>`;
    }
}

// ─── SHOW DETAIL ─────────────────────────────────────────
async function showDetail(id) {
    // Toggle — kalau sudah terbuka, tutup
    const existing = document.getElementById(`detail-${id}`);
    const btn      = document.getElementById(`btn-${id}`);
    if (existing) {
        existing.remove();
        btn.classList.remove('active');
        btn.innerHTML = '<i data-feather="eye" class="w-3.5 h-3.5"></i> Detail';
        feather.replace();
        return;
    }

    // Tutup detail lain
    document.querySelectorAll('[id^="detail-"]').forEach(el => el.remove());
    document.querySelectorAll('[id^="btn-"]').forEach(b => {
        b.classList.remove('active');
        b.innerHTML = '<i data-feather="eye" class="w-3.5 h-3.5"></i> Detail';
    });

    btn.innerHTML = '<div class="w-3 h-3 border border-blue-400 border-t-transparent rounded-full animate-spin"></div> Memuat...';

    try {
        const res  = await fetch(`/api/requests/${id}`, {
            headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' }
        });
        const data = await res.json();

        btn.classList.add('active');
        btn.innerHTML = '<i data-feather="chevron-up" class="w-3.5 h-3.5"></i> Tutup';
        feather.replace();

        const row       = document.getElementById(`row-${id}`);
        const detailRow = document.createElement('tr');
        detailRow.id    = `detail-${id}`;
        detailRow.classList.add('detail-panel');

        detailRow.innerHTML = `
            <td colspan="5" class="px-5 py-0">
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-5 my-2">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-4">
                        <div class="detail-field">
                            <span class="detail-field-label">Judul</span>
                            <span class="detail-field-value font-semibold">${data.title ?? '-'}</span>
                        </div>
                        <div class="detail-field">
                            <span class="detail-field-label">Kategori</span>
                            <span class="detail-field-value">${data.category ?? '-'}</span>
                        </div>
                        <div class="detail-field">
                            <span class="detail-field-label">Status</span>
                            <span class="detail-field-value">${statusBadge(data.status)}</span>
                        </div>
                        <div class="detail-field col-span-2 sm:col-span-3">
                            <span class="detail-field-label">Deskripsi</span>
                            <span class="detail-field-value">${data.description ?? '-'}</span>
                        </div>
                        ${data.technician_name ? `
                        <div class="detail-field">
                            <span class="detail-field-label">Teknisi</span>
                            <span class="detail-field-value">${data.technician_name}</span>
                        </div>` : ''}
                        ${data.scheduled_date ? `
                        <div class="detail-field">
                            <span class="detail-field-label">Jadwal</span>
                            <span class="detail-field-value">${formatDate(data.scheduled_date)}</span>
                        </div>` : ''}
                        ${data.rejection_reason ? `
                        <div class="detail-field col-span-2 sm:col-span-3">
                            <span class="detail-field-label">Alasan Penolakan</span>
                            <span class="detail-field-value text-red-500">${data.rejection_reason}</span>
                        </div>` : ''}
                    </div>
                    ${data.photo ? `
                    <div>
                        <p class="detail-field-label mb-2">Foto</p>
                        <a href="/storage/${data.photo}" target="_blank">
                            <img src="/storage/${data.photo}"
                                class="w-48 rounded-xl border border-slate-200 hover:opacity-90 transition cursor-zoom-in">
                        </a>
                    </div>` : ''}
                </div>
            </td>
        `;

        row.insertAdjacentElement('afterend', detailRow);

    } catch (err) {
        btn.classList.remove('active');
        btn.innerHTML = '<i data-feather="eye" class="w-3.5 h-3.5"></i> Detail';
        feather.replace();
        alert('Gagal memuat detail pengajuan.');
    }
}

// ─── HELPERS ─────────────────────────────────────────────
function goTo(url) { window.location.href = url; }
function logout()  { localStorage.clear(); window.location.href = '/login'; }
function goToDashboard() {
    if (user?.role === 'pic') window.location.href = '/dashboard-pic';
    else if (user?.system_type === 'lite') window.location.href = '/dashboard-lite';
    else window.location.href = '/dashboard-full';
}

// ─── INIT ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadRequests();
});
</script>

</body>
</html>