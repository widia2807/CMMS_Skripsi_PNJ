<!DOCTYPE html>
<html>
<head>
    <title>Dashboard PIC</title>
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
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
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
            flex-shrink: 0;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f8fafc;
            transition: background 0.15s;
        }
        .activity-item:last-child { border-bottom: none; }

        .activity-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #6366f1;
            margin-top: 5px;
            flex-shrink: 0;
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
            cursor: pointer;
        }
        .btn-primary:hover { background: #334155; transform: translateY(-1px); }

        .close-btn {
            width: 28px; height: 28px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #94a3b8;
            transition: all 0.15s;
            cursor: pointer;
            flex-shrink: 0;
        }
        .close-btn:hover { background: #fee2e2; border-color: #fecaca; color: #ef4444; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

        .fade-in { animation: fadeIn 0.3s ease; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

@include('components.sidebar')

<div class="flex min-h-screen">

    <!-- OVERLAY mobile -->
    <div id="overlay"
         class="fixed inset-0 bg-black opacity-40 hidden md:hidden z-20"
         onclick="toggleSidebar()">
    </div>

    <!-- MAIN -->
    <div class="flex-1 md:ml-64">

        <!-- TOPBAR -->
        <div class="bg-white border-b border-slate-100 px-6 md:px-8 py-4 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500">
                    <i data-feather="menu" class="w-5 h-5"></i>
                </button>
                <div>
                    <h1 class="font-bold text-slate-800 text-lg">Dashboard PIC</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Person In Charge</p>
                </div>
            </div>
            <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center">
                    <span id="userInitial" class="text-white font-bold text-xs"></span>
                </div>
                <span id="userInfo" class="text-sm font-medium text-slate-600 hidden sm:block"></span>
            </div>
        </div>

        <div class="p-6 md:p-8">

            <!-- WELCOME BANNER -->
            <div id="welcomeCard" class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 mb-8 flex items-center justify-between overflow-hidden relative fade-in">
                <div class="absolute right-0 top-0 w-48 h-full opacity-10">
                    <svg viewBox="0 0 200 200" class="w-full h-full"><circle cx="160" cy="40" r="90" fill="white"/><circle cx="40" cy="160" r="70" fill="white"/></svg>
                </div>
                <div>
                    <p class="text-indigo-200 text-sm font-medium mb-1">Selamat datang 👋</p>
                    <h2 class="text-white text-xl font-bold" id="welcomeName">PIC</h2>
                    <p class="text-indigo-200 text-sm mt-1">Ajukan perbaikan dan pantau pekerjaan Anda di sini.</p>
                </div>
                <button onclick="closeWelcome()" class="absolute top-4 right-4 w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                    <i data-feather="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- STAT CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

                <div class="stat-card fade-in">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Pengajuan</p>
                        <div class="icon-box bg-slate-100">
                            <i data-feather="file-text" class="w-4 h-4 text-slate-500"></i>
                        </div>
                    </div>
                    <p id="totalPengajuan" class="text-3xl font-bold text-slate-800">0</p>
                    <p class="text-xs text-slate-400 mt-1">semua status</p>
                </div>

                <div class="stat-card fade-in" style="animation-delay: 0.05s">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Sedang Berjalan</p>
                        <div class="icon-box bg-blue-50">
                            <i data-feather="loader" class="w-4 h-4 text-blue-500"></i>
                        </div>
                    </div>
                    <p id="onProgress" class="text-3xl font-bold text-blue-600">0</p>
                    <p class="text-xs text-slate-400 mt-1">in progress</p>
                </div>

                <div class="stat-card fade-in" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Selesai</p>
                        <div class="icon-box bg-green-50">
                            <i data-feather="check-circle" class="w-4 h-4 text-green-500"></i>
                        </div>
                    </div>
                    <p id="done" class="text-3xl font-bold text-green-600">0</p>
                    <p class="text-xs text-slate-400 mt-1">pekerjaan selesai</p>
                </div>

            </div>

            <!-- AKTIVITAS TERBARU -->
            <div id="activityCard" class="card p-6 fade-in">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="font-bold text-slate-800">Aktivitas Terbaru</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Riwayat pengajuan dan pembaruan status</p>
                    </div>
                    <button onclick="closeActivity()" class="close-btn">
                        <i data-feather="x" class="w-3.5 h-3.5"></i>
                    </button>
                </div>

                <!-- Loading state -->
                <div id="activityLoading" class="py-8 text-center">
                    <div class="w-8 h-8 border-2 border-indigo-200 border-t-indigo-500 rounded-full animate-spin mx-auto mb-3"></div>
                    <p class="text-slate-400 text-sm">Memuat aktivitas...</p>
                </div>

                <!-- List -->
                <ul id="recentActivity" class="hidden divide-y divide-slate-50"></ul>

                <!-- Empty state -->
                <div id="activityEmpty" class="hidden py-10 text-center">
                    <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i data-feather="inbox" class="w-5 h-5 text-slate-400"></i>
                    </div>
                    <p class="text-slate-400 text-sm">Belum ada aktivitas terbaru</p>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
const token = localStorage.getItem('token');
const user  = JSON.parse(localStorage.getItem('user'));

if (!user || !token) { localStorage.clear(); window.location.href = '/login'; }

// User info
document.getElementById('userInfo').innerText   = user.name + ' · ' + user.role;
document.getElementById('welcomeName').innerText = user.name;
document.getElementById('userInitial').innerText = user.name?.split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase();

// ─── DASHBOARD ───────────────────────────────────────────
async function loadDashboard() {
    try {
        const res  = await fetch('/api/pic/dashboard', { headers: { Authorization: 'Bearer ' + token } });
        const data = await res.json();
        if (!res.ok) { console.error('API Error:', data.message); return; }

        document.getElementById('totalPengajuan').innerText = data.total    ?? 0;
        document.getElementById('onProgress').innerText     = data.progress ?? 0;
        document.getElementById('done').innerText           = data.done     ?? 0;
    } catch (err) { console.error(err); }
}

// ─── ACTIVITY ────────────────────────────────────────────
async function loadActivity() {
    try {
        const res  = await fetch('/api/pic/activity', { headers: { Authorization: 'Bearer ' + token } });
        const data = await res.json();

        document.getElementById('activityLoading').classList.add('hidden');

        if (!res.ok || !Array.isArray(data) || !data.length) {
            document.getElementById('activityEmpty').classList.remove('hidden');
            return;
        }

        const container = document.getElementById('recentActivity');
        container.classList.remove('hidden');
        container.innerHTML = data.map(item => `
            <li class="activity-item py-3">
                <div class="activity-dot mt-1.5"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-slate-700">${item.description}</p>
                    ${item.created_at
                        ? `<p class="text-xs text-slate-400 mt-0.5">${new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>`
                        : ''}
                </div>
            </li>
        `).join('');

        feather.replace();
    } catch (err) {
        document.getElementById('activityLoading').classList.add('hidden');
        document.getElementById('activityEmpty').classList.remove('hidden');
        console.error(err);
    }
}

// ─── UI HELPERS ──────────────────────────────────────────
function closeWelcome() {
    document.getElementById('welcomeCard').style.display = 'none';
}

function closeActivity() {
    document.getElementById('activityCard').style.display = 'none';
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

function goTo(url) { window.location.href = url; }
function logout()  { localStorage.clear(); window.location.href = '/login'; }

// ─── INIT ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadDashboard();
    loadActivity();
});
</script>

</body>
</html>