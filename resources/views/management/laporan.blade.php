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
        .card { background: white; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .stat-card { background: white; border-radius: 16px; padding: 20px 24px; border: 1px solid #f1f5f9; box-shadow: 0 1px 3px rgba(0,0,0,0.04); transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .icon-box { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .status-badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; white-space: nowrap; }
        .btn-export { display: inline-flex; align-items: center; gap: 6px; background: #1e293b; color: white; border-radius: 10px; padding: 8px 14px; font-size: 12px; font-weight: 600; transition: all 0.15s; cursor: pointer; white-space: nowrap; }
        .btn-export:hover { background: #334155; transform: translateY(-1px); }
        tr { transition: background 0.1s; }
        tr:hover td { background: #f8fafc; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        .progress-bar { height: 8px; border-radius: 99px; background: #f1f5f9; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 99px; transition: width 0.6s ease; }
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .chart-container { position: relative; width: 100%; }
        @media (max-width: 640px) {
            .topbar-inner { flex-direction: column; align-items: flex-start; gap: 10px; }
            .topbar-right { width: 100%; display: flex; gap: 8px; align-items: center; }
            #filterMonth { flex: 1; min-width: 0; }
            .chart-container { max-height: 260px; }
        }
    </style>
</head>
<body>

@include('components.sidebar')

<div class="flex min-h-screen">
<div class="flex-1 md:ml-64">

    <!-- TOPBAR -->
    <div class="bg-white border-b border-slate-100 px-4 md:px-8 py-4 sticky top-0 z-30">
        <div class="topbar-inner flex justify-between items-center gap-3">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-400">
                    <i data-feather="menu" class="w-5 h-5"></i>
                </button>
                <div>
                    <h1 class="font-bold text-slate-800 text-lg">Laporan</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Rekap & analisis pekerjaan</p>
                </div>
            </div>
            <div class="topbar-right flex items-center gap-2">
                <div class="relative flex-1 sm:flex-none">
                    <input type="month" id="filterMonth"
                        class="w-full sm:w-auto text-sm border border-slate-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:border-slate-400"
                        onchange="applyFilter()">
                </div>
                <button onclick="clearFilter()" id="btnClearFilter" title="Tampilkan semua"
                    class="hidden text-xs text-slate-500 border border-slate-200 rounded-xl px-3 py-2 bg-white hover:bg-slate-50 whitespace-nowrap">
                    Semua
                </button>
                <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 flex-shrink-0">
                    <div class="w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center">
                        <span id="userInitial" class="text-white font-bold text-xs"></span>
                    </div>
                    <span id="userInfo" class="text-sm font-medium text-slate-600 hidden sm:inline"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-8 space-y-6">

        <!-- STAT CARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Perbaikan</p>
                    <div class="icon-box bg-orange-50"><i data-feather="tool" class="w-4 h-4 text-orange-500"></i></div>
                </div>
                <p id="statRepair" class="text-2xl md:text-3xl font-bold text-slate-800">0</p>
                <p id="statRepairPeriode" class="text-xs text-slate-400 mt-1">-</p>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Perbaikan Selesai</p>
                    <div class="icon-box bg-green-50"><i data-feather="check-circle" class="w-4 h-4 text-green-500"></i></div>
                </div>
                <p id="statRepairDone" class="text-2xl md:text-3xl font-bold text-green-500">0</p>
                <p id="statRepairDonePct" class="text-xs text-slate-400 mt-1">-</p>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Maintenance</p>
                    <div class="icon-box bg-blue-50"><i data-feather="calendar" class="w-4 h-4 text-blue-500"></i></div>
                </div>
                <p id="statMaint" class="text-2xl md:text-3xl font-bold text-slate-800">0</p>
                <p id="statMaintPeriode" class="text-xs text-slate-400 mt-1">-</p>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Maintenance Selesai</p>
                    <div class="icon-box bg-green-50"><i data-feather="check-circle" class="w-4 h-4 text-green-500"></i></div>
                </div>
                <p id="statMaintDone" class="text-2xl md:text-3xl font-bold text-green-500">0</p>
                <p id="statMaintDonePct" class="text-xs text-slate-400 mt-1">-</p>
            </div>
        </div>

        <!-- CHARTS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
            <div class="card p-5 md:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="icon-box bg-orange-100"><i data-feather="pie-chart" class="w-4 h-4 text-orange-600"></i></div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-sm">Status Perbaikan</h2>
                        <p class="text-xs text-slate-400">Distribusi status request perbaikan</p>
                    </div>
                </div>
                <div class="chart-container" style="max-height:280px;">
                    <canvas id="chartRepair"></canvas>
                </div>
            </div>
            <div class="card p-5 md:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="icon-box bg-blue-100"><i data-feather="bar-chart-2" class="w-4 h-4 text-blue-600"></i></div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-sm">Status Maintenance</h2>
                        <p class="text-xs text-slate-400">Distribusi status maintenance terjadwal</p>
                    </div>
                </div>
                <div class="chart-container" style="max-height:280px;">
                    <canvas id="chartMaint"></canvas>
                </div>
            </div>
        </div>

        <!-- PROGRESS PER KATEGORI -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
            <div class="card p-5 md:p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="icon-box bg-purple-100"><i data-feather="tag" class="w-4 h-4 text-purple-600"></i></div>
                    <h2 class="font-bold text-slate-800 text-sm">Perbaikan per Kategori</h2>
                </div>
                <div id="repairByCategory" class="space-y-4"></div>
            </div>
            <div class="card p-5 md:p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="icon-box bg-indigo-100"><i data-feather="layers" class="w-4 h-4 text-indigo-600"></i></div>
                    <h2 class="font-bold text-slate-800 text-sm">Maintenance per Kategori</h2>
                </div>
                <div id="maintByCategory" class="space-y-4"></div>
            </div>
        </div>

        <!-- TABEL PERBAIKAN -->
        <div class="card">
            <div class="px-5 md:px-6 py-5 border-b border-slate-100 flex justify-between items-center gap-3">
                <div class="flex items-center gap-3">
                    <div class="icon-box bg-orange-100"><i data-feather="tool" class="w-4 h-4 text-orange-600"></i></div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-sm md:text-base">Detail Perbaikan Gedung</h2>
                        <p class="text-xs text-slate-400" id="repairTableSubtitle">Rekap seluruh request perbaikan</p>
                    </div>
                </div>
                <button onclick="exportRepair()" class="btn-export">
                    <i data-feather="download" class="w-3.5 h-3.5"></i>
                    <span>Export</span>
                </button>
            </div>
            <div class="table-wrap">
                <table class="w-full text-sm" style="min-width:600px">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Pekerjaan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Cabang</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Kategori</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Tukang</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Urgensi</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Tgl Jadwal</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">No. SPK</th>
                        </tr>
                    </thead>
                    <tbody id="repairTableBody">
                        <tr><td colspan="8" class="px-5 py-8 text-center text-slate-400">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TABEL MAINTENANCE -->
        <div class="card">
            <div class="px-5 md:px-6 py-5 border-b border-slate-100 flex justify-between items-center gap-3">
                <div class="flex items-center gap-3">
                    <div class="icon-box bg-blue-100"><i data-feather="calendar" class="w-4 h-4 text-blue-600"></i></div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-sm md:text-base">Detail Maintenance Terjadwal</h2>
                        <p class="text-xs text-slate-400" id="maintTableSubtitle">Rekap seluruh jadwal maintenance</p>
                    </div>
                </div>
                <button onclick="exportMaint()" class="btn-export">
                    <i data-feather="download" class="w-3.5 h-3.5"></i>
                    <span>Export</span>
                </button>
            </div>
            <div class="table-wrap">
                <table class="w-full text-sm" style="min-width:700px">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Judul</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Kategori</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Tukang</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Periode</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Tgl Jadwal</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Tgl Selesai</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">No. SPK</th>
                        </tr>
                    </thead>
                    <tbody id="maintTableBody">
                        <tr><td colspan="8" class="px-5 py-8 text-center text-slate-400">Memuat...</td></tr>
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
const companyId = user.company_id ?? null;

document.getElementById('userInfo').textContent    = (user.name ?? '-') + ' · Management';
document.getElementById('userInitial').textContent = user.name
    ? user.name.split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase() : 'MG';

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
    pending:     { label: 'Menunggu',     cls: 'bg-amber-100 text-amber-700' },
    confirmed:   { label: 'Dikonfirmasi', cls: 'bg-blue-100 text-blue-700' },
    in_progress: { label: 'Berjalan',    cls: 'bg-purple-100 text-purple-700' },
    done:        { label: 'Selesai',      cls: 'bg-green-100 text-green-700' },
};
const URGENCY_LABEL = { low:'🟢 Santai', medium:'🟡 Segera', high:'🔴 Prioritas' };
const URGENCY_TEXT  = { low:'Santai', medium:'Segera', high:'Prioritas' };
const PERIOD = { weekly:'Mingguan', monthly:'Bulanan', quarterly:'Triwulan', yearly:'Tahunan' };

function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' });
}
function formatDateTime(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit' });
}

function getPeriodeLabel() {
    const month = document.getElementById('filterMonth').value;
    if (!month) return 'Semua Periode';
    return new Date(month + '-01').toLocaleDateString('id-ID', { month:'long', year:'numeric' });
}

function matchesMonth(dateStr) {
    const month = document.getElementById('filterMonth').value;
    if (!month) return true;
    if (!dateStr) return false;
    const d = new Date(dateStr);
    const [y, m] = month.split('-').map(Number);
    return d.getFullYear() === y && (d.getMonth() + 1) === m;
}

function buildParams() {
    const p = new URLSearchParams();
    if (companyId) p.set('company_id', companyId);
    return p.toString() ? '?' + p.toString() : '';
}

function applyFilter() {
    const month = document.getElementById('filterMonth').value;
    document.getElementById('btnClearFilter').classList.toggle('hidden', !month);
    renderAll();
}

function clearFilter() {
    document.getElementById('filterMonth').value = '';
    document.getElementById('btnClearFilter').classList.add('hidden');
    renderAll();
}

function renderAll() {
    renderRepairTable(); renderRepairStats(); renderRepairChart(); renderRepairByCategory();
    renderMaintTable(); renderMaintStats(); renderMaintChart(); renderMaintByCategory();
    updateSubtitles();
}

function updateSubtitles() {
    const lbl = getPeriodeLabel();
    document.getElementById('repairTableSubtitle').textContent = 'Periode: ' + lbl;
    document.getElementById('maintTableSubtitle').textContent  = 'Periode: ' + lbl;
}

let repairChart = null, maintChart = null;
let repairRaw = [], maintRaw = [];

async function loadAll() {
    await Promise.all([loadRepair(), loadMaint()]);
    renderAll();
}

async function loadRepair() {
    try {
        const res  = await fetch('/api/requests' + buildParams(), { headers: { Authorization: 'Bearer ' + token } });
        const data = await res.json();
        repairRaw  = Array.isArray(data) ? data : (data.data ?? []);
    } catch (err) { console.error(err); }
}

async function loadMaint() {
    try {
        const res  = await fetch('/api/scheduled-maintenances' + buildParams(), { headers: { Authorization: 'Bearer ' + token } });
        const data = await res.json();
        maintRaw   = Array.isArray(data) ? data : (data.data ?? []);
    } catch (err) { console.error(err); }
}

/* ── REPAIR ── */
function getRepairFiltered() { return repairRaw.filter(r => matchesMonth(r.schedule_date)); }

function renderRepairStats() {
    const data = getRepairFiltered();
    const done = data.filter(r => r.status === 'done').length;
    const pct  = data.length ? Math.round((done/data.length)*100) : 0;
    document.getElementById('statRepair').textContent        = data.length;
    document.getElementById('statRepairDone').textContent    = done;
    document.getElementById('statRepairPeriode').textContent = getPeriodeLabel();
    document.getElementById('statRepairDonePct').textContent = data.length ? pct+'% selesai' : '-';
}

function renderRepairTable() {
    const data  = getRepairFiltered();
    const tbody = document.getElementById('repairTableBody');
    if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="8" class="px-5 py-8 text-center text-slate-400">Tidak ada data${document.getElementById('filterMonth').value?' untuk periode ini':''}.</td></tr>`;
        return;
    }
    tbody.innerHTML = data.map(item => {
        const st = STATUS_REPAIR[item.status] ?? { label: item.status, cls: 'bg-slate-100 text-slate-500' };
        return `
        <tr class="border-b border-slate-50 last:border-0">
            <td class="px-5 py-3.5">
                <p class="font-semibold text-slate-700">${item.title ?? '-'}</p>
                <p class="text-xs text-slate-400">${item.description ?? ''}</p>
            </td>
            <td class="px-5 py-3.5 text-slate-500 text-xs">${item.branch ?? '-'}</td>
            <td class="px-5 py-3.5 text-slate-500 text-xs">
                <p>${item.category ?? '-'}</p>
                ${item.sub_category && item.sub_category !== '-' ? `<p class="text-slate-400">${item.sub_category}</p>` : ''}
            </td>
            <td class="px-5 py-3.5 text-slate-500 text-xs">${item.technician_name ?? '<span class="text-slate-300">Belum assign</span>'}</td>
            <td class="px-5 py-3.5 text-xs">${item.urgency ? URGENCY_LABEL[item.urgency] : '-'}</td>
            <td class="px-5 py-3.5 text-slate-500 text-xs">${formatDate(item.schedule_date)}</td>
            <td class="px-5 py-3.5"><span class="status-badge ${st.cls}">${st.label}</span></td>
            <td class="px-5 py-3.5 text-slate-400 text-xs">${item.spk_number ?? '-'}</td>
        </tr>`;
    }).join('');
}

function renderRepairChart() {
    const data = getRepairFiltered();
    const counts = {};
    data.forEach(r => { const l = STATUS_REPAIR[r.status]?.label??r.status; counts[l]=(counts[l]??0)+1; });
    const labels = Object.keys(counts), values = Object.values(counts);
    const colors = ['#f59e0b','#22c55e','#f97316','#06b6d4','#a855f7','#94a3b8','#ef4444'];
    if (repairChart) repairChart.destroy();
    if (!labels.length) return;
    repairChart = new Chart(document.getElementById('chartRepair'), {
        type: 'doughnut',
        data: { labels, datasets: [{ data: values, backgroundColor: colors, borderWidth: 2, borderColor: '#fff' }] },
        options: { responsive:true, maintainAspectRatio:true, plugins: { legend: { position:'bottom', labels:{ font:{family:'Plus Jakarta Sans',size:11}, padding:10, boxWidth:12 } } }, cutout:'60%' }
    });
}

function renderRepairByCategory() {
    const data = getRepairFiltered();
    const cats = {};
    data.forEach(r => { const c=r.category??'Lainnya'; if(!cats[c])cats[c]={total:0,done:0}; cats[c].total++; if(r.status==='done')cats[c].done++; });
    const el = document.getElementById('repairByCategory');
    if (!Object.keys(cats).length) { el.innerHTML='<p class="text-xs text-slate-400 text-center py-4">Tidak ada data.</p>'; return; }
    el.innerHTML = Object.entries(cats).sort((a,b)=>b[1].total-a[1].total).map(([cat,v]) => {
        const pct = Math.round((v.done/v.total)*100);
        return `<div><div class="flex justify-between items-center mb-1"><span class="text-sm font-medium text-slate-700">${cat}</span><span class="text-xs text-slate-400">${v.done}/${v.total} (${pct}%)</span></div><div class="progress-bar"><div class="progress-fill bg-orange-400" style="width:${pct}%"></div></div></div>`;
    }).join('');
}

/* ── MAINT ── */
function getMaintFiltered() { return maintRaw.filter(m => matchesMonth(m.scheduled_date)); }

function renderMaintStats() {
    const data = getMaintFiltered();
    const done = data.filter(m => m.status==='done').length;
    const pct  = data.length ? Math.round((done/data.length)*100) : 0;
    document.getElementById('statMaint').textContent        = data.length;
    document.getElementById('statMaintDone').textContent    = done;
    document.getElementById('statMaintPeriode').textContent = getPeriodeLabel();
    document.getElementById('statMaintDonePct').textContent = data.length ? pct+'% selesai' : '-';
}

function renderMaintTable() {
    const data  = getMaintFiltered();
    const tbody = document.getElementById('maintTableBody');
    if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="8" class="px-5 py-8 text-center text-slate-400">Tidak ada data${document.getElementById('filterMonth').value?' untuk periode ini':''}.</td></tr>`;
        return;
    }
    tbody.innerHTML = data.map(item => {
        const st = STATUS_MAINT[item.status] ?? { label: item.status, cls: 'bg-slate-100 text-slate-500' };
        return `
        <tr class="border-b border-slate-50 last:border-0">
            <td class="px-5 py-3.5">
                <p class="font-semibold text-slate-700">${item.title ?? '-'}</p>
                ${item.note ? `<p class="text-xs text-slate-400">${item.note}</p>` : ''}
            </td>
            <td class="px-5 py-3.5 text-slate-500 text-xs">${item.category_name ?? '-'}</td>
            <td class="px-5 py-3.5 text-slate-500 text-xs">${item.worker_name ?? '<span class="text-slate-300">-</span>'}</td>
            <td class="px-5 py-3.5 text-xs text-slate-500">${PERIOD[item.period]??item.period??'-'}</td>
            <td class="px-5 py-3.5 text-xs text-slate-500">${formatDate(item.scheduled_date)}</td>
            <td class="px-5 py-3.5"><span class="status-badge ${st.cls}">${st.label}</span></td>
            <td class="px-5 py-3.5 text-slate-400 text-xs">${formatDate(item.completed_at)}</td>
            <td class="px-5 py-3.5 text-slate-400 text-xs">${item.spk_number ?? '-'}</td>
        </tr>`;
    }).join('');
}

function renderMaintChart() {
    const data = getMaintFiltered();
    const counts = {};
    data.forEach(m => { const l=STATUS_MAINT[m.status]?.label??m.status; counts[l]=(counts[l]??0)+1; });
    const labels=Object.keys(counts), values=Object.values(counts);
    const colors=['#f59e0b','#3b82f6','#a855f7','#22c55e'];
    if (maintChart) maintChart.destroy();
    if (!labels.length) return;
    maintChart = new Chart(document.getElementById('chartMaint'), {
        type:'bar',
        data:{ labels, datasets:[{ data:values, backgroundColor:colors, borderRadius:8, borderSkipped:false }] },
        options:{ responsive:true, maintainAspectRatio:true, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,ticks:{precision:0,font:{family:'Plus Jakarta Sans',size:11}},grid:{color:'#f1f5f9'}}, x:{grid:{display:false},ticks:{font:{family:'Plus Jakarta Sans',size:11}}} } }
    });
}

