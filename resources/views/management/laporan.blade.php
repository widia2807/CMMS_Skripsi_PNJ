<!DOCTYPE html>
<html>
<head>
    <title>Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }

        .icon-box {
            width: 40px; height: 40px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #1e293b;
            color: white;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.15s;
            cursor: pointer;
        }
        .btn-export:hover { background: #334155; transform: translateY(-1px); }

        tr { transition: background 0.1s; }
        tr:hover td { background: #f8fafc; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

        .progress-bar {
            height: 8px;
            border-radius: 99px;
            background: #f1f5f9;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.6s ease;
        }
    </style>
</head>
<body>

@include('components.sidebar')

<div class="flex min-h-screen">
<div class="flex-1 md:ml-64">

    <!-- TOPBAR -->
    <div class="bg-white border-b border-slate-100 px-4 md:px-8 py-4 flex justify-between items-center sticky top-0 z-30">
        <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-400">
                <i data-feather="menu" class="w-5 h-5"></i>
            </button>
            <div>
                <h1 class="font-bold text-slate-800 text-lg">Laporan</h1>
                <p class="text-xs text-slate-400 mt-0.5">Rekap & analisis pekerjaan</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <!-- Filter bulan -->
            <input type="month" id="filterMonth"
                class="text-sm border border-slate-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:border-slate-400"
                onchange="loadAll()">
            <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                <div class="w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center">
                    <span id="userInitial" class="text-white font-bold text-xs"></span>
                </div>
                <span id="userInfo" class="text-sm font-medium text-slate-600"></span>
            </div>
        </div>
    </div>

    <div class="p-6 md:p-8 space-y-6">

        <!-- STAT CARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Perbaikan</p>
                    <div class="icon-box bg-orange-50"><i data-feather="tool" class="w-4 h-4 text-orange-500"></i></div>
                </div>
                <p id="statRepair" class="text-3xl font-bold text-slate-800">0</p>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Perbaikan Selesai</p>
                    <div class="icon-box bg-green-50"><i data-feather="check-circle" class="w-4 h-4 text-green-500"></i></div>
                </div>
                <p id="statRepairDone" class="text-3xl font-bold text-green-500">0</p>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Maintenance</p>
                    <div class="icon-box bg-blue-50"><i data-feather="calendar" class="w-4 h-4 text-blue-500"></i></div>
                </div>
                <p id="statMaint" class="text-3xl font-bold text-slate-800">0</p>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Maintenance Selesai</p>
                    <div class="icon-box bg-green-50"><i data-feather="check-circle" class="w-4 h-4 text-green-500"></i></div>
                </div>
                <p id="statMaintDone" class="text-3xl font-bold text-green-500">0</p>
            </div>
        </div>

        <!-- CHARTS ROW -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Chart Perbaikan per Status -->
            <div class="card p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="icon-box bg-orange-100">
                        <i data-feather="pie-chart" class="w-4 h-4 text-orange-600"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-sm">Status Perbaikan</h2>
                        <p class="text-xs text-slate-400">Distribusi status request perbaikan</p>
                    </div>
                </div>
                <canvas id="chartRepair" height="200"></canvas>
            </div>

            <!-- Chart Maintenance per Status -->
            <div class="card p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="icon-box bg-blue-100">
                        <i data-feather="bar-chart-2" class="w-4 h-4 text-blue-600"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-sm">Status Maintenance</h2>
                        <p class="text-xs text-slate-400">Distribusi status maintenance terjadwal</p>
                    </div>
                </div>
                <canvas id="chartMaint" height="200"></canvas>
            </div>

        </div>

        <!-- PROGRESS PER KATEGORI -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="card p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="icon-box bg-purple-100">
                        <i data-feather="tag" class="w-4 h-4 text-purple-600"></i>
                    </div>
                    <h2 class="font-bold text-slate-800 text-sm">Perbaikan per Kategori</h2>
                </div>
                <div id="repairByCategory" class="space-y-4"></div>
            </div>

            <div class="card p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="icon-box bg-indigo-100">
                        <i data-feather="layers" class="w-4 h-4 text-indigo-600"></i>
                    </div>
                    <h2 class="font-bold text-slate-800 text-sm">Maintenance per Kategori</h2>
                </div>
                <div id="maintByCategory" class="space-y-4"></div>
            </div>

        </div>

        <!-- TABEL PERBAIKAN -->
        <div class="card">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="icon-box bg-orange-100">
                        <i data-feather="tool" class="w-4 h-4 text-orange-600"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800">Detail Perbaikan Gedung</h2>
                        <p class="text-xs text-slate-400">Rekap seluruh request perbaikan</p>
                    </div>
                </div>
                <button onclick="exportRepair()" class="btn-export text-xs">
                    <i data-feather="download" class="w-3.5 h-3.5"></i> Export
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Pekerjaan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Cabang</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Kategori</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Tukang</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Urgensi</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Selesai</th>
                        </tr>
                    </thead>
                    <tbody id="repairTableBody">
                        <tr><td colspan="7" class="px-5 py-8 text-center text-slate-400">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TABEL MAINTENANCE -->
        <div class="card">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="icon-box bg-blue-100">
                        <i data-feather="calendar" class="w-4 h-4 text-blue-600"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800">Detail Maintenance Terjadwal</h2>
                        <p class="text-xs text-slate-400">Rekap seluruh jadwal maintenance</p>
                    </div>
                </div>
                <button onclick="exportMaint()" class="btn-export text-xs">
                    <i data-feather="download" class="w-3.5 h-3.5"></i> Export
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Judul</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Kategori</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Tukang</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Periode</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Tanggal</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Selesai</th>
                        </tr>
                    </thead>
                    <tbody id="maintTableBody">
                        <tr><td colspan="7" class="px-5 py-8 text-center text-slate-400">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</div>

<script>
const token = localStorage.getItem('token');
if (!token) { window.location.href = '/login'; }

const user = JSON.parse(localStorage.getItem('user') || '{}');
document.getElementById('userInfo').textContent    = (user.name ?? '-') + ' · Management';
document.getElementById('userInitial').textContent = user.name
    ? user.name.split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase() : 'MG';

// Set default bulan sekarang
const now = new Date();
document.getElementById('filterMonth').value =
    now.getFullYear() + '-' + String(now.getMonth()+1).padStart(2,'0');

const STATUS_REPAIR = {
    pending:          { label: 'Pending',        cls: 'bg-amber-100 text-amber-700' },
    approved:         { label: 'Disetujui',      cls: 'bg-green-100 text-green-700' },
    waiting_material: { label: 'Butuh Material', cls: 'bg-orange-100 text-orange-700' },
    material_ready:   { label: 'Material Siap',  cls: 'bg-cyan-100 text-cyan-700' },
    on_progress:      { label: 'Dikerjakan',     cls: 'bg-purple-100 text-purple-700' },
    done:             { label: 'Selesai',         cls: 'bg-slate-100 text-slate-600' },
    rejected:         { label: 'Ditolak',         cls: 'bg-red-100 text-red-700' },
};
const STATUS_MAINT = {
    pending:     { label: 'Menunggu',    cls: 'bg-amber-100 text-amber-700' },
    confirmed:   { label: 'Dikonfirmasi', cls: 'bg-blue-100 text-blue-700' },
    in_progress: { label: 'Berjalan',   cls: 'bg-purple-100 text-purple-700' },
    done:        { label: 'Selesai',     cls: 'bg-green-100 text-green-700' },
};
const URGENCY = { low:'🟢 Santai', medium:'🟡 Segera', high:'🔴 Prioritas' };
const PERIOD  = { weekly:'Mingguan', monthly:'Bulanan', quarterly:'Triwulan', yearly:'Tahunan' };

function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
}

let repairChart = null;
let maintChart  = null;

/* ─── LOAD ALL ─── */
async function loadAll() {
    await Promise.all([loadRepair(), loadMaint()]);
}

/* ─── PERBAIKAN ─── */
let repairData = [];

async function loadRepair() {
    const month = document.getElementById('filterMonth').value;
    const params = month ? '?month=' + month : '';
    try {
        const res  = await fetch('/api/requests' + params, { headers: { Authorization: 'Bearer ' + token } });
        const data = await res.json();
        repairData = Array.isArray(data) ? data : (data.data ?? []);
        renderRepairTable();
        renderRepairStats();
        renderRepairChart();
        renderRepairByCategory();
    } catch (err) { console.error(err); }
}

function renderRepairStats() {
    document.getElementById('statRepair').textContent     = repairData.length;
    document.getElementById('statRepairDone').textContent = repairData.filter(r => r.status === 'done').length;
}

function renderRepairTable() {
    const tbody = document.getElementById('repairTableBody');
    if (!repairData.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-5 py-8 text-center text-slate-400">Tidak ada data.</td></tr>`;
        return;
    }
    tbody.innerHTML = repairData.map(item => {
        const st = STATUS_REPAIR[item.status] ?? { label: item.status, cls: 'bg-slate-100 text-slate-500' };
        return `
        <tr class="border-b border-slate-50 last:border-0">
            <td class="px-5 py-3.5">
                <p class="font-semibold text-slate-700">${item.title ?? '-'}</p>
            </td>
            <td class="px-5 py-3.5 text-slate-500 text-xs">${item.branch ?? '-'}</td>
            <td class="px-5 py-3.5 text-slate-500 text-xs">${item.category ?? '-'}</td>
            <td class="px-5 py-3.5 text-slate-500 text-xs">${item.technician_name ?? '-'}</td>
            <td class="px-5 py-3.5 text-xs">${item.urgency ? URGENCY[item.urgency] : '-'}</td>
            <td class="px-5 py-3.5"><span class="status-badge ${st.cls}">${st.label}</span></td>
            <td class="px-5 py-3.5 text-slate-400 text-xs">${formatDate(item.completed_at)}</td>
        </tr>`;
    }).join('');
}

function renderRepairChart() {
    const counts = {};
    repairData.forEach(r => {
        const lbl = STATUS_REPAIR[r.status]?.label ?? r.status;
        counts[lbl] = (counts[lbl] ?? 0) + 1;
    });
    const labels = Object.keys(counts);
    const values = Object.values(counts);
    const colors = ['#f59e0b','#22c55e','#f97316','#06b6d4','#a855f7','#94a3b8','#ef4444'];

    if (repairChart) repairChart.destroy();
    repairChart = new Chart(document.getElementById('chartRepair'), {
        type: 'doughnut',
        data: { labels, datasets: [{ data: values, backgroundColor: colors, borderWidth: 2, borderColor: '#fff' }] },
        options: { plugins: { legend: { position: 'bottom', labels: { font: { family: 'Plus Jakarta Sans', size: 11 }, padding: 12 } } }, cutout: '65%' }
    });
}

function renderRepairByCategory() {
    const cats = {};
    repairData.forEach(r => {
        const cat = r.category ?? 'Lainnya';
        if (!cats[cat]) cats[cat] = { total: 0, done: 0 };
        cats[cat].total++;
        if (r.status === 'done') cats[cat].done++;
    });
    const el = document.getElementById('repairByCategory');
    if (!Object.keys(cats).length) { el.innerHTML = '<p class="text-xs text-slate-400 text-center py-4">Tidak ada data.</p>'; return; }
    el.innerHTML = Object.entries(cats).sort((a,b) => b[1].total - a[1].total).map(([cat, v]) => {
        const pct = Math.round((v.done / v.total) * 100);
        return `
        <div>
            <div class="flex justify-between items-center mb-1">
                <span class="text-sm font-medium text-slate-700">${cat}</span>
                <span class="text-xs text-slate-400">${v.done}/${v.total}</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill bg-orange-400" style="width:${pct}%"></div>
            </div>
        </div>`;
    }).join('');
}

/* ─── MAINTENANCE ─── */
let maintData = [];

async function loadMaint() {
    const month = document.getElementById('filterMonth').value;
    const params = month ? '?month=' + month : '';
    try {
        const res  = await fetch('/api/scheduled-maintenances' + params, { headers: { Authorization: 'Bearer ' + token } });
        const data = await res.json();
        maintData = Array.isArray(data) ? data : (data.data ?? []);
        renderMaintTable();
        renderMaintStats();
        renderMaintChart();
        renderMaintByCategory();
    } catch (err) { console.error(err); }
}

function renderMaintStats() {
    document.getElementById('statMaint').textContent     = maintData.length;
    document.getElementById('statMaintDone').textContent = maintData.filter(m => m.status === 'done').length;
}

function renderMaintTable() {
    const tbody = document.getElementById('maintTableBody');
    if (!maintData.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-5 py-8 text-center text-slate-400">Tidak ada data.</td></tr>`;
        return;
    }
    tbody.innerHTML = maintData.map(item => {
        const st = STATUS_MAINT[item.status] ?? { label: item.status, cls: 'bg-slate-100 text-slate-500' };
        return `
        <tr class="border-b border-slate-50 last:border-0">
            <td class="px-5 py-3.5">
                <p class="font-semibold text-slate-700">${item.title ?? '-'}</p>
                ${item.sub_category_name ? `<span class="text-xs bg-blue-50 text-blue-500 px-2 py-0.5 rounded-full">${item.sub_category_name}</span>` : ''}
            </td>
            <td class="px-5 py-3.5 text-slate-500 text-xs">${item.category_name ?? item.category ?? '-'}</td>
            <td class="px-5 py-3.5 text-slate-500 text-xs">${item.worker_name ?? '-'}</td>
            <td class="px-5 py-3.5 text-xs text-slate-500">${PERIOD[item.period] ?? item.period ?? '-'}</td>
            <td class="px-5 py-3.5 text-xs text-slate-500">${formatDate(item.scheduled_date)}</td>
            <td class="px-5 py-3.5"><span class="status-badge ${st.cls}">${st.label}</span></td>
            <td class="px-5 py-3.5 text-slate-400 text-xs">${formatDate(item.completed_at)}</td>
        </tr>`;
    }).join('');
}

function renderMaintChart() {
    const counts = {};
    maintData.forEach(m => {
        const lbl = STATUS_MAINT[m.status]?.label ?? m.status;
        counts[lbl] = (counts[lbl] ?? 0) + 1;
    });
    const labels = Object.keys(counts);
    const values = Object.values(counts);
    const colors = ['#f59e0b','#3b82f6','#a855f7','#22c55e'];

    if (maintChart) maintChart.destroy();
    maintChart = new Chart(document.getElementById('chartMaint'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } } },
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } } }
            }
        }
    });
}

