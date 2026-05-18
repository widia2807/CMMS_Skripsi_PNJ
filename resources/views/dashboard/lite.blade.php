<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin GA</title>
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
        <div class="bg-white border-b border-slate-100 px-8 py-4 flex justify-between items-center sticky top-0 z-30">
            <div>
                <h1 class="font-bold text-slate-800 text-lg">Dashboard Admin GA</h1>
                <p class="text-xs text-slate-400 mt-0.5">General Affairs Management System</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-slate-600">
                    <i data-feather="menu" class="w-5 h-5"></i>
                </button>
                <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
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
                    <h2 class="text-white text-xl font-bold" id="welcomeName">Admin GA</h2>
                    <p class="text-slate-400 text-sm mt-1">Kelola user, lakukan approval, dan pantau aktivitas sistem.</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <div class="bg-white/10 rounded-xl px-4 py-3 text-center">
                        <p class="text-white/60 text-xs">Hari ini</p>
                        <p class="text-white font-bold text-lg" id="todayDate">-</p>
                    </div>
                </div>
            </div>

            <!-- STAT CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total User</p>
                        <div class="icon-box bg-slate-100">
                            <i data-feather="users" class="w-4 h-4 text-slate-500"></i>
                        </div>
                    </div>
                    <p id="totalUser" class="text-3xl font-bold text-slate-800">0</p>
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

            <!-- INFO BOX -->
            <div class="card p-6">
                <div class="flex items-start gap-4">
                    <div class="icon-box bg-blue-50 shrink-0 mt-0.5">
                        <i data-feather="info" class="w-4 h-4 text-blue-500"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-base mb-1">Panduan Admin GA</h2>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Anda dapat mengelola user, melakukan approval, dan memantau aktivitas sistem di sini.
                            Gunakan menu di sidebar untuk navigasi ke halaman lainnya.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
const token = localStorage.getItem('token');
const user  = JSON.parse(localStorage.getItem('user') || '{}');

if (!token || !user) { localStorage.clear(); window.location.href = '/login'; }

document.getElementById('userInfo').innerText    = (user.name ?? 'Admin') + ' · ' + (user.role ?? '');
document.getElementById('welcomeName').innerText = user.name ?? 'Admin GA';
document.getElementById('userInitial').innerText = (user.name ?? 'A').split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase();
document.getElementById('todayDate').innerText   = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });

function toggleSidebar() {
    document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
    document.getElementById('overlay')?.classList.toggle('hidden');
}

function goTo(url) { window.location.href = url; }

function goToDashboard() {
    const u = JSON.parse(localStorage.getItem('user') || '{}');
    if (u?.role === 'pic') window.location.href = '/dashboard-pic';
    else if (u?.system_type === 'lite') window.location.href = '/dashboard-lite';
    else window.location.href = '/dashboard-full';
}

function logout() {
    localStorage.clear();
    window.location.href = '/login';
}

async function loadLiteDashboard() {
    try {
        const res  = await fetch('/api/lite/dashboard', {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (!res.ok) { console.error(data); return; }

        document.getElementById('totalUser').innerText   = data.total   ?? 0;
        document.getElementById('pendingUser').innerText = data.pending  ?? 0;
        document.getElementById('activeUser').innerText  = data.active   ?? 0;
    } catch (err) { console.error('Error:', err); }
}

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadLiteDashboard();
});
</script>

</body>
</html>