function renderMaintByCategory() {
    const data = getMaintFiltered();
    const cats = {};
    data.forEach(m => { const c=m.category_name??'Lainnya'; if(!cats[c])cats[c]={total:0,done:0}; cats[c].total++; if(m.status==='done')cats[c].done++; });
    const el = document.getElementById('maintByCategory');
    if (!Object.keys(cats).length) { el.innerHTML='<p class="text-xs text-slate-400 text-center py-4">Tidak ada data.</p>'; return; }
    el.innerHTML = Object.entries(cats).sort((a,b)=>b[1].total-a[1].total).map(([cat,v]) => {
        const pct = Math.round((v.done/v.total)*100);
        return `<div><div class="flex justify-between items-center mb-1"><span class="text-sm font-medium text-slate-700">${cat}</span><span class="text-xs text-slate-400">${v.done}/${v.total} (${pct}%)</span></div><div class="progress-bar"><div class="progress-fill bg-blue-400" style="width:${pct}%"></div></div></div>`;
    }).join('');
}

/* ════════════════════════════════════════
   EXPORT — format dokumen per pengajuan
════════════════════════════════════════ */
function exportRepair() {
    const data    = getRepairFiltered();
    const periode = getPeriodeLabel();
    const company = user.company_name ?? user.company ?? '';
    const color   = '#f97316';

    if (!data.length) { alert('Tidak ada data untuk diekspor.'); return; }

    const cards = data.map((r, i) => {
        const statusLabel = STATUS_REPAIR[r.status]?.label ?? r.status;
        const urgencyText = URGENCY_TEXT[r.urgency] ?? r.urgency ?? '-';

        // material dari array materials (setelah controller diupdate)
        let materialHtml = '<span style="color:#94a3b8">Tidak ada</span>';
        const mats = r.materials ?? [];
        if (mats.length > 0) {
            materialHtml = `<table style="width:100%;border-collapse:collapse;margin-top:4px;font-size:11px;">
                <thead><tr style="background:#fff7ed;">
                    <th style="padding:5px 8px;text-align:left;border:1px solid #fed7aa;color:#c2410c;">Item</th>
                    <th style="padding:5px 8px;text-align:center;border:1px solid #fed7aa;color:#c2410c;">Qty</th>
                    <th style="padding:5px 8px;text-align:left;border:1px solid #fed7aa;color:#c2410c;">Satuan</th>
                    <th style="padding:5px 8px;text-align:left;border:1px solid #fed7aa;color:#c2410c;">Status</th>
                </tr></thead>
                <tbody>${mats.map(m=>`<tr>
                    <td style="padding:5px 8px;border:1px solid #fed7aa;">${m.item_name??'-'}</td>
                    <td style="padding:5px 8px;border:1px solid #fed7aa;text-align:center;">${m.qty??'-'}</td>
                    <td style="padding:5px 8px;border:1px solid #fed7aa;">${m.unit??'-'}</td>
                    <td style="padding:5px 8px;border:1px solid #fed7aa;">${m.status??'-'}</td>
                </tr>`).join('')}</tbody>
            </table>`;
        } else if (r.material_used) {
            materialHtml = r.material_used;
        }

        return `
        <div class="doc-card" style="page-break-inside:avoid; margin-bottom:32px; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
            <!-- Header card -->
            <div style="background:${color}; padding:14px 20px; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div style="font-size:10px; color:rgba(255,255,255,0.8); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:3px;">Laporan Perbaikan #${i+1}</div>
                    <div style="font-size:16px; font-weight:700; color:white;">${r.title ?? '-'}</div>
                </div>
                <div style="background:rgba(255,255,255,0.2); border-radius:8px; padding:6px 12px; text-align:right;">
                    <div style="font-size:10px; color:rgba(255,255,255,0.8);">No. SPK</div>
                    <div style="font-size:12px; font-weight:700; color:white;">${r.spk_number ?? '-'}</div>
                </div>
            </div>

            <!-- Body -->
            <div style="padding:16px 20px;">
                <!-- Info grid -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:14px;">
                    <div class="info-item">
                        <div class="info-label">Cabang</div>
                        <div class="info-value">${r.branch ?? '-'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value" style="font-weight:700; color:${r.status==='done'?'#16a34a':r.status==='rejected'?'#dc2626':'#d97706'};">${statusLabel}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Kategori</div>
                        <div class="info-value">${r.category ?? '-'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Sub Kategori</div>
                        <div class="info-value">${r.sub_category && r.sub_category !== '-' ? r.sub_category : '-'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tukang Ditugaskan</div>
                        <div class="info-value">${r.technician_name ?? '-'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Urgensi</div>
                        <div class="info-value">${urgencyText}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Jadwal</div>
                        <div class="info-value">${formatDate(r.schedule_date)}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Penyelesaian</div>
                        <div class="info-value" style="font-weight:600; color:${r.completed_at?'#16a34a':'#94a3b8'};">${formatDate(r.completed_at)}</div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div style="background:#f8fafc; border-radius:8px; padding:10px 14px; margin-bottom:12px;">
                    <div style="font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Deskripsi Kerusakan</div>
                    <div style="font-size:12px; color:#334155; line-height:1.6;">${r.description ?? '-'}</div>
                </div>

                <!-- Material -->
                <div style="background:#fff7ed; border:1px solid #fed7aa; border-radius:8px; padding:10px 14px; margin-bottom:12px;">
                    <div style="font-size:10px; font-weight:700; color:#c2410c; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">Material Digunakan</div>
                    <div style="font-size:12px; color:#334155;">${materialHtml}</div>
                </div>

                <!-- Catatan selesai -->
                <div style="background:#f0fdf4; border:1px solid #86efac; border-radius:8px; padding:10px 14px;">
                    <div style="font-size:10px; font-weight:700; color:#15803d; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Catatan Penyelesaian</div>
                    <div style="font-size:12px; color:#334155; line-height:1.6;">${r.completion_note ?? '<span style="color:#94a3b8">Belum ada catatan</span>'}</div>
                </div>
            </div>
        </div>`;
    }).join('');

    printDocument('Laporan Perbaikan Gedung', periode, company, color, cards, data.length);
}

