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

        .btn-secondary {
            background: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-secondary:hover { background: #f1f5f9; transform: translateY(-1px); }

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

        .btn-purple {
            background: #f5f3ff;
            color: #7c3aed;
            border: 1px solid #ddd6fe;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-purple:hover { background: #ede9fe; }

        .form-input {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .form-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        .form-panel {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            margin-top: 12px;
        }

        .table-row {
            transition: background 0.1s;
        }
        .table-row:hover td { background: #f8fafc; }

        .icon-box {
            width: 40px; height: 40px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }

        .slide-down {
            animation: slideDown 0.2s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
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
            <h1 class="font-bold text-slate-800 text-lg">Dashboard Admin GA</h1>
            <p class="text-xs text-slate-400 mt-0.5">General Affairs Management System</p>
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
                <svg viewBox="0 0 200 200" class="w-full h-full"><circle cx="150" cy="50" r="80" fill="white"/><circle cx="50" cy="150" r="60" fill="white"/></svg>
            </div>
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Selamat datang kembali 👋</p>
                <h2 class="text-white text-xl font-bold" id="welcomeName">Admin GA</h2>
                <p class="text-slate-400 text-sm mt-1">Kelola pengajuan perbaikan, approval, dan monitoring pekerjaan.</p>
            </div>
            <div class="hidden md:flex items-center gap-2">
                <div class="bg-white/10 rounded-xl px-4 py-3 text-center">
                    <p class="text-white/60 text-xs">Hari ini</p>
                    <p class="text-white font-bold text-lg" id="todayDate">-</p>
                </div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Request</p>
                    <div class="icon-box bg-slate-100">
                        <i data-feather="inbox" class="w-4 h-4 text-slate-500"></i>
                    </div>
                </div>
                <p id="totalRequest" class="text-3xl font-bold text-slate-800">0</p>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Pending</p>
                    <div class="icon-box bg-amber-50">
                        <i data-feather="clock" class="w-4 h-4 text-amber-500"></i>
                    </div>
                </div>
                <p id="pendingRequest" class="text-3xl font-bold text-amber-500">0</p>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Approved</p>
                    <div class="icon-box bg-green-50">
                        <i data-feather="check-circle" class="w-4 h-4 text-green-500"></i>
                    </div>
                </div>
                <p id="approvedRequest" class="text-3xl font-bold text-green-500">0</p>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Kategori</p>
                    <div class="icon-box bg-blue-50">
                        <i data-feather="tag" class="w-4 h-4 text-blue-500"></i>
                    </div>
                </div>
                <p id="totalCategories" class="text-3xl font-bold text-blue-500">0</p>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Sub Kategori</p>
                    <div class="icon-box bg-purple-50">
                        <i data-feather="layers" class="w-4 h-4 text-purple-500"></i>
                    </div>
                </div>
                <p id="totalSubCategories" class="text-3xl font-bold text-purple-500">0</p>
            </div>

        </div>

        <!-- KATEGORI & SUB KATEGORI -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- KATEGORI -->
            <div class="card p-6">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <h2 class="font-bold text-slate-800 text-base">Kategori Perbaikan</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Kategori utama untuk pengajuan</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="showAddCategory()" class="btn-blue">
                            <i data-feather="plus" class="w-3.5 h-3.5"></i> Tambah
                        </button>
                        <button onclick="toggleCategoryList()" class="btn-secondary">
                            <i data-feather="list" class="w-3.5 h-3.5"></i> List
                        </button>
                    </div>
                </div>

                <!-- Form -->
                <div id="categoryForm" class="hidden form-panel slide-down mb-4">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 block">Nama Kategori</label>
                    <div class="flex gap-2">
                        <input id="categoryName" type="text" placeholder="Contoh: Kelistrikan, Sipil..."
                            class="form-input flex-1">
                        <button onclick="addCategory()" class="btn-primary whitespace-nowrap">
                            <i data-feather="save" class="w-3.5 h-3.5"></i> Simpan
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div id="categoryTableWrapper" class="hidden">
                    <div class="max-h-56 overflow-y-auto rounded-xl border border-slate-100">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Nama Kategori</th>
                                </tr>
                            </thead>
                            <tbody id="categoryTable"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Empty hint -->
                <div id="categoryEmptyHint" class="text-center py-8">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i data-feather="tag" class="w-5 h-5 text-blue-400"></i>
                    </div>
                    <p class="text-slate-400 text-sm">Klik <strong>List</strong> untuk lihat data,<br>atau <strong>Tambah</strong> untuk buat baru.</p>
                </div>
            </div>

            <!-- SUB KATEGORI -->
            <div class="card p-6">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <h2 class="font-bold text-slate-800 text-base">Sub Kategori Perbaikan</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Detail spesifik dari kategori utama</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="showAddSubCategory()" class="btn-purple">
                            <i data-feather="plus" class="w-3.5 h-3.5"></i> Tambah
                        </button>
                        <button onclick="toggleSubCategoryList()" class="btn-secondary">
                            <i data-feather="list" class="w-3.5 h-3.5"></i> List
                        </button>
                    </div>
                </div>

                <!-- Form -->
                <div id="subCategoryForm" class="hidden form-panel slide-down mb-4">
                    <div class="space-y-2">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 block">Kategori Induk</label>
                            <select id="subCategoryCategory" class="form-input"></select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 block">Nama Sub Kategori</label>
                            <div class="flex gap-2">
                                <input id="subCategoryName" type="text" placeholder="Contoh: Instalasi Kabel..."
                                    class="form-input flex-1">
                                <button onclick="addSubCategory()" class="btn-primary whitespace-nowrap">
                                    <i data-feather="save" class="w-3.5 h-3.5"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div id="subCategoryTableWrapper" class="hidden">
                    <div class="max-h-56 overflow-y-auto rounded-xl border border-slate-100">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Sub Kategori</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Kategori</th>
                                </tr>
                            </thead>
                            <tbody id="subCategoryTable"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Empty hint -->
                <div id="subCategoryEmptyHint" class="text-center py-8">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i data-feather="layers" class="w-5 h-5 text-purple-400"></i>
                    </div>
                    <p class="text-slate-400 text-sm">Klik <strong>List</strong> untuk lihat data,<br>atau <strong>Tambah</strong> untuk buat baru.</p>
                </div>
            </div>

        </div>

    </div>
</div>
</div>

<script>
const token = localStorage.getItem('token');
const user  = JSON.parse(localStorage.getItem('user'));

if (!token || !user) { localStorage.clear(); window.location.href = '/login'; }

// User info
document.getElementById('userInfo').innerText = user.name + ' · ' + user.role;
document.getElementById('welcomeName').innerText = user.name;
document.getElementById('userInitial').innerText = user.name?.split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase();
document.getElementById('todayDate').innerText = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });

// ─── DASHBOARD ───────────────────────────────────────────
async function loadDashboard() {
    try {
        const res  = await fetch('/api/requests', { headers: { Authorization: 'Bearer ' + token } });
        const data = await res.json();

        if (!res.ok || !Array.isArray(data)) {
            if (res.status === 401) { localStorage.clear(); window.location.href = '/login'; }
            return;
        }

        document.getElementById('totalRequest').innerText   = data.length;
        document.getElementById('pendingRequest').innerText  = data.filter(i => i.status === 'pending').length;
        document.getElementById('approvedRequest').innerText = data.filter(i => i.status === 'approved').length;
    } catch (err) { console.error(err); }
}

// ─── CATEGORIES ──────────────────────────────────────────
async function loadCategories() {
    try {
        const res  = await fetch('/api/categories', { headers: { Authorization: 'Bearer ' + token } });
        const data = await res.json();
        if (!Array.isArray(data)) return;
        document.getElementById('totalCategories').innerText = data.length;
    } catch (err) { console.error(err); }
}

async function loadCategoriesTable() {
    const res  = await fetch('/api/categories', { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();
    document.getElementById('categoryTable').innerHTML = data.map(c => `
        <tr class="table-row border-b border-slate-50 last:border-0">
            <td class="px-4 py-3 text-slate-600 font-medium">${c.name}</td>
        </tr>
    `).join('');
}

async function addCategory() {
    const name = document.getElementById('categoryName').value.trim();
    if (!name) { alert('Nama wajib diisi!'); return; }
    await fetch('/api/categories', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify({ name })
    });
    document.getElementById('categoryName').value = '';
    showAddCategory(); // tutup form
    loadCategories();
    loadCategoriesTable();
    alert('Kategori berhasil ditambahkan!');
}

function showAddCategory() {
    const form = document.getElementById('categoryForm');
    const hint = document.getElementById('categoryEmptyHint');
    form.classList.toggle('hidden');
    if (!form.classList.contains('hidden')) hint.classList.add('hidden');
    else if (document.getElementById('categoryTableWrapper').classList.contains('hidden')) hint.classList.remove('hidden');
    feather.replace();
}

function toggleCategoryList() {
    const wrapper = document.getElementById('categoryTableWrapper');
    const hint    = document.getElementById('categoryEmptyHint');
    wrapper.classList.toggle('hidden');
    if (!wrapper.classList.contains('hidden')) {
        hint.classList.add('hidden');
        loadCategoriesTable();
    } else if (document.getElementById('categoryForm').classList.contains('hidden')) {
        hint.classList.remove('hidden');
    }
}

// ─── SUB CATEGORIES ──────────────────────────────────────
async function loadSubCategories() {
    try {
        const res  = await fetch('/api/sub-categories', { headers: { Authorization: 'Bearer ' + token } });
        const data = await res.json();
        if (!Array.isArray(data)) return;
        document.getElementById('totalSubCategories').innerText = data.length;
    } catch (err) { console.error(err); }
}

async function loadSubCategoriesTable() {
    const res  = await fetch('/api/sub-categories', { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();
    document.getElementById('subCategoryTable').innerHTML = data.map(s => `
        <tr class="table-row border-b border-slate-50 last:border-0">
            <td class="px-4 py-3 text-slate-600 font-medium">${s.name}</td>
            <td class="px-4 py-3">
                <span class="bg-blue-50 text-blue-600 text-xs font-medium px-2.5 py-1 rounded-full">
                    ${s.category?.name || '-'}
                </span>
            </td>
        </tr>
    `).join('');
}

async function loadCategoryDropdown() {
    const res  = await fetch('/api/categories', { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();
    document.getElementById('subCategoryCategory').innerHTML =
        '<option value="">Pilih Kategori</option>' +
        data.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
}

async function addSubCategory() {
    const name        = document.getElementById('subCategoryName').value.trim();
    const category_id = document.getElementById('subCategoryCategory').value;
    if (!name || !category_id) { alert('Semua field wajib diisi!'); return; }
    await fetch('/api/sub-categories', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, category_id })
    });
    document.getElementById('subCategoryName').value = '';
    showAddSubCategory(); // tutup form
    loadSubCategories();
    loadSubCategoriesTable();
    alert('Sub kategori berhasil ditambahkan!');
}

function showAddSubCategory() {
    const form = document.getElementById('subCategoryForm');
    const hint = document.getElementById('subCategoryEmptyHint');
    form.classList.toggle('hidden');
    if (!form.classList.contains('hidden')) {
        hint.classList.add('hidden');
        loadCategoryDropdown();
    } else if (document.getElementById('subCategoryTableWrapper').classList.contains('hidden')) {
        hint.classList.remove('hidden');
    }
    feather.replace();
}

function toggleSubCategoryList() {
    const wrapper = document.getElementById('subCategoryTableWrapper');
    const hint    = document.getElementById('subCategoryEmptyHint');
    wrapper.classList.toggle('hidden');
    if (!wrapper.classList.contains('hidden')) {
        hint.classList.add('hidden');
        loadSubCategoriesTable();
    } else if (document.getElementById('subCategoryForm').classList.contains('hidden')) {
        hint.classList.remove('hidden');
    }
}

function goTo(url) { window.location.href = url; }

// ─── INIT ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadDashboard();
    loadCategories();
    loadSubCategories();
});
</script>

</body>
</html>