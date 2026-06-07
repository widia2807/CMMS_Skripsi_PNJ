<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Technician</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f1f5f9; }
        .stat-card {
            background: white; border-radius: 16px; padding: 20px 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .card { background: white; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .icon-box { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .table-row { transition: background 0.1s; }
        .table-row:hover td { background: #f8fafc; }
        .job-badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }
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
            <h1 class="font-bold text-slate-800 text-lg">Dashboard Teknisi</h1>
                <p class="text-xs text-slate-400 mt-0.5">Ringkasan pekerjaan dan tugas aktif Anda</p>
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

        <!-- WELCOME BANNER -->
        <div class="bg-slate-800 rounded-2xl p-6 mb-8 flex items-center justify-between overflow-hidden relative">
            <div class="absolute right-0 top-0 w-64 h-full opacity-5">
                <svg viewBox="0 0 200 200" class="w-full h-full">
                    <circle cx="150" cy="50" r="80" fill="white"/>
                    <circle cx="50" cy="150" r="60" fill="white"/>
                </svg>
            </div>
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Selamat datang kembali 👋</p>
                <h2 class="text-white text-xl font-bold" id="welcomeName">Teknisi</h2>
                <p class="text-slate-400 text-sm mt-1">Kerjakan tugas yang diberikan dan selalu update status pekerjaan.</p>
            </div>
            <div class="hidden md:flex">
                <div class="bg-white/10 rounded-xl px-4 py-3 text-center">
                    <p class="text-white/60 text-xs">Hari ini</p>
                    <p class="text-white font-bold text-lg" id="todayDate">-</p>
                </div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Pekerjaan Masuk</p>
                    <div class="icon-box bg-blue-50"><i data-feather="briefcase" class="w-4 h-4 text-blue-500"></i></div>
                </div>
                <p id="incomingJob" class="text-3xl font-bold text-blue-500">0</p>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Selesai</p>
                    <div class="icon-box bg-green-50"><i data-feather="check-circle" class="w-4 h-4 text-green-500"></i></div>
                </div>
                <p id="completedJob" class="text-3xl font-bold text-green-500">0</p>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Sedang Dikerjakan</p>
                    <div class="icon-box bg-purple-50"><i data-feather="zap" class="w-4 h-4 text-purple-500"></i></div>
                </div>
                <p id="onProgressJob" class="text-3xl font-bold text-purple-500">0</p>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Maintenance</p>
                    <div class="icon-box bg-cyan-50"><i data-feather="calendar" class="w-4 h-4 text-cyan-500"></i></div>
                </div>
                <p id="scheduledJob" class="text-3xl font-bold text-cyan-500">0</p>
            </div>
        </div>

        <!-- BOTTOM SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- PEKERJAAN TERBARU — tanpa tombol lihat semua -->
            <div class="card p-6 lg:col-span-2">
                <div class="mb-5">
                    <h2 class="font-bold text-slate-800 text-base">Pekerjaan Terbaru</h2>
                    <p class="text-xs text-slate-400 mt-0.5">5 pekerjaan yang baru ditugaskan</p>
                </div>
                <div class="rounded-xl border border-slate-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Pekerjaan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Lokasi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                            </tr>
                        </thead>
                        <tbody id="jobTable">
                            <tr><td colspan="3" class="px-4 py-8 text-center text-slate-400 text-sm">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- REMINDER saja — tanpa aksi cepat -->
            <div class="card p-6 self-start">
                <div class="flex items-start gap-3">
                    <div class="icon-box bg-amber-50 shrink-0 mt-0.5">
                        <i data-feather="info" class="w-4 h-4 text-amber-500"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 text-sm mb-1">Reminder</p>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Selalu update status pekerjaan agar admin dapat memantau progress secara real-time.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

<script>
function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
}
const token = getCookie('token');
const user  = JSON.parse(getCookie('user') || '{}');
if (!token || !user) { window.location.href = '/login'; }
document.getElementById('userInfo').innerText    = (user.name ?? 'Teknisi') + ' · Teknisi';
document.getElementById('welcomeName').innerText = user.name ?? 'Teknisi';
document.getElementById('userInitial').innerText = (user.name ?? 'T').split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase();
document.getElementById('todayDate').innerText   = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });

const STATUS_LABEL = {
    approved: 'Disetujui', scheduled: 'Terjadwal',
    waiting_material: 'Menunggu Material', on_progress: 'Dikerjakan',
    done: 'Selesai', material_ready: 'Material Siap', verified: 'Terverifikasi'
};
const STATUS_STYLE = {
    approved:         'background:#f0fdf4;color:#16a34a',
    scheduled:        'background:#eff6ff;color:#2563eb',
    waiting_material: 'background:#fffbeb;color:#d97706',
    material_ready:   'background:#ecfeff;color:#0891b2',
    on_progress:      'background:#f5f3ff;color:#7c3aed',
    done:             'background:#f8fafc;color:#64748b',
    verified:         'background:#f0fdfa;color:#0d9488',
};

async function loadDashboard() {
    try {
        const res  = await fetch('/api/dashboard-technician', { headers: { 'Authorization': 'Bearer ' + token } });
        const data = await res.json();
        if (!res.ok) { if (res.status === 401) { localStorage.clear(); window.location.href = '/login'; } return; }
        document.getElementById('incomingJob').innerText  = data.incoming_jobs  ?? 0;
        document.getElementById('completedJob').innerText = data.completed_jobs ?? 0;
    } catch (err) { console.error(err); }
}

async function loadJobs() {
    try {
        const [jobRes, schedRes] = await Promise.all([
            fetch('/api/technician/jobs',                  { headers: { 'Authorization': 'Bearer ' + token } }),
            fetch('/api/scheduled-maintenances/my-tasks',  { headers: { 'Authorization': 'Bearer ' + token } }),
        ]);

        const jobs     = jobRes.ok   ? (await jobRes.json())   : [];
        const scheds   = schedRes.ok ? (await schedRes.json()) : [];
        const schedArr = Array.isArray(scheds) ? scheds : (scheds.data ?? []);

        document.getElementById('onProgressJob').innerText = jobs.filter(j => j.status === 'on_progress').length;
        document.getElementById('scheduledJob').innerText  = schedArr.length;

        const tbody = document.getElementById('jobTable');
        if (!jobs.length) {
            tbody.innerHTML = `<tr><td colspan="3" class="px-4 py-8 text-center text-slate-400 text-sm">Belum ada pekerjaan.</td></tr>`;
            return;
        }
        tbody.innerHTML = jobs.slice(0, 5).map(job => `
            <tr class="table-row border-b border-slate-50 last:border-0">
                <td class="px-4 py-3">
                    <p class="font-semibold text-slate-700 text-sm">${job.title ?? '-'}</p>
                    <p class="text-xs text-slate-400">${job.category ?? '-'}</p>
                </td>
                <td class="px-4 py-3 text-slate-500 text-sm">${job.branch ?? '-'}</td>
                <td class="px-4 py-3">
                    <span class="job-badge" style="${STATUS_STYLE[job.status] ?? 'background:#f8fafc;color:#64748b'}">
                        ${STATUS_LABEL[job.status] ?? job.status}
                    </span>
                </td>
            </tr>
        `).join('');
        feather.replace();
    } catch (err) { console.error(err); }
}

function goTo(url) { window.location.href = url; }

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadDashboard();
    loadJobs();
});
</script>

</body>
</html>