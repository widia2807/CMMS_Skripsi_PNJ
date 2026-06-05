<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Management</title>
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
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }

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

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .tab-btn { transition: all 0.15s; }
        .tab-active { background: #1e293b; color: white; }
        .tab-inactive { background: white; color: #64748b; border: 1px solid #e2e8f0; }

        tr { transition: background 0.1s; }
        tr:hover td { background: #f8fafc; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

        .spk-sent { background: #f0fdf4; border: 1px solid #86efac; color: #15803d; }
        .spk-pending { background: #fefce8; border: 1px solid #fde047; color: #854d0e; }
        .spk-none { background: #f8fafc; border: 1px solid #e2e8f0; color: #94a3b8; }

        .expand-row { display: none; }
        .expand-row.open { display: table-row; }

        .lihat-semua-section { display: none; }
        .lihat-semua-section.open { display: block; }
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
                <h1 class="font-bold text-slate-800 text-lg">Dashboard</h1>
                <p class="text-xs text-slate-400 mt-0.5">Monitoring seluruh pekerjaan & aset</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                <div class="w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center">
                    <span id="userInitial" class="text-white font-bold text-xs"></span>
                </div>
                <span id="userInfo" class="text-sm font-medium text-slate-600"></span>
            </div>
        </div>
    </div>

    <div class="p-6 md:p-8 space-y-6">

        <!-- WELCOME BANNER -->
        <div class="bg-slate-800 rounded-2xl p-6 flex items-center justify-between overflow-hidden relative">
            <div class="absolute right-0 top-0 w-64 h-full opacity-5">
                <svg viewBox="0 0 200 200" class="w-full h-full">
                    <circle cx="150" cy="50" r="80" fill="white"/>
                    <circle cx="50" cy="150" r="60" fill="white"/>
                </svg>
            </div>
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Selamat datang 👋</p>
                <h2 class="text-white text-xl font-bold" id="welcomeName">Management</h2>
                <p class="text-slate-400 text-sm mt-1">Pantau seluruh aktivitas pekerjaan, aset, dan laporan di sini.</p>
            </div>
            <div class="hidden md:flex items-center gap-2">
                <div class="bg-white/10 rounded-xl px-4 py-3 text-center">
                    <p class="text-white/60 text-xs">Hari ini</p>
                    <p class="text-white font-bold text-lg" id="todayDate">-</p>
                </div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Pekerjaan</p>
                    <div class="icon-box bg-slate-100"><i data-feather="briefcase" class="w-4 h-4 text-slate-500"></i></div>
                </div>
                <p id="statTotal" class="text-3xl font-bold text-slate-800">0</p>
                <p class="text-xs text-slate-400 mt-1">Perbaikan + Maintenance</p>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Sedang Berjalan</p>
                    <div class="icon-box bg-blue-50"><i data-feather="activity" class="w-4 h-4 text-blue-500"></i></div>
                </div>
                <p id="statOngoing" class="text-3xl font-bold text-blue-500">0</p>
                <p class="text-xs text-slate-400 mt-1">Aktif dikerjakan</p>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Selesai Bulan Ini</p>
                    <div class="icon-box bg-green-50"><i data-feather="check-circle" class="w-4 h-4 text-green-500"></i></div>
                </div>
                <p id="statDone" class="text-3xl font-bold text-green-500">0</p>
                <p class="text-xs text-slate-400 mt-1">Terselesaikan</p>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Butuh Material</p>
                    <div class="icon-box bg-amber-50"><i data-feather="package" class="w-4 h-4 text-amber-500"></i></div>
                </div>
                <p id="statMaterial" class="text-3xl font-bold text-amber-500">0</p>
                <p class="text-xs text-slate-400 mt-1">Menunggu material</p>
            </div>
        </div>

        <!-- PERBAIKAN GEDUNG -->
        <div class="card">
            <div class="px-6 py-5 border-b border-slate-100 flex flex-wrap justify-between items-center gap-3">
                <div class="flex items-center gap-3">
                    <div class="icon-box bg-orange-100">
                        <i data-feather="tool" class="w-4 h-4 text-orange-600"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800">Perbaikan Gedung</h2>
                        <p class="text-xs text-slate-400">Menampilkan maks. 10 pekerjaan aktif & terbaru</p>
                    </div>
                </div>
            </div>

            <!-- Tabel preview 10 data -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Pekerjaan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Lokasi</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Tukang</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Urgensi</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">SPK</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Material</th>
                        </tr>
                    </thead>
                    <tbody id="repairTable">
                        <tr><td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer tombol Lihat Semua -->
            <div id="repairFooter" class="px-6 py-4 border-t border-slate-100 hidden">
                <button onclick="toggleRepairAll()" id="repairToggleBtn"
                    class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">
                    <i data-feather="chevron-down" class="w-4 h-4" id="repairChevron"></i>
                    <span id="repairToggleLabel">Lihat semua pekerjaan</span>
                </button>
            </div>

            <!-- Section Lihat Semua (tersembunyi dulu) -->
            <div id="repairAllSection" class="lihat-semua-section border-t border-slate-100">
                <div class="px-6 py-4 flex flex-wrap gap-2 items-center bg-slate-50 border-b border-slate-100">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide mr-1">Filter:</span>
                    <button onclick="filterRepairAll('all')" id="ra-all" class="tab-btn tab-active text-xs px-3 py-1.5 rounded-lg font-semibold">Semua</button>
                    <button onclick="filterRepairAll('this_month')" id="ra-this_month" class="tab-btn tab-inactive text-xs px-3 py-1.5 rounded-lg font-semibold">Bulan ini</button>
                    <button onclick="filterRepairAll('pending')" id="ra-pending" class="tab-btn tab-inactive text-xs px-3 py-1.5 rounded-lg font-semibold">Pending</button>
                    <button onclick="filterRepairAll('approved')" id="ra-approved" class="tab-btn tab-inactive text-xs px-3 py-1.5 rounded-lg font-semibold">Disetujui</button>
                    <button onclick="filterRepairAll('on_progress')" id="ra-on_progress" class="tab-btn tab-inactive text-xs px-3 py-1.5 rounded-lg font-semibold">Dikerjakan</button>
                    <button onclick="filterRepairAll('done')" id="ra-done" class="tab-btn tab-inactive text-xs px-3 py-1.5 rounded-lg font-semibold">Selesai</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Pekerjaan</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Lokasi</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Tukang</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Urgensi</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">SPK</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Material</th>
                            </tr>
                        </thead>
                        <tbody id="repairAllTable">
                            <tr><td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MAINTENANCE TERJADWAL -->
        <div class="card">
            <div class="px-6 py-5 border-b border-slate-100 flex flex-wrap justify-between items-center gap-3">
                <div class="flex items-center gap-3">
                    <div class="icon-box bg-blue-100">
                        <i data-feather="calendar" class="w-4 h-4 text-blue-600"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800">Maintenance Terjadwal</h2>
                        <p class="text-xs text-slate-400">Menampilkan maks. 10 jadwal aktif & terbaru</p>
                    </div>
                </div>
            </div>

            <!-- Tabel preview 10 data -->
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
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">SPK</th>
                        </tr>
                    </thead>
                    <tbody id="maintTable">
                        <tr><td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer tombol Lihat Semua -->
            <div id="maintFooter" class="px-6 py-4 border-t border-slate-100 hidden">
                <button onclick="toggleMaintAll()" id="maintToggleBtn"
                    class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">
                    <i data-feather="chevron-down" class="w-4 h-4" id="maintChevron"></i>
                    <span id="maintToggleLabel">Lihat semua maintenance</span>
                </button>
            </div>

            <!-- Section Lihat Semua -->
            <div id="maintAllSection" class="lihat-semua-section border-t border-slate-100">
                <div class="px-6 py-4 flex flex-wrap gap-2 items-center bg-slate-50 border-b border-slate-100">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide mr-1">Filter:</span>
                    <button onclick="filterMaintAll('all')" id="ma-all" class="tab-btn tab-active text-xs px-3 py-1.5 rounded-lg font-semibold">Semua</button>
                    <button onclick="filterMaintAll('this_month')" id="ma-this_month" class="tab-btn tab-inactive text-xs px-3 py-1.5 rounded-lg font-semibold">Bulan ini</button>
                    <button onclick="filterMaintAll('pending')" id="ma-pending" class="tab-btn tab-inactive text-xs px-3 py-1.5 rounded-lg font-semibold">Menunggu</button>
                    <button onclick="filterMaintAll('confirmed')" id="ma-confirmed" class="tab-btn tab-inactive text-xs px-3 py-1.5 rounded-lg font-semibold">Dikonfirmasi</button>
                    <button onclick="filterMaintAll('in_progress')" id="ma-in_progress" class="tab-btn tab-inactive text-xs px-3 py-1.5 rounded-lg font-semibold">Berjalan</button>
                    <button onclick="filterMaintAll('done')" id="ma-done" class="tab-btn tab-inactive text-xs px-3 py-1.5 rounded-lg font-semibold">Selesai</button>
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
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">SPK</th>
                            </tr>
                        </thead>
                        <tbody id="maintAllTable">
                            <tr><td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- DAFTAR ASET (ringkasan + tombol lihat) -->
        <div class="card">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="icon-box bg-purple-100">
                        <i data-feather="archive" class="w-4 h-4 text-purple-600"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800">Daftar Aset</h2>
                        <p class="text-xs text-slate-400">Inventaris aset perusahaan</p>
                    </div>
                </div>
                <button onclick="toggleAssetTable()" id="assetToggleBtn"
                    class="flex items-center gap-2 bg-slate-800 text-white text-xs font-semibold px-4 py-2 rounded-xl hover:bg-slate-700 transition-colors">
                    <i data-feather="list" class="w-3.5 h-3.5"></i>
                    <span id="assetToggleLabel">Lihat Daftar Aset</span>
                </button>
            </div>

            <!-- Ringkasan stat aset -->
            <div class="px-6 py-5 grid grid-cols-2 md:grid-cols-4 gap-4" id="assetSummary">
                <div class="bg-slate-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-slate-800" id="assetTotal">-</p>
                    <p class="text-xs text-slate-400 mt-1">Total Aset</p>
                </div>
                <div class="bg-green-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-green-600" id="assetBaik">-</p>
                    <p class="text-xs text-slate-400 mt-1">Kondisi Baik</p>
                </div>
                <div class="bg-amber-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-amber-600" id="assetRusakRingan">-</p>
                    <p class="text-xs text-slate-400 mt-1">Rusak Ringan</p>
                </div>
                <div class="bg-red-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-red-600" id="assetRusakBerat">-</p>
                    <p class="text-xs text-slate-400 mt-1">Rusak Berat</p>
                </div>
            </div>

            <!-- Tabel aset (tersembunyi dulu) -->
            <div id="assetTableSection" class="lihat-semua-section border-t border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Nama Aset</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Cabang</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Kategori</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Kondisi</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wide">Jumlah</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Nilai</th>
                            </tr>
                        </thead>
                        <tbody id="assetTable">
                            <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400 text-sm">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<!-- MODAL DETAIL PERBAIKAN -->
<div id="detailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Detail Pekerjaan</h3>
            <button onclick="closeDetail()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div id="detailContent" class="p-6 space-y-3"></div>
    </div>
</div>

<script>
const token = localStorage.getItem('token');
if (!token) { window.location.href = '/login'; }

const user = JSON.parse(localStorage.getItem('user') || '{}');
document.getElementById('userInfo').textContent    = (user.name ?? '-') + ' · Management';
document.getElementById('userInitial').textContent = user.name ? user.name.split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase() : 'MG';
document.getElementById('welcomeName').textContent = user.name ?? 'Management';
document.getElementById('todayDate').textContent   = new Date().toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });

const STATUS_REPAIR = {
    pending:          { label: 'Pending',           cls: 'bg-amber-100 text-amber-700' },
    approved:         { label: 'Disetujui',         cls: 'bg-green-100 text-green-700' },
    waiting_material: { label: 'Butuh Material',    cls: 'bg-orange-100 text-orange-700' },
    material_ready:   { label: 'Material Siap',     cls: 'bg-cyan-100 text-cyan-700' },
    on_progress:      { label: 'Dikerjakan',        cls: 'bg-purple-100 text-purple-700' },
    done:             { label: 'Selesai',            cls: 'bg-slate-100 text-slate-600' },
    rejected:         { label: 'Ditolak',            cls: 'bg-red-100 text-red-700' },
};
const STATUS_MAINT = {
    pending:     { label: 'Menunggu Konfirmasi', cls: 'bg-amber-100 text-amber-700' },
    confirmed:   { label: 'Dikonfirmasi',        cls: 'bg-blue-100 text-blue-700' },
    in_progress: { label: 'Sedang Berjalan',     cls: 'bg-purple-100 text-purple-700' },
    done:        { label: 'Selesai',             cls: 'bg-green-100 text-green-700' },
};
const URGENCY = { low:'🟢 Santai', medium:'🟡 Segera', high:'🔴 Prioritas' };
const PERIOD  = { weekly:'Mingguan', monthly:'Bulanan', quarterly:'Triwulan', yearly:'Tahunan' };
const CONDITION = {
    'baik':         { label: 'Baik',         cls: 'bg-green-100 text-green-700' },
    'rusak ringan': { label: 'Rusak Ringan', cls: 'bg-amber-100 text-amber-700' },
    'rusak berat':  { label: 'Rusak Berat',  cls: 'bg-red-100 text-red-700' },
};

const REPAIR_ACTIVE = ['approved','on_progress','waiting_material','material_ready','scheduled'];
const MAINT_ACTIVE  = ['confirmed','in_progress','pending'];

function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
}

function isThisMonth(dateStr) {
    if (!dateStr) return false;
    const d = new Date(dateStr);
    const now = new Date();
    return d.getMonth() === now.getMonth() && d.getFullYear() === now.getFullYear();
}

function spkBadge(item, type) {
    if (item.spk_sent_at) return `<span class="status-badge spk-sent">✓ Terkirim</span>`;
    if ((type==='repair' && item.technician_id && item.schedule_date) ||
        (type==='maint'  && item.worker_confirmed_at && !item.spk_sent_at))
        return `<span class="status-badge spk-pending">⏳ Siap Kirim</span>`;
    return `<span class="status-badge spk-none">— Belum</span>`;
}

/* ─── helper: build top-10 preview ─── */
function buildPreview(data, activeStatuses) {
    const active = data.filter(i => activeStatuses.includes(i.status));
    const done   = data.filter(i => !activeStatuses.includes(i.status))
                       .sort((a,b) => new Date(b.updated_at||0) - new Date(a.updated_at||0));
    const preview = [...active, ...done].slice(0, 10);
    return preview;
}

/* ────── PERBAIKAN ────── */
let repairData = [];
let repairAllFilter = 'all';
let repairAllOpen = false;

function renderRepairPreview() {
    const preview = buildPreview(repairData, REPAIR_ACTIVE);
    const tbody = document.getElementById('repairTable');
    if (!preview.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">Tidak ada data.</td></tr>`;
        return;
    }
    tbody.innerHTML = preview.map(item => repairRow(item)).join('');
    // tampilkan tombol lihat semua jika data > 10
    document.getElementById('repairFooter').classList.toggle('hidden', repairData.length <= 10);
    feather.replace();
}

function repairRow(item) {
    const st = STATUS_REPAIR[item.status] ?? { label: item.status, cls: 'bg-slate-100 text-slate-500' };
    const isMaterial = item.status === 'waiting_material' || item.status === 'material_ready';
    return `
    <tr class="border-b border-slate-50 last:border-0 cursor-pointer" onclick="openDetail(${item.id}, 'repair')">
        <td class="px-5 py-3.5">
            <p class="font-semibold text-slate-700">${item.title ?? '-'}</p>
            <p class="text-xs text-slate-400">${item.category ?? '-'}</p>
        </td>
        <td class="px-5 py-3.5 text-slate-500 text-xs">${item.branch ?? '-'}</td>
        <td class="px-5 py-3.5 text-slate-500 text-xs">${item.technician_name ?? '<span class="text-slate-300">Belum assign</span>'}</td>
        <td class="px-5 py-3.5">
            ${item.urgency ? `<span class="text-xs">${URGENCY[item.urgency]??item.urgency}</span>` : '<span class="text-slate-300 text-xs">-</span>'}
        </td>
        <td class="px-5 py-3.5"><span class="status-badge ${st.cls}">${st.label}</span></td>
        <td class="px-5 py-3.5">${spkBadge(item,'repair')}</td>
        <td class="px-5 py-3.5">
            ${isMaterial
                ? `<span class="status-badge bg-orange-100 text-orange-700">⚠ ${item.status==='waiting_material'?'Menunggu':'Siap'}</span>`
                : '<span class="text-slate-300 text-xs">-</span>'}
        </td>
    </tr>`;
}

function filterRepairAll(f) {
    repairAllFilter = f;
    document.querySelectorAll('[id^="ra-"]').forEach(b => b.className = 'tab-btn tab-inactive text-xs px-3 py-1.5 rounded-lg font-semibold');
    document.getElementById('ra-' + f).className = 'tab-btn tab-active text-xs px-3 py-1.5 rounded-lg font-semibold';
    renderRepairAll();
}

function renderRepairAll() {
    let filtered = repairData;
    if (repairAllFilter === 'this_month') {
        filtered = repairData.filter(i => isThisMonth(i.created_at) || isThisMonth(i.updated_at));
    } else if (repairAllFilter !== 'all') {
        filtered = repairData.filter(i => i.status === repairAllFilter);
    }
    const tbody = document.getElementById('repairAllTable');
    if (!filtered.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">Tidak ada data.</td></tr>`;
        return;
    }
    tbody.innerHTML = filtered.map(item => repairRow(item)).join('');
    feather.replace();
}

function toggleRepairAll() {
    repairAllOpen = !repairAllOpen;
    const section = document.getElementById('repairAllSection');
    const chevron = document.getElementById('repairChevron');
    const label   = document.getElementById('repairToggleLabel');
    section.classList.toggle('open', repairAllOpen);
    chevron.style.transform = repairAllOpen ? 'rotate(180deg)' : '';
    label.textContent = repairAllOpen ? 'Sembunyikan' : 'Lihat semua pekerjaan';
    if (repairAllOpen) renderRepairAll();
    feather.replace();
}

async function loadRepair() {
    try {
        const res  = await fetch('/api/requests', { headers: { Authorization: 'Bearer ' + token } });
        const data = await res.json();
        repairData = Array.isArray(data) ? data : (data.data ?? []);
        renderRepairPreview();
        updateStats();
    } catch(err) { console.error(err); }
}

/* ────── MAINTENANCE ────── */
let maintData = [];
let maintAllFilter = 'all';
let maintAllOpen = false;

function renderMaintPreview() {
    const preview = buildPreview(maintData, MAINT_ACTIVE);
    const tbody = document.getElementById('maintTable');
    if (!preview.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">Tidak ada data.</td></tr>`;
        return;
    }
    tbody.innerHTML = preview.map(item => maintRow(item)).join('');
    document.getElementById('maintFooter').classList.toggle('hidden', maintData.length <= 10);
    feather.replace();
}

function maintRow(item) {
    const st = STATUS_MAINT[item.status] ?? { label: item.status, cls: 'bg-slate-100 text-slate-500' };
    return `
    <tr class="border-b border-slate-50 last:border-0">
        <td class="px-5 py-3.5">
            <p class="font-semibold text-slate-700">${item.title ?? '-'}</p>
            ${item.sub_category_name ? `<span class="text-xs bg-blue-50 text-blue-500 px-2 py-0.5 rounded-full">${item.sub_category_name}</span>` : ''}
        </td>
        <td class="px-5 py-3.5 text-slate-500 text-xs">${item.category_name ?? item.category ?? '-'}</td>
        <td class="px-5 py-3.5 text-slate-500 text-xs">${item.worker_name ?? '<span class="text-slate-300">-</span>'}</td>
        <td class="px-5 py-3.5 text-xs text-slate-500">${PERIOD[item.period] ?? item.period ?? '-'}</td>
        <td class="px-5 py-3.5 text-xs text-slate-500">${formatDate(item.scheduled_date)}</td>
        <td class="px-5 py-3.5"><span class="status-badge ${st.cls}">${st.label}</span></td>
        <td class="px-5 py-3.5">${spkBadge(item,'maint')}</td>
    </tr>`;
}

function filterMaintAll(f) {
    maintAllFilter = f;
    document.querySelectorAll('[id^="ma-"]').forEach(b => b.className = 'tab-btn tab-inactive text-xs px-3 py-1.5 rounded-lg font-semibold');
    document.getElementById('ma-' + f).className = 'tab-btn tab-active text-xs px-3 py-1.5 rounded-lg font-semibold';
    renderMaintAll();
}

function renderMaintAll() {
    let filtered = maintData;
    if (maintAllFilter === 'this_month') {
        filtered = maintData.filter(i => isThisMonth(i.scheduled_date) || isThisMonth(i.updated_at));
    } else if (maintAllFilter !== 'all') {
        filtered = maintData.filter(i => i.status === maintAllFilter);
    }
    const tbody = document.getElementById('maintAllTable');
    if (!filtered.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-5 py-8 text-center text-slate-400 text-sm">Tidak ada data.</td></tr>`;
        return;
    }
    tbody.innerHTML = filtered.map(item => maintRow(item)).join('');
    feather.replace();
}

function toggleMaintAll() {
    maintAllOpen = !maintAllOpen;
    const section = document.getElementById('maintAllSection');
    const chevron = document.getElementById('maintChevron');
    const label   = document.getElementById('maintToggleLabel');
    section.classList.toggle('open', maintAllOpen);
    chevron.style.transform = maintAllOpen ? 'rotate(180deg)' : '';
    label.textContent = maintAllOpen ? 'Sembunyikan' : 'Lihat semua maintenance';
    if (maintAllOpen) renderMaintAll();
    feather.replace();
}

async function loadMaint() {
    try {
        const res  = await fetch('/api/scheduled-maintenances', { headers: { Authorization: 'Bearer ' + token } });
        const data = await res.json();
        maintData = Array.isArray(data) ? data : (data.data ?? []);
        renderMaintPreview();
        updateStats();
    } catch(err) { console.error(err); }
}

/* ────── ASET ────── */
let assetTableOpen = false;
let assetsLoaded   = false;

function toggleAssetTable() {
    assetTableOpen = !assetTableOpen;
    const section = document.getElementById('assetTableSection');
    const label   = document.getElementById('assetToggleLabel');
    section.classList.toggle('open', assetTableOpen);
    label.textContent = assetTableOpen ? 'Sembunyikan Daftar' : 'Lihat Daftar Aset';
    if (assetTableOpen && !assetsLoaded) loadAssetTable();
}

async function loadAssets() {
    try {
        const res   = await fetch('/api/assets', { headers: { Authorization: 'Bearer ' + token } });
        const data  = await res.json();
        const assets = Array.isArray(data) ? data : (data.data ?? []);

        // hitung summary
        const baik        = assets.filter(a => a.condition === 'baik').length;
        const rusakRingan = assets.filter(a => a.condition === 'rusak ringan').length;
        const rusakBerat  = assets.filter(a => a.condition === 'rusak berat').length;

        document.getElementById('assetTotal').textContent       = assets.length;
        document.getElementById('assetBaik').textContent        = baik;
        document.getElementById('assetRusakRingan').textContent = rusakRingan;
        document.getElementById('assetRusakBerat').textContent  = rusakBerat;

        // simpan untuk tabel
        window._assetsData = assets;
    } catch(err) { console.error(err); }
}

function loadAssetTable() {
    assetsLoaded = true;
    const assets = window._assetsData ?? [];
    const tbody  = document.getElementById('assetTable');
    if (!assets.length) {
        tbody.innerHTML = `<tr><td colspan="6" class="px-5 py-8 text-center text-slate-400 text-sm">Tidak ada data aset.</td></tr>`;
        return;
    }
    tbody.innerHTML = assets.map(a => {
        const cond = CONDITION[a.condition] ?? { label: a.condition ?? '-', cls: 'bg-slate-100 text-slate-500' };
        return `
        <tr class="border-b border-slate-50 last:border-0">
            <td class="px-5 py-3.5">
                <p class="font-semibold text-slate-700">${a.name}</p>
                <p class="text-xs text-slate-400">${a.room?.name ?? '-'}</p>
            </td>
            <td class="px-5 py-3.5 text-slate-500 text-sm">${a.branch?.name ?? '-'}</td>
            <td class="px-5 py-3.5">
                ${a.category?.name ? `<span class="status-badge bg-purple-50 text-purple-700">${a.category.name}</span>` : '<span class="text-slate-300">-</span>'}
            </td>
            <td class="px-5 py-3.5"><span class="status-badge ${cond.cls}">${cond.label}</span></td>
            <td class="px-5 py-3.5 text-center">
                <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg text-xs font-bold">${a.quantity ?? 1}</span>
            </td>
            <td class="px-5 py-3.5 text-slate-600 text-sm font-medium">
                ${a.value ? 'Rp ' + parseInt(a.value).toLocaleString('id-ID') : '<span class="text-slate-300">-</span>'}
            </td>
        </tr>`;
    }).join('');
}

/* ────── STATS ────── */
function updateStats() {
    const allJobs   = [...repairData, ...maintData];
    const ongoing   = repairData.filter(r => REPAIR_ACTIVE.includes(r.status)).length
                    + maintData.filter(m => ['confirmed','in_progress'].includes(m.status)).length;
    const thisMonth = new Date().getMonth();
    const thisYear  = new Date().getFullYear();
    const done = allJobs.filter(i => {
        const d = i.completed_at ?? i.updated_at;
        if (!d || i.status !== 'done') return false;
        const dt = new Date(d);
        return dt.getMonth() === thisMonth && dt.getFullYear() === thisYear;
    }).length;
    const material = repairData.filter(r => r.status === 'waiting_material').length;

    document.getElementById('statTotal').textContent    = allJobs.length;
    document.getElementById('statOngoing').textContent  = ongoing;
    document.getElementById('statDone').textContent     = done;
    document.getElementById('statMaterial').textContent = material;
}

/* ────── DETAIL MODAL ────── */
async function openDetail(id, type) {
    const url = type === 'repair' ? `/api/requests/${id}` : `/api/scheduled-maintenances/${id}`;
    const res  = await fetch(url, { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();
    const st   = type === 'repair'
        ? (STATUS_REPAIR[data.status] ?? { label: data.status, cls: '' })
        : (STATUS_MAINT[data.status]  ?? { label: data.status, cls: '' });

    document.getElementById('detailContent').innerHTML = `
        <div class="flex justify-between items-start">
            <h4 class="font-bold text-slate-800">${data.title ?? '-'}</h4>
            <span class="status-badge ${st.cls}">${st.label}</span>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-xs text-slate-400 mb-0.5">${type==='repair'?'Cabang':'Kategori'}</p>
                <p class="text-sm font-semibold text-slate-700">${type==='repair'?(data.branch??'-'):(data.category_name??data.category??'-')}</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-xs text-slate-400 mb-0.5">Tukang</p>
                <p class="text-sm font-semibold text-slate-700">${(type==='repair'?data.technician_name:data.worker_name)??'-'}</p>
            </div>
            ${(data.schedule_date??data.scheduled_date) ? `
            <div class="bg-slate-50 rounded-xl p-3 col-span-2">
                <p class="text-xs text-slate-400 mb-0.5">Tanggal</p>
                <p class="text-sm font-semibold text-slate-700">${formatDate(data.schedule_date??data.scheduled_date)}</p>
            </div>` : ''}
        </div>
        ${(data.description??data.note) ? `
        <div class="bg-slate-50 rounded-xl p-4">
            <p class="text-xs text-slate-400 mb-1">Keterangan</p>
            <p class="text-sm text-slate-700">${data.description??data.note}</p>
        </div>` : ''}
        ${data.spk_sent_at ? `
        <div class="bg-green-50 border border-green-200 rounded-xl p-3 flex items-center gap-2">
            <i data-feather="file-text" class="w-4 h-4 text-green-600 shrink-0"></i>
            <div>
                <p class="text-xs font-bold text-green-700">${data.spk_number??'SPK Terkirim'}</p>
                <p class="text-xs text-green-600">Dikirim: ${formatDate(data.spk_sent_at)}</p>
            </div>
        </div>` : ''}
        ${data.completion_note ? `
        <div class="bg-slate-50 rounded-xl p-4">
            <p class="text-xs text-slate-400 mb-1">Catatan Selesai</p>
            <p class="text-sm text-slate-700">${data.completion_note}</p>
        </div>` : ''}
        ${data.completion_photo ? `<img src="/storage/${data.completion_photo}" class="w-full h-44 object-cover rounded-xl">` : ''}
        ${data.photo ? `<img src="/storage/${data.photo}" class="w-full h-44 object-cover rounded-xl">` : ''}
    `;
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
    feather.replace();
}

function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
}

function goTo(url) { window.location.href = url; }
function goToDashboard() { window.location.href = '/dashboard-management'; }

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadRepair();
    loadMaint();
    loadAssets();
});
</script>
</body>
</html>