function exportMaint() {
    const data    = getMaintFiltered();
    const periode = getPeriodeLabel();
    const company = user.company_name ?? user.company ?? '';
    const color   = '#3b82f6';

    if (!data.length) { alert('Tidak ada data untuk diekspor.'); return; }

    const cards = data.map((m, i) => {
        const statusLabel = STATUS_MAINT[m.status]?.label ?? m.status;

        return `
        <div class="doc-card" style="page-break-inside:avoid; margin-bottom:32px; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
            <!-- Header card -->
            <div style="background:${color}; padding:14px 20px; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div style="font-size:10px; color:rgba(255,255,255,0.8); font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:3px;">Laporan Maintenance #${i+1}</div>
                    <div style="font-size:16px; font-weight:700; color:white;">${m.title ?? '-'}</div>
                </div>
                <div style="background:rgba(255,255,255,0.2); border-radius:8px; padding:6px 12px; text-align:right;">
                    <div style="font-size:10px; color:rgba(255,255,255,0.8);">No. SPK</div>
                    <div style="font-size:12px; font-weight:700; color:white;">${m.spk_number ?? '-'}</div>
                </div>
            </div>

            <!-- Body -->
            <div style="padding:16px 20px;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:14px;">
                    <div class="info-item">
                        <div class="info-label">Kategori</div>
                        <div class="info-value">${m.category_name ?? '-'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Sub Kategori</div>
                        <div class="info-value">${m.sub_category_name ?? '-'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tukang Ditugaskan</div>
                        <div class="info-value">${m.worker_name ?? '-'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Dibuat Oleh</div>
                        <div class="info-value">${m.created_by_name ?? '-'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Periode</div>
                        <div class="info-value">${PERIOD[m.period]??m.period??'-'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value" style="font-weight:700; color:${m.status==='done'?'#16a34a':m.status==='in_progress'?'#7c3aed':'#d97706'};">${statusLabel}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Jadwal</div>
                        <div class="info-value">${formatDate(m.scheduled_date)}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Penyelesaian</div>
                        <div class="info-value" style="font-weight:600; color:${m.completed_at?'#16a34a':'#94a3b8'};">${formatDate(m.completed_at)}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Konfirmasi Tukang</div>
                        <div class="info-value">${formatDate(m.worker_confirmed_at)}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">SPK Dikirim</div>
                        <div class="info-value">${formatDate(m.spk_sent_at)}</div>
                    </div>
                </div>

                <!-- Catatan -->
                ${m.note ? `
                <div style="background:#f8fafc; border-radius:8px; padding:10px 14px; margin-bottom:12px;">
                    <div style="font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Catatan Tugas</div>
                    <div style="font-size:12px; color:#334155; line-height:1.6;">${m.note}</div>
                </div>` : ''}

                <!-- Catatan selesai -->
                <div style="background:#f0fdf4; border:1px solid #86efac; border-radius:8px; padding:10px 14px;">
                    <div style="font-size:10px; font-weight:700; color:#15803d; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Catatan Penyelesaian</div>
                    <div style="font-size:12px; color:#334155; line-height:1.6;">${m.completion_note ?? '<span style="color:#94a3b8">Belum ada catatan</span>'}</div>
                </div>
            </div>
        </div>`;
    }).join('');

    printDocument('Laporan Maintenance Terjadwal', periode, company, color, cards, data.length);
}

