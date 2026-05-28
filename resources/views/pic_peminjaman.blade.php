<!DOCTYPE html>
<html>
<head>
    <title>Peminjaman Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn { transition: all 0.15s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .modal-box { animation: modalIn 0.2s ease; }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.96) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }
        tr:hover td { background: #f8fafc; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">

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
                        <h1 class="font-bold text-slate-800 text-lg">Peminjaman Alat</h1>
                        <p class="text-xs text-slate-400 mt-0.5">Ajukan dan pantau peminjaman aset</p>
                    </div>
                </div>
            </div>

<div class="p-8">

    <!-- ACTION BAR -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <h2 class="text-xl font-bold text-slate-800">Pengajuan Saya</h2>
        <div class="flex gap-2 flex-wrap">
            <button onclick="openAssetListModal()"
                class="btn flex items-center gap-2 bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm">
                <i data-feather="package" class="w-4 h-4"></i> Lihat Daftar Aset
            </button>
            <button onclick="openBorrowModal()"
                class="btn flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm">
                <i data-feather="plus" class="w-4 h-4"></i> Ajukan Peminjaman
            </button>
        </div>
    </div>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
            <p class="text-xs text-slate-400 mb-1">Menunggu</p>
            <p id="stat_requested" class="text-2xl font-bold text-amber-500">0</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
            <p class="text-xs text-slate-400 mb-1">Disetujui</p>
            <p id="stat_approved" class="text-2xl font-bold text-blue-600">0</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
            <p class="text-xs text-slate-400 mb-1">Dipakai</p>
            <p id="stat_picked" class="text-2xl font-bold text-indigo-600">0</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
            <p class="text-xs text-slate-400 mb-1">Selesai</p>
            <p id="stat_returned" class="text-2xl font-bold text-green-600">0</p>
        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Aset</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Dari → Tujuan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal Pinjam</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Alasan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    </tr>
                </thead>
                <tbody id="borrowTable"></tbody>
            </table>
        </div>
        <div id="emptyState" class="hidden text-center py-16">
            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-feather="inbox" class="w-7 h-7 text-slate-300"></i>
            </div>
            <p class="text-slate-400 font-medium text-sm">Belum ada pengajuan peminjaman</p>
            <p class="text-slate-300 text-xs mt-1">Klik "+ Ajukan Peminjaman" untuk memulai</p>
        </div>
    </div>

</div>
</div>
</div>

<!-- ══════════════ MODAL DAFTAR ASET ══════════════ -->
<div id="assetListModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl modal-box max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-800">Daftar Aset Tersedia</h3>
                <p class="text-xs text-slate-400 mt-0.5">Semua aset yang terdaftar di seluruh cabang</p>
            </div>
            <button onclick="closeAssetListModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Filter -->
        <div class="px-6 py-3 border-b border-slate-50 flex flex-wrap gap-2">
            <select id="filterAssetBranch" onchange="filterAssets()"
                class="text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Cabang</option>
            </select>
            <select id="filterAssetCategory" onchange="filterAssets()"
                class="text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Kategori</option>
            </select>
            <select id="filterAssetCondition" onchange="filterAssets()"
                class="text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua Kondisi</option>
                <option value="baik">Baik</option>
                <option value="rusak ringan">Rusak Ringan</option>
                <option value="rusak berat">Rusak Berat</option>
            </select>
        </div>

        <!-- Table -->
        <div class="overflow-y-auto flex-1">
            <table class="w-full text-sm">
                <thead class="sticky top-0 bg-slate-50">
                    <tr class="border-b border-slate-100">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Aset</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Cabang</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Ruangan</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kategori</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Qty</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kondisi</th>
                    </tr>
                </thead>
                <tbody id="assetListTable"></tbody>
            </table>
            <div id="assetListEmpty" class="hidden text-center py-12 text-slate-400 text-sm">Tidak ada aset ditemukan</div>
        </div>

        <div class="px-6 py-4 border-t border-slate-100 flex justify-end">
            <button onclick="closeAssetListModal()"
                class="btn border border-slate-200 text-slate-500 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-50">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ══════════════ MODAL AJUKAN PEMINJAMAN ══════════════ -->
<div id="borrowModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl modal-box max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-800">Ajukan Peminjaman</h3>
                <p class="text-xs text-slate-400 mt-0.5">Isi detail peminjaman aset</p>
            </div>
            <button onclick="closeBorrowModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="p-6 space-y-4">

            <!-- Section: Aset yang Dipinjam -->
            <div class="bg-slate-50 rounded-xl p-4 space-y-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Aset yang Dipinjam</p>

                <div>
                    <label class="text-xs text-slate-500 block mb-1">Cabang Asal Aset <span class="text-red-400">*</span></label>
                    <select id="form_asset_branch" onchange="onFormBranchChange()"
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2.5 bg-white">
                        <option value="">Pilih Cabang</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs text-slate-500 block mb-1">Kategori Aset <span class="text-red-400">*</span></label>
                    <select id="form_asset_category" onchange="onFormCategoryChange()" disabled
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2.5 bg-white disabled:bg-slate-100 disabled:text-slate-400">
                        <option value="">Pilih Kategori</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs text-slate-500 block mb-1">Nama Aset <span class="text-red-400">*</span></label>
                    <select id="form_asset_id" disabled
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2.5 bg-white disabled:bg-slate-100 disabled:text-slate-400">
                        <option value="">Pilih Aset</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs text-slate-500 block mb-1">Jumlah yang Dipinjam <span class="text-red-400">*</span></label>
                    <input type="number" id="form_qty" min="1" value="1"
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2.5"
                        placeholder="Masukkan jumlah">
                </div>
            </div>

            <!-- Section: Tujuan Peminjaman -->
            <div class="bg-blue-50 rounded-xl p-4 space-y-3">
                <p class="text-xs font-semibold text-blue-400 uppercase tracking-wide">Tujuan Peminjaman</p>

                <div>
                    <label class="text-xs text-slate-500 block mb-1">Cabang Tujuan <span class="text-red-400">*</span></label>
                    <select id="form_dest_branch" onchange="onFormDestBranchChange()"
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2.5 bg-white">
                        <option value="">Pilih Cabang Tujuan</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs text-slate-500 block mb-1">Ruangan Tujuan <span class="text-red-400">*</span></label>
                    <select id="form_dest_room" disabled
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2.5 bg-white disabled:bg-slate-100 disabled:text-slate-400">
                        <option value="">Pilih Ruangan</option>
                    </select>
                </div>
            </div>

            <!-- Section: Waktu & Alasan -->
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-slate-500 block mb-1">Tanggal Mulai Pinjam <span class="text-red-400">*</span></label>
                        <input type="date" id="form_start_date"
                            class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2.5">
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 block mb-1">Tanggal Pengembalian <span class="text-red-400">*</span></label>
                        <input type="date" id="form_end_date"
                            class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2.5">
                    </div>
                </div>

                <div>
                    <label class="text-xs text-slate-500 block mb-1">Alasan Peminjaman <span class="text-red-400">*</span></label>
                    <textarea id="form_reason" rows="3"
                        placeholder="Jelaskan keperluan peminjaman aset ini..."
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2.5 resize-none"></textarea>
                </div>

                <div>
                    <label class="text-xs text-slate-500 block mb-1">Catatan Tambahan <span class="text-slate-400">(opsional)</span></label>
                    <input type="text" id="form_notes"
                        placeholder="Keterangan lain jika ada..."
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2.5">
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button onclick="closeBorrowModal()"
                    class="flex-1 btn border border-slate-200 text-slate-500 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50">
                    Batal
                </button>
                <button onclick="submitBorrow()"
                    class="flex-1 btn bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-semibold">
                    Kirim Pengajuan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const token = localStorage.getItem('token');
if (!token) window.location.href = '/login';

const user = JSON.parse(localStorage.getItem('user') || '{}');
if (user.name) document.getElementById('picName').textContent = user.name;

let allAssets = [];
let allBranches = [];
let allCategories = [];

/* ── STATUS ── */
function statusBadge(s) {
    const map = {
        requested: 'bg-amber-50 text-amber-700 border-amber-200',
        approved:  'bg-blue-50 text-blue-700 border-blue-200',
        picked:    'bg-indigo-50 text-indigo-700 border-indigo-200',
        returned:  'bg-green-50 text-green-700 border-green-200',
        rejected:  'bg-red-50 text-red-700 border-red-200',
    };
    const label = {
        requested: 'Menunggu', approved: 'Disetujui',
        picked: 'Dipakai', returned: 'Selesai', rejected: 'Ditolak'
    };
    return `<span class="px-2.5 py-1 rounded-full text-xs font-semibold border ${map[s] || 'bg-slate-100 text-slate-500'}">${label[s] ?? s}</span>`;
}

function conditionBadge(c) {
    const map = { 'baik': 'bg-green-100 text-green-700', 'rusak ringan': 'bg-amber-100 text-amber-700', 'rusak berat': 'bg-red-100 text-red-700' };
    return `<span class="px-2 py-0.5 rounded-full text-xs font-medium ${map[c] || 'bg-slate-100 text-slate-500'}">${c ?? '-'}</span>`;
}

function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

/* ══════════════════════════════════════
   LOAD DATA
══════════════════════════════════════ */
async function loadBorrowings() {
    const res  = await fetch('/api/borrowings/my', { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();

    // Stats
    const count = s => data.filter(d => d.status === s).length;
    document.getElementById('stat_requested').textContent = count('requested');
    document.getElementById('stat_approved').textContent  = count('approved');
    document.getElementById('stat_picked').textContent    = count('picked');
    document.getElementById('stat_returned').textContent  = count('returned');

    const table = document.getElementById('borrowTable');
    const empty = document.getElementById('emptyState');

    if (!data.length) {
        table.innerHTML = '';
        empty.classList.remove('hidden');
        feather.replace();
        return;
    }
    empty.classList.add('hidden');

    table.innerHTML = data.map(b => `
        <tr class="border-b border-slate-50 transition-colors">
            <td class="px-5 py-4">
                <p class="font-semibold text-slate-700">${b.asset?.name ?? '-'}</p>
                <p class="text-xs text-slate-400 mt-0.5">Qty: ${b.qty ?? 1}</p>
            </td>
            <td class="px-5 py-4">
                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                    <span class="bg-slate-100 px-2 py-0.5 rounded">${b.asset?.branch?.name ?? '-'}</span>
                    <i data-feather="arrow-right" class="w-3 h-3 text-slate-300"></i>
                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded">${b.destination_branch?.name ?? '-'}</span>
                </div>
                <p class="text-xs text-slate-400 mt-1">${b.destination_room?.name ?? '-'}</p>
            </td>
            <td class="px-5 py-4 text-xs text-slate-500">
                <p>${formatDate(b.start_date)}</p>
                <p class="text-slate-400">s/d ${formatDate(b.end_date)}</p>
            </td>
            <td class="px-5 py-4 text-xs text-slate-500 max-w-[160px]">
                <p class="truncate">${b.reason ?? '-'}</p>
                ${b.notes ? `<p class="text-slate-400 truncate">${b.notes}</p>` : ''}
            </td>
            <td class="px-5 py-4">${statusBadge(b.status)}</td>
        </tr>
    `).join('');

    feather.replace();
}

async function loadMasterData() {
    const [branches, assets, categories] = await Promise.all([
        fetch('/api/branches', { headers: { Authorization: 'Bearer ' + token } }).then(r => r.json()),
        fetch('/api/assets',   { headers: { Authorization: 'Bearer ' + token } }).then(r => r.json()),
        fetch('/api/asset/categories', { headers: { Authorization: 'Bearer ' + token } }).then(r => r.json()),
    ]);

    allBranches   = Array.isArray(branches)   ? branches   : [];
    allAssets     = Array.isArray(assets)     ? assets     : [];
    allCategories = Array.isArray(categories) ? categories : [];

    // Isi filter asset list
    const branchFilter = document.getElementById('filterAssetBranch');
    branchFilter.innerHTML = '<option value="">Semua Cabang</option>' +
        allBranches.map(b => `<option value="${b.id}">${b.name}</option>`).join('');

    const catFilter = document.getElementById('filterAssetCategory');
    catFilter.innerHTML = '<option value="">Semua Kategori</option>' +
        allCategories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');

    // Isi dropdown form cabang asal & tujuan
    const branchOptions = '<option value="">Pilih Cabang</option>' +
        allBranches.map(b => `<option value="${b.id}">${b.name}</option>`).join('');
    document.getElementById('form_asset_branch').innerHTML = branchOptions;
    document.getElementById('form_dest_branch').innerHTML  =
        '<option value="">Pilih Cabang Tujuan</option>' +
        allBranches.map(b => `<option value="${b.id}">${b.name}</option>`).join('');

    // Isi kategori form
    document.getElementById('form_asset_category').innerHTML =
        '<option value="">Pilih Kategori</option>' +
        allCategories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
}

/* ══════════════════════════════════════
   MODAL DAFTAR ASET
══════════════════════════════════════ */
function openAssetListModal() {
    document.getElementById('assetListModal').classList.remove('hidden');
    document.getElementById('assetListModal').classList.add('flex');
    renderAssetList(allAssets);
    feather.replace();
}

function closeAssetListModal() {
    document.getElementById('assetListModal').classList.add('hidden');
    document.getElementById('assetListModal').classList.remove('flex');
}

function filterAssets() {
    const branchId   = document.getElementById('filterAssetBranch').value;
    const categoryId = document.getElementById('filterAssetCategory').value;
    const condition  = document.getElementById('filterAssetCondition').value;

    let filtered = allAssets;
    if (branchId)   filtered = filtered.filter(a => String(a.branch_id) === branchId);
    if (categoryId) filtered = filtered.filter(a => String(a.category_id) === categoryId);
    if (condition)  filtered = filtered.filter(a => a.condition === condition);

    renderAssetList(filtered);
}

function renderAssetList(data) {
    const tbody = document.getElementById('assetListTable');
    const empty = document.getElementById('assetListEmpty');

    if (!data.length) {
        tbody.innerHTML = '';
        empty.classList.remove('hidden');
        feather.replace();
        return;
    }
    empty.classList.add('hidden');

    tbody.innerHTML = data.map(a => `
        <tr class="border-b border-slate-50 transition-colors">
            <td class="px-5 py-3.5">
                <p class="font-semibold text-slate-700 text-sm">${a.name}</p>
                ${a.brand ? `<p class="text-xs text-slate-400">${a.brand}</p>` : ''}
            </td>
            <td class="px-5 py-3.5 text-sm text-slate-500">${a.branch?.name ?? '-'}</td>
            <td class="px-5 py-3.5 text-sm text-slate-500">${a.room?.name ?? '-'}</td>
            <td class="px-5 py-3.5">
                ${a.category?.name
                    ? `<span class="bg-purple-50 text-purple-700 text-xs px-2 py-0.5 rounded-full">${a.category.name}</span>`
                    : '<span class="text-slate-300 text-xs">-</span>'}
            </td>
            <td class="px-5 py-3.5 text-center">
                <span class="bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold px-2 py-0.5 rounded-lg">${a.quantity ?? 1}</span>
            </td>
            <td class="px-5 py-3.5">${conditionBadge(a.condition)}</td>
        </tr>
    `).join('');

    feather.replace();
}

/* ══════════════════════════════════════
   MODAL AJUKAN PEMINJAMAN — CASCADING DROPDOWN
══════════════════════════════════════ */
function openBorrowModal() {
    resetBorrowForm();
    document.getElementById('borrowModal').classList.remove('hidden');
    document.getElementById('borrowModal').classList.add('flex');
    feather.replace();
}

function closeBorrowModal() {
    document.getElementById('borrowModal').classList.add('hidden');
    document.getElementById('borrowModal').classList.remove('flex');
}

function resetBorrowForm() {
    ['form_asset_branch', 'form_asset_category', 'form_asset_id', 'form_dest_branch', 'form_dest_room'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    ['form_qty', 'form_start_date', 'form_end_date', 'form_reason', 'form_notes'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = id === 'form_qty' ? '1' : '';
    });
    document.getElementById('form_asset_category').disabled = true;
    document.getElementById('form_asset_id').disabled       = true;
    document.getElementById('form_dest_room').disabled      = true;
}

// Step 1: pilih cabang → enable kategori, filter aset
function onFormBranchChange() {
    const branchId = document.getElementById('form_asset_branch').value;
    const catEl    = document.getElementById('form_asset_category');
    const assetEl  = document.getElementById('form_asset_id');

    catEl.disabled   = !branchId;
    assetEl.disabled = true;
    assetEl.innerHTML = '<option value="">Pilih Aset</option>';

    if (!branchId) {
        catEl.value = '';
        return;
    }
    catEl.disabled = false;
}

// Step 2: pilih kategori → filter aset berdasarkan cabang + kategori
function onFormCategoryChange() {
    const branchId   = document.getElementById('form_asset_branch').value;
    const categoryId = document.getElementById('form_asset_category').value;
    const assetEl    = document.getElementById('form_asset_id');

    if (!categoryId) {
        assetEl.disabled = true;
        assetEl.innerHTML = '<option value="">Pilih Aset</option>';
        return;
    }

    const filtered = allAssets.filter(a =>
        String(a.branch_id) === branchId &&
        String(a.category_id) === categoryId
    );

    assetEl.innerHTML = '<option value="">Pilih Aset</option>' +
        filtered.map(a => `<option value="${a.id}">${a.name} (Qty: ${a.quantity ?? 1})</option>`).join('');
    assetEl.disabled = filtered.length === 0;
}

// Step 3: pilih cabang tujuan → load ruangan
async function onFormDestBranchChange() {
    const branchId = document.getElementById('form_dest_branch').value;
    const roomEl   = document.getElementById('form_dest_room');

    roomEl.disabled = true;
    roomEl.innerHTML = '<option value="">Pilih Ruangan</option>';

    if (!branchId) return;

    const res   = await fetch('/api/rooms?branch_id=' + branchId, { headers: { Authorization: 'Bearer ' + token } });
    const rooms = await res.json();

    if (Array.isArray(rooms) && rooms.length) {
        roomEl.innerHTML = '<option value="">Pilih Ruangan</option>' +
            rooms.map(r => `<option value="${r.id}">${r.name}</option>`).join('');
        roomEl.disabled = false;
    }
}

async function submitBorrow() {
    const asset_id          = document.getElementById('form_asset_id').value;
    const qty               = document.getElementById('form_qty').value;
    const dest_branch_id    = document.getElementById('form_dest_branch').value;
    const dest_room_id      = document.getElementById('form_dest_room').value;
    const start_date        = document.getElementById('form_start_date').value;
    const end_date          = document.getElementById('form_end_date').value;
    const reason            = document.getElementById('form_reason').value.trim();
    const notes             = document.getElementById('form_notes').value.trim();

    if (!asset_id)       return alert('Pilih aset terlebih dahulu!');
    if (!qty || qty < 1) return alert('Jumlah harus diisi!');
    if (!dest_branch_id) return alert('Pilih cabang tujuan!');
    if (!start_date)     return alert('Tanggal mulai pinjam wajib diisi!');
    if (!end_date)       return alert('Tanggal pengembalian wajib diisi!');
    if (!reason)         return alert('Alasan peminjaman wajib diisi!');

    const res = await fetch('/api/borrowings', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify({
            asset_id,
            qty,
            destination_branch_id: dest_branch_id,
            destination_room_id:   dest_room_id || null,
            start_date,
            end_date,
            reason,
            notes: notes || null,
        })
    });

    const data = await res.json();
    if (!res.ok) return alert(data.message ?? 'Gagal mengajukan peminjaman!');

    alert('Pengajuan berhasil dikirim! Menunggu persetujuan Admin GA.');
    closeBorrowModal();
    loadBorrowings();
}

function goTo(url) { window.location.href = url; }

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadMasterData();
    loadBorrowings();
});
</script>

</body>
</html>