<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        tr:hover td { background: #f8fafc; }
        .btn { transition: all 0.15s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
    </style>
</head>

<body class="bg-slate-50">

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
                        <h1 class="font-bold text-slate-800 text-lg">Manajemen Peminjaman</h1>
                        <p class="text-xs text-slate-400 mt-0.5">Kelola peminjaman antar cabang</p>
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


<div class="p-8">

<!-- STAT CARDS -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <p class="text-xs text-slate-400">Requested</p>
        <h2 id="stat_requested" class="text-xl font-bold text-yellow-600">0</h2>
    </div>
    <div class="stat-card">
        <p class="text-xs text-slate-400">Approved</p>
        <h2 id="stat_approved" class="text-xl font-bold text-blue-600">0</h2>
    </div>
    <div class="stat-card">
        <p class="text-xs text-slate-400">Dipakai</p>
        <h2 id="stat_picked" class="text-xl font-bold text-indigo-600">0</h2>
    </div>
    <div class="stat-card">
        <p class="text-xs text-slate-400">Selesai</p>
        <h2 id="stat_returned" class="text-xl font-bold text-green-600">0</h2>
    </div>
</div>

<!-- FILTER -->
<div class="flex flex-wrap gap-2 mb-4">
    <button onclick="filterStatus('all')" class="btn px-3 py-1.5 bg-slate-200 rounded-lg text-sm">All</button>
    <button onclick="filterStatus('requested')" class="btn px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg text-sm">Requested</button>
    <button onclick="filterStatus('approved')" class="btn px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-sm">Approved</button>
    <button onclick="filterStatus('picked')" class="btn px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-lg text-sm">Dipakai</button>
    <button onclick="filterStatus('returned')" class="btn px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm">Selesai</button>
</div>

<!-- TABLE -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Aset & Lokasi Asal</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Peminjam</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tujuan</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal & Alasan</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody id="borrowTable"></tbody>
    </table>
    </div>
</div>

</div>
</div>
</div>

<script>
const token = localStorage.getItem('token');
let allData = [];
let currentFilter = 'all';

function statusBadge(s) {
    const map = {
        requested: 'bg-yellow-50 text-yellow-700 border-yellow-200',
        approved:  'bg-blue-50 text-blue-700 border-blue-200',
        picked:    'bg-indigo-50 text-indigo-700 border-indigo-200',
        returned:  'bg-green-50 text-green-700 border-green-200',
        rejected:  'bg-red-50 text-red-700 border-red-200'
    };
    const label = {
        requested: 'Menunggu', approved: 'Disetujui',
        picked: 'Dipakai', returned: 'Selesai', rejected: 'Ditolak'
    };
    return `<span class="px-2.5 py-1 rounded-full text-xs font-semibold border ${map[s] || 'bg-slate-100'}">${label[s] ?? s}</span>`;
}

function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

function updateStats(data) {
    const count = (s) => data.filter(d => d.status === s).length;
    document.getElementById('stat_requested').textContent = count('requested');
    document.getElementById('stat_approved').textContent  = count('approved');
    document.getElementById('stat_picked').textContent    = count('picked');
    document.getElementById('stat_returned').textContent  = count('returned');
}

function renderTable(data) {
    const table = document.getElementById('borrowTable');

    if (!data.length) {
        table.innerHTML = `<tr><td colspan="6" class="text-center py-10 text-slate-400">Tidak ada data</td></tr>`;
        return;
    }

    table.innerHTML = data.map(b => `
        <tr class="border-b border-slate-50">
            <!-- Aset & Lokasi Asal -->
            <td class="px-5 py-4">
                <p class="font-semibold text-slate-700">${b.asset?.name ?? '-'}</p>
                <p class="text-xs text-slate-400 mt-0.5">
                    Cabang: ${b.asset?.branch?.name ?? '-'} &middot;
                    Ruangan: ${b.asset?.room?.name ?? '-'}
                </p>
                ${b.qty ? `<p class="text-xs text-slate-400 mt-0.5">Qty: <span class="font-semibold text-slate-600">${b.qty}</span></p>` : ''}
            </td>

            <!-- Peminjam -->
            <td class="px-5 py-4">
                <p class="text-slate-700 font-medium">${b.user?.name ?? '-'}</p>
                <p class="text-xs text-slate-400">${b.request_branch?.name ?? '-'}</p>
            </td>

            <!-- Tujuan -->
            <td class="px-5 py-4">
                <div class="flex items-center gap-1.5 text-xs">
                    <i data-feather="arrow-right" class="w-3 h-3 text-slate-300 shrink-0"></i>
                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-medium">${b.destination_branch?.name ?? '-'}</span>
                </div>
                <p class="text-xs text-slate-400 mt-1 pl-4">${b.destination_room?.name ?? '-'}</p>
            </td>

            <!-- Tanggal & Alasan -->
            <td class="px-5 py-4 text-slate-500 text-xs">
                <p>${formatDate(b.start_date)} — ${formatDate(b.end_date)}</p>
                ${b.reason ? `<p class="mt-0.5 text-slate-400 italic">"${b.reason}"</p>` : ''}
                ${b.notes  ? `<p class="mt-0.5 text-slate-400">${b.notes}</p>` : ''}
            </td>

            <!-- Status -->
            <td class="px-5 py-4">${statusBadge(b.status)}</td>

            <!-- Aksi -->
            <td class="px-5 py-4">
                <div class="flex gap-2 flex-wrap">
                    ${b.status === 'requested' ? `
                        <button onclick="approve(${b.id})" class="btn bg-green-50 text-green-600 px-3 py-1 rounded-lg text-xs border border-green-200 hover:bg-green-100">Approve</button>
                        <button onclick="reject(${b.id})"  class="btn bg-red-50 text-red-600 px-3 py-1 rounded-lg text-xs border border-red-200 hover:bg-red-100">Reject</button>
                    ` : ''}
                    ${b.status === 'approved' ? `
                        <button onclick="markPicked(${b.id})" class="btn bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-xs border border-indigo-200 hover:bg-indigo-100">Dipakai</button>
                    ` : ''}
                    ${b.status === 'picked' ? `
                        <button onclick="markReturned(${b.id})" class="btn bg-green-50 text-green-600 px-3 py-1 rounded-lg text-xs border border-green-200 hover:bg-green-100">Selesai</button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');

    feather.replace();
}

function filterStatus(status) {
    currentFilter = status;
    if (status === 'all') renderTable(allData);
    else renderTable(allData.filter(d => d.status === status));
}

async function loadBorrowings() {
    const res  = await fetch('/api/borrowings', { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();
    allData = data;
    updateStats(data);
    renderTable(data);
}

async function approve(id) {
    if (!confirm('Setujui pengajuan peminjaman ini?')) return;
    const res  = await fetch('/api/borrowings/' + id + '/approve', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify({})
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message ?? 'Gagal approve');
    loadBorrowings();
}

async function reject(id) {
    if (!confirm('Tolak pengajuan peminjaman ini?')) return;
    const res  = await fetch('/api/borrowings/' + id + '/reject', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message ?? 'Gagal reject');
    loadBorrowings();
}

async function markPicked(id) {
    const res  = await fetch('/api/borrowings/' + id + '/picked', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message ?? 'Gagal update');
    loadBorrowings();
}

async function markReturned(id) {
    const res  = await fetch('/api/borrowings/' + id + '/returned', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message ?? 'Gagal update');
    loadBorrowings();
}

function goTo(url) {
    window.location.href = url;
}

loadBorrowings();
feather.replace();
</script>

</body>
</html>