function printDocument(title, periode, company, color, cardsHtml, total) {
    const win = window.open('', '_blank');
    win.document.write(`<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<title>${title}</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
  * { box-sizing:border-box; margin:0; padding:0; }
  body { font-family:'Plus Jakarta Sans',sans-serif; background:white; color:#1e293b; padding:32px 28px; }

  .doc-header { border-bottom:3px solid ${color}; padding-bottom:16px; margin-bottom:28px; display:flex; justify-content:space-between; align-items:flex-start; }
  .doc-header-left h1 { font-size:20px; font-weight:800; color:${color}; margin-bottom:4px; }
  .doc-header-left p  { font-size:12px; color:#64748b; }
  .doc-header-right   { text-align:right; }
  .doc-header-right strong { display:block; font-size:15px; font-weight:700; color:#1e293b; }
  .doc-header-right span  { font-size:11px; color:#64748b; }

  .doc-summary { display:flex; gap:12px; margin-bottom:28px; }
  .doc-summary-item { flex:1; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px; text-align:center; }
  .doc-summary-item .num { font-size:24px; font-weight:800; color:${color}; }
  .doc-summary-item .lbl { font-size:10px; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-top:2px; }

  .info-item { background:#f8fafc; border-radius:6px; padding:8px 12px; }
  .info-label { font-size:10px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:3px; }
  .info-value { font-size:13px; color:#1e293b; font-weight:500; }

  .doc-footer { margin-top:24px; padding-top:12px; border-top:1px solid #f1f5f9; display:flex; justify-content:space-between; font-size:10px; color:#94a3b8; }

  @media print {
    body { padding:16px 14px; }
    @page { margin:0.8cm; size:A4 portrait; }
    .doc-card { page-break-inside:avoid; }
  }
</style>
</head><body>

<div class="doc-header">
  <div class="doc-header-left">
    <h1>${title}</h1>
    <p>Periode: ${periode} &nbsp;·&nbsp; Total: ${total} pengajuan</p>
  </div>
  <div class="doc-header-right">
    ${company ? `<strong>${company}</strong>` : ''}
    <span>Dicetak: ${new Date().toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'})}</span>
  </div>
</div>

${cardsHtml}

<div class="doc-footer">
  <span>Dokumen ini digenerate otomatis dari sistem.</span>
  <span>${user.name ?? ''} · Management</span>
</div>

</body></html>`);
    win.document.close();
    setTimeout(() => win.print(), 700);
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