function renderMaintByCategory() {
    const cats = {};
    maintData.forEach(m => {
        const cat = m.category_name ?? m.category ?? 'Lainnya';
        if (!cats[cat]) cats[cat] = { total: 0, done: 0 };
        cats[cat].total++;
        if (m.status === 'done') cats[cat].done++;
    });
    const el = document.getElementById('maintByCategory');
    if (!Object.keys(cats).length) { el.innerHTML = '<p class="text-xs text-slate-400 text-center py-4">Tidak ada data.</p>'; return; }
    el.innerHTML = Object.entries(cats).sort((a,b) => b[1].total - a[1].total).map(([cat, v]) => {
        const pct = Math.round((v.done / v.total) * 100);
        return `
        <div>
            <div class="flex justify-between items-center mb-1">
                <span class="text-sm font-medium text-slate-700">${cat}</span>
                <span class="text-xs text-slate-400">${v.done}/${v.total}</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill bg-blue-400" style="width:${pct}%"></div>
            </div>
        </div>`;
    }).join('');
}

/* ─── EXPORT (print as table) ─── */
function exportRepair() {
    const rows = repairData.map(r => [
        r.title ?? '-', r.branch ?? '-', r.category ?? '-',
        r.technician_name ?? '-', r.urgency ?? '-',
        STATUS_REPAIR[r.status]?.label ?? r.status,
        formatDate(r.completed_at)
    ]);
    printTable('Laporan Perbaikan Gedung',
        ['Pekerjaan','Cabang','Kategori','Tukang','Urgensi','Status','Selesai'],
        rows);
}

function exportMaint() {
    const rows = maintData.map(m => [
        m.title ?? '-', m.category_name ?? m.category ?? '-',
        m.worker_name ?? '-', PERIOD[m.period] ?? m.period ?? '-',
        formatDate(m.scheduled_date),
        STATUS_MAINT[m.status]?.label ?? m.status,
        formatDate(m.completed_at)
    ]);
    printTable('Laporan Maintenance Terjadwal',
        ['Judul','Kategori','Tukang','Periode','Tanggal','Status','Selesai'],
        rows);
}

function printTable(title, headers, rows) {
    const win = window.open('', '_blank');
    win.document.write(`
        <html><head><title>${title}</title>
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; padding: 24px; }
            h2 { font-size: 18px; margin-bottom: 16px; color: #1e293b; }
            table { width: 100%; border-collapse: collapse; font-size: 13px; }
            th { background: #f8fafc; text-align: left; padding: 10px 12px; border: 1px solid #e2e8f0; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; }
            td { padding: 9px 12px; border: 1px solid #f1f5f9; color: #334155; }
            tr:nth-child(even) td { background: #f8fafc; }
        </style></head>
        <body>
            <h2>${title}</h2>
            <p style="font-size:12px;color:#94a3b8;margin-bottom:12px;">Dicetak: ${new Date().toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'})}</p>
            <table>
                <thead><tr>${headers.map(h=>`<th>${h}</th>`).join('')}</tr></thead>
                <tbody>${rows.map(r=>`<tr>${r.map(c=>`<td>${c}</td>`).join('')}</tr>`).join('')}</tbody>
            </table>
        </body></html>`);
    win.document.close();
    win.print();
}

function goTo(url) { window.location.href = url; }
function goToDashboard() { window.location.href = '/dashboard-management'; }

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadAll();
});
</script>
</body>
</html>