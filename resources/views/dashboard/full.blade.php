<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body { background: #f1f5f9; }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        .card {
            background: white;
            border-radius: 20px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .icon-box {
            width: 40px; height: 40px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }

        .btn-primary {
            background: #1e293b;
            color: white;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary:hover { background: #334155; transform: translateY(-1px); }

        .btn-blue {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-blue:hover { background: #dbeafe; }

        .btn-green {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-green:hover { background: #dcfce7; }

        .table-row { transition: background 0.1s; }
        .table-row:hover td { background: #f8fafc; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>
</head>

<body>

@include('components.sidebar')

<div class="flex min-h-screen">

    <div id="overlay"
        class="fixed inset-0 bg-black opacity-40 hidden md:hidden z-20"
        onclick="toggleSidebar()">
    </div>

    <div class="flex-1 md:ml-64">
<!-- TOPBAR -->
<div class="bg-white border-b border-slate-100 px-4 md:px-8 py-4 flex justify-between items-center sticky top-0 z-30">
    <div class="flex items-center gap-3">
        <!-- Hamburger mobile -->
        <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-400">
            <i data-feather="menu" class="w-5 h-5"></i>
        </button>
        <div>
            <h1 class="font-bold text-slate-800 text-lg">Dashboard</h1>
            <p class="text-xs text-slate-400 mt-0.5">CMMS — General Affairs Management System</p>
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
        <!-- TOPBAR -->
        <div class="bg-white border-b border-slate-100 px-8 py-4 flex justify-between items-center sticky top-0 z-30">
            <div>
                <h1 class="font-bold text-slate-800 text-lg">Dashboard</h1>
                <p class="text-xs text-slate-400 mt-0.5">CMMS — General Affairs Management System</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-slate-600">
                    <i data-feather="menu" class="w-5 h-5"></i>
                </button>
                <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                    <div class="w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center">
                        <span class="text-white font-bold text-xs">SA</span>
                    </div>
                    <span class="text-sm font-medium text-slate-600">Super Admin</span>
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
                    <h2 class="text-white text-xl font-bold">Super Admin</h2>
                    <p class="text-slate-400 text-sm mt-1">Kelola cabang, user, dan sistem CMMS Anda di sini.</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
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
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Cabang</p>
                        <div class="icon-box bg-slate-100">
                            <i data-feather="git-branch" class="w-4 h-4 text-slate-500"></i>
                        </div>
                    </div>
                    <p id="totalCabang" class="text-3xl font-bold text-slate-800">0</p>
                </div>

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total User</p>
                        <div class="icon-box bg-blue-50">
                            <i data-feather="users" class="w-4 h-4 text-blue-500"></i>
                        </div>
                    </div>
                    <p id="totalUser" class="text-3xl font-bold text-blue-500">0</p>
                </div>

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">User Pending</p>
                        <div class="icon-box bg-amber-50">
                            <i data-feather="clock" class="w-4 h-4 text-amber-500"></i>
                        </div>
                    </div>
                    <p id="pendingUser" class="text-3xl font-bold text-amber-500">0</p>
                </div>

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">User Aktif</p>
                        <div class="icon-box bg-green-50">
                            <i data-feather="user-check" class="w-4 h-4 text-green-500"></i>
                        </div>
                    </div>
                    <p id="activeUser" class="text-3xl font-bold text-green-500">0</p>
                </div>

            </div>

            <!-- ALERT PENDING -->
            <div id="pendingAlert" class="hidden mb-6 bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="icon-box bg-amber-100 w-8 h-8 rounded-lg">
                        <i data-feather="alert-triangle" class="w-4 h-4 text-amber-500"></i>
                    </div>
                    <p class="text-sm font-semibold text-amber-700" id="pendingAlertText">User menunggu approval</p>
                </div>
                <a href="/users" class="btn-primary bg-amber-500 hover:bg-amber-600 text-white text-xs">
                    <i data-feather="arrow-right" class="w-3.5 h-3.5"></i> Lihat
                </a>
            </div>

            <!-- BOTTOM SECTION -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- RECENT ACTIVITY -->
                <div class="card p-6 lg:col-span-2">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="font-bold text-slate-800 text-base">Recent Activity</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Aktivitas terbaru dalam sistem</p>
                        </div>
                    </div>
                    <div class="rounded-xl border border-slate-100 overflow-hidden">
                        <ul id="recentActivity" class="divide-y divide-slate-50">
                            <li class="px-4 py-3 text-sm text-slate-400 text-center">Memuat aktivitas...</li>
                        </ul>
                    </div>
                </div>

                <!-- QUICK ACTIONS -->
                <div class="space-y-4">
                    <div class="card p-6">
                        <h2 class="font-bold text-slate-800 text-base mb-4">Aksi Cepat</h2>
                        <div class="space-y-2">
                            <a href="/branches" class="btn-blue w-full justify-start">
                                <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                    <i data-feather="git-branch" class="w-3.5 h-3.5 text-blue-600"></i>
                                </div>
                                Tambah Cabang
                            </a>
                            <a href="/users" class="btn-green w-full justify-start">
                                <div class="w-7 h-7 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
                                    <i data-feather="user-plus" class="w-3.5 h-3.5 text-green-600"></i>
                                </div>
                                Tambah User
                            </a>
                        </div>
                    </div>

                    <div class="card p-6">
                        <div class="flex items-start gap-3">
                            <div class="icon-box bg-blue-50 shrink-0 mt-0.5">
                                <i data-feather="info" class="w-4 h-4 text-blue-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-700 text-sm mb-1">Info Sistem</p>
                                <p class="text-xs text-slate-400 leading-relaxed">
                                    Kelola cabang, user, dan pantau seluruh aktivitas CMMS melalui menu di sidebar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
const token = localStorage.getItem('token');
if (!token) { localStorage.clear(); window.location.href = '/login'; }

document.getElementById('todayDate').innerText = new Date().toLocaleDateString('id-ID', {
    day: 'numeric', month: 'short', year: 'numeric'
});

function toggleSidebar() {
    document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
    document.getElementById('overlay')?.classList.toggle('hidden');
}

function goTo(url) { window.location.href = url; }

function goToDashboard() {
    const u = JSON.parse(localStorage.getItem('user') || '{}');
    if (u?.system_type === 'lite') window.location.href = '/dashboard-lite';
    else window.location.href = '/dashboard-full';
}

function logout() {
    localStorage.clear();
    window.location.href = '/login';
}

async function loadDashboard() {
    try {
        const res  = await fetch('/api/dashboard', {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (!res.ok) { console.error(data); return; }

        const pending = data.pending_user ?? 0;

        document.getElementById('totalCabang').innerText = data.total_cabang ?? 0;
        document.getElementById('totalUser').innerText   = data.total_user   ?? 0;
        document.getElementById('pendingUser').innerText = pending;
        document.getElementById('activeUser').innerText  = data.active_user  ?? 0;

        if (pending > 0) {
            document.getElementById('pendingAlertText').innerText = `${pending} user menunggu approval`;
            document.getElementById('pendingAlert').classList.remove('hidden');
            document.getElementById('pendingAlert').classList.add('flex');
        }
    } catch (err) { console.error('Gagal load dashboard:', err); }
}

async function loadActivity() {
    try {
        const res  = await fetch('/api/dashboard/activity', {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (!Array.isArray(data)) return;

        const container = document.getElementById('recentActivity');
        if (!data.length) {
            container.innerHTML = `<li class="px-4 py-3 text-sm text-slate-400 text-center">Belum ada aktivitas.</li>`;
            return;
        }
        container.innerHTML = data.map(item => `
            <li class="px-4 py-3 flex items-center gap-3 hover:bg-slate-50 transition-colors">
                <div class="w-6 h-6 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                    <i data-feather="check" class="w-3 h-3 text-green-500"></i>
                </div>
                <span class="text-sm text-slate-600">${item.description}</span>
            </li>
        `).join('');
        feather.replace();
    } catch (err) { console.error(err); }
}

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadDashboard();
    loadActivity();
});
</script>

</body>
</html>