<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Terjadwal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100">

@include('components.sidebar')

<div class="flex h-screen">

    <!-- MAIN -->
    <div class="flex-1 md:ml-64 p-6 overflow-y-auto">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-xl font-bold">Maintenance Terjadwal</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola jadwal & penugasan tukang</p>
            </div>
            <div class="flex items-center gap-3">
                <span id="userInfo" class="text-sm text-gray-500"></span>
                <button onclick="openCatModal()"
                    class="text-sm px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition">
                    🗂 Kelola Kategori
                </button>
                <button onclick="openSubCatModal()"
                    class="text-sm px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition">
                    Kelola Sub Kategori
                </button>
                <button onclick="openCreateModal()"
                    class="bg-gray-900 text-white text-sm px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    + Buat Jadwal
                </button>
            </div>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-sm">
                <p class="text-xs text-gray-500 mb-1">Total Jadwal</p>
                <p id="statTotal" class="text-2xl font-semibold text-gray-800">0</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm">
                <p class="text-xs text-gray-500 mb-1">Menunggu Konfirmasi</p>
                <p id="statPending" class="text-2xl font-semibold text-amber-600">0</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm">
                <p class="text-xs text-gray-500 mb-1">Sedang Berjalan</p>
                <p id="statOngoing" class="text-2xl font-semibold text-blue-600">0</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm">
                <p class="text-xs text-gray-500 mb-1">Selesai</p>
                <p id="statDone" class="text-2xl font-semibold text-green-600">0</p>
            </div>
        </div>

        <!-- FILTER -->
        <div class="flex flex-wrap gap-3 mb-4">
            <select id="filterStatus" onchange="loadSchedules()"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua status</option>
                <option value="pending">Menunggu Konfirmasi</option>
                <option value="confirmed">Dikonfirmasi</option>
                <option value="in_progress">Sedang Berjalan</option>
                <option value="done">Selesai</option>
            </select>

            <select id="filterWorker" onchange="loadSchedules()"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua tukang</option>
            </select>

            <select id="filterCategory" onchange="onFilterCategoryChange()"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none">
                <option value="">Semua kategori</option>
            </select>

            <select id="filterSubCategory" onchange="loadSchedules()"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none hidden">
                <option value="">Semua sub kategori</option>
            </select>

            <input type="month" id="filterMonth" onchange="loadSchedules()"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none" />
        </div>

        <!-- LIST -->
        <div id="scheduleList" class="space-y-3"></div>

    </div>
</div>

<!-- ===================== MODAL BUAT JADWAL ===================== -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 z-50"
     style="display:none; align-items:center; justify-content:center;">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 p-6 shadow-xl max-h-[90vh] overflow-y-auto">
        <h2 class="font-semibold text-base mb-4">Buat Jadwal Maintenance</h2>

        <div class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Judul Pekerjaan</label>
                    <input id="formTitle" type="text" placeholder="Cth: Pemeriksaan AC Lantai 3"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300" />
                </div>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Kategori</label>
                    <select id="formCategory" onchange="onFormCategoryChange()"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none">
                        <option value="">Pilih Kategori</option>
                    </select>
                </div>
            </div>

            <div id="formSubCategoryWrapper" class="hidden">
                <label class="text-xs text-gray-500 block mb-1">Sub Kategori</label>
                <select id="formSubCategory"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none">
                    <option value="">Pilih Sub Kategori</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Tanggal Pelaksanaan</label>
                    <input id="formDate" type="date"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300" />
                </div>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Periode</label>
                    <select id="formPeriod"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none">
                        <option value="weekly">Mingguan</option>
                        <option value="monthly">Bulanan</option>
                        <option value="quarterly">Triwulan</option>
                        <option value="yearly">Tahunan</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-xs text-gray-500 block mb-1">Penugasan Tukang</label>
                <select id="formWorker"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none">
                </select>
            </div>

            <div>
                <label class="text-xs text-gray-500 block mb-1">Catatan / Instruksi</label>
                <textarea id="formNote" rows="3" placeholder="Tambahkan instruksi khusus untuk tukang..."
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 resize-none"></textarea>
            </div>

            <!-- TOGGLE AUTOMASI -->
            <div class="flex items-center justify-between bg-blue-50 rounded-xl px-4 py-3 border border-blue-100">
                <div>
                    <p class="text-sm font-medium text-blue-800">🔄 Jadwal Otomatis</p>
                    <p class="text-xs text-blue-500 mt-0.5">Jadwal berikutnya dibuat otomatis setelah selesai</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="formIsAuto" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                        peer-checked:after:translate-x-full peer-checked:after:border-white
                        after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                        after:bg-white after:border-gray-300 after:border after:rounded-full
                        after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-5">
            <button onclick="closeCreateModal()"
                class="text-sm px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                Batal
            </button>
            <button onclick="createSchedule()"
                class="text-sm bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                Simpan & Kirim Notifikasi
            </button>
        </div>
    </div>
</div>

<!-- ===================== MODAL UBAH TUKANG ===================== -->
<div id="reassignModal" class="fixed inset-0 bg-black bg-opacity-50 z-50"
     style="display:none; align-items:center; justify-content:center;">
    <div class="bg-white rounded-2xl w-full max-w-sm mx-4 p-6 shadow-xl">
        <h2 class="font-semibold text-base mb-4">Ubah Penugasan Tukang</h2>
        <div>
            <label class="text-xs text-gray-500 block mb-1">Pilih Tukang</label>
            <select id="reassignWorker"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none">
            </select>
        </div>
        <div class="flex justify-end gap-2 mt-5">
            <button onclick="closeReassignModal()"
                class="text-sm px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Batal</button>
            <button onclick="confirmReassign()"
                class="text-sm bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">Simpan</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL LAPORAN SELESAI ===================== -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50"
     style="display:none; align-items:center; justify-content:center;">
    <div class="bg-white rounded-2xl w-full max-w-sm mx-4 p-6 shadow-xl">
        <h2 class="font-semibold text-base mb-4">Laporan Penyelesaian</h2>
        <div id="reportContent" class="text-sm text-gray-600 space-y-2"></div>
        <div class="flex justify-end mt-5">
            <button onclick="closeReportModal()"
                class="text-sm px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Tutup</button>
        </div>
    </div>
</div>

<!-- ===================== MODAL KELOLA KATEGORI ===================== -->
<div id="catModal" class="fixed inset-0 bg-black bg-opacity-50 z-50"
     style="display:none; align-items:flex-start; justify-content:center; padding-top:60px;">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-xl overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-base">Kelola Kategori</h2>
            <button onclick="closeCatModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-5 max-h-[72vh] overflow-y-auto">
            <!-- Form tambah/edit -->
            <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Tambah / Edit Kategori</p>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Nama Kategori</label>
                    <input id="catFormName" type="text" placeholder="Cth: Mekanikal"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300" />
                </div>
                <div class="flex gap-2">
                    <button id="catCancelEdit" onclick="cancelEditCat()"
                        class="hidden text-sm px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-100 transition">Batal</button>
                    <button id="catFormBtn" onclick="saveCat()"
                        class="flex-1 text-sm bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">Tambah</button>
                </div>
            </div>
            <!-- Daftar kategori -->
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Daftar Kategori</p>
                <div id="catList">
                    <p class="text-xs text-gray-400 text-center py-3">Memuat...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODAL KELOLA SUB KATEGORI ===================== -->
<div id="subCatModal" class="fixed inset-0 bg-black bg-opacity-50 z-50"
     style="display:none; align-items:flex-start; justify-content:center; padding-top:60px;">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-xl overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-base">Kelola Sub Kategori</h2>
            <button onclick="closeSubCatModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-5 max-h-[72vh] overflow-y-auto">
            <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Tambah / Edit Sub Kategori</p>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Kategori Induk</label>
                    <select id="subCatFormCategory" onchange="onSubCatCategoryChange()"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-gray-300">
                        <option value="">Pilih Kategori</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Nama Sub Kategori</label>
                    <input id="subCatFormName" type="text" placeholder="Cth: Servis Berkala AC"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300" />
                </div>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Deskripsi <span class="text-gray-400">(opsional)</span></label>
                    <input id="subCatFormDesc" type="text" placeholder="Cth: Dilakukan setiap 3 bulan sekali"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300" />
                </div>
                <div class="flex gap-2 pt-1">
                    <button id="subCatCancelEdit" onclick="cancelEditSubCat()"
                        class="hidden text-sm px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-100 transition">Batal</button>
                    <button id="subCatFormBtn" onclick="saveSubCat()"
                        class="flex-1 text-sm bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">Tambah</button>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Daftar Sub Kategori</p>
                <div id="subCatList">
                    <p class="text-xs text-gray-400 text-center py-3">Pilih kategori di atas untuk melihat daftarnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const token    = localStorage.getItem('token');
const user     = JSON.parse(localStorage.getItem('user'));
const initials = n => n ? n.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase() : '??';

const AVATAR_COLORS = [
    { bg: 'bg-blue-100',   text: 'text-blue-700' },
    { bg: 'bg-green-100',  text: 'text-green-700' },
    { bg: 'bg-amber-100',  text: 'text-amber-700' },
    { bg: 'bg-pink-100',   text: 'text-pink-700' },
    { bg: 'bg-purple-100', text: 'text-purple-700' },
];

const STATUS_MAP = {
    pending:     { label: 'Menunggu Konfirmasi', cls: 'bg-amber-100 text-amber-700' },
    confirmed:   { label: 'Dikonfirmasi',        cls: 'bg-blue-100 text-blue-700' },
    in_progress: { label: 'Sedang Berjalan',     cls: 'bg-blue-100 text-blue-700' },
    done:        { label: 'Selesai',             cls: 'bg-green-100 text-green-700' },
};

const PERIOD_MAP = {
    weekly:    'Mingguan',
    monthly:   'Bulanan',
    quarterly: 'Triwulan',
    yearly:    'Tahunan',
};

if (!user) { window.location.href = '/login'; }
document.getElementById('userInfo').innerText = user.name + ' (' + user.role + ')';

let selectedScheduleId = null;
let workers = [];

/* ─── INIT ─── */
document.addEventListener('DOMContentLoaded', async () => {
    feather.replace();
    await loadCategories();
    await loadWorkers();
    await loadSchedules();
});

/* ─── LOAD CATEGORIES ─── */
async function loadCategories() {
    const res  = await fetch('/api/categories', { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();
    if (!Array.isArray(data)) return;

    ['formCategory', 'filterCategory', 'subCatFormCategory'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.innerHTML = id === 'filterCategory'
            ? '<option value="">Semua kategori</option>'
            : '<option value="">Pilih Kategori</option>';
        data.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name;
            el.appendChild(opt);
        });
    });
}

/* ─── LOAD SUB CATEGORIES ─── */
async function loadSubCategories(categoryId, targetElId, placeholderText) {
    const el = document.getElementById(targetElId);
    if (!el) return;
    el.innerHTML = `<option value="">${placeholderText}</option>`;
    if (!categoryId) return;

    const res  = await fetch(`/api/scheduled-sub-categories?category_id=${categoryId}`, {
        headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();
    if (!Array.isArray(data) || !data.length) return;
    data.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.name;
        el.appendChild(opt);
    });
}

async function onFormCategoryChange() {
    const catId   = document.getElementById('formCategory').value;
    const wrapper = document.getElementById('formSubCategoryWrapper');
    if (!catId) {
        wrapper.classList.add('hidden');
        document.getElementById('formSubCategory').innerHTML = '<option value="">Pilih Sub Kategori</option>';
        return;
    }
    await loadSubCategories(catId, 'formSubCategory', 'Pilih Sub Kategori');
    wrapper.classList.remove('hidden');
}

async function onFilterCategoryChange() {
    const catId     = document.getElementById('filterCategory').value;
    const filterSub = document.getElementById('filterSubCategory');
    filterSub.innerHTML = '<option value="">Semua sub kategori</option>';
    if (!catId) { filterSub.classList.add('hidden'); loadSchedules(); return; }
    await loadSubCategories(catId, 'filterSubCategory', 'Semua sub kategori');
    filterSub.classList.remove('hidden');
    loadSchedules();
}

/* ─── LOAD WORKERS ─── */
async function loadWorkers() {
    const res = await fetch('/api/workers', { headers: { Authorization: 'Bearer ' + token } });
    workers   = await res.json();
    ['formWorker', 'filterWorker', 'reassignWorker'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.innerHTML = id === 'filterWorker' ? '<option value="">Semua tukang</option>' : '';
        workers.forEach(w => {
            const opt = document.createElement('option');
            opt.value = w.id; opt.textContent = w.name; el.appendChild(opt);
        });
    });
}

/* ─── LOAD JADWAL ─── */
async function loadSchedules() {
    const params = new URLSearchParams();
    const status      = document.getElementById('filterStatus').value;
    const worker      = document.getElementById('filterWorker').value;
    const month       = document.getElementById('filterMonth').value;
    const category    = document.getElementById('filterCategory').value;
    const subCategory = document.getElementById('filterSubCategory').value;
    if (status)      params.append('status', status);
    if (worker)      params.append('worker_id', worker);
    if (month)       params.append('month', month);
    if (category)    params.append('category_id', category);
    if (subCategory) params.append('scheduled_sub_category_id', subCategory);

    const res  = await fetch('/api/scheduled-maintenances?' + params, {
        headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();
    updateStats(data);
    renderList(data);
}

function updateStats(data) {
    document.getElementById('statTotal').innerText   = data.length;
    document.getElementById('statPending').innerText = data.filter(d => d.status === 'pending').length;
    document.getElementById('statOngoing').innerText = data.filter(d => ['confirmed','in_progress'].includes(d.status)).length;
    document.getElementById('statDone').innerText    = data.filter(d => d.status === 'done').length;
}

/* ─── RENDER LIST ─── */
function renderList(data) {
    const container = document.getElementById('scheduleList');
    container.innerHTML = '';
    if (!data.length) {
        container.innerHTML = '<p class="text-gray-400 text-sm">Belum ada jadwal maintenance.</p>';
        return;
    }

    data.forEach((item, i) => {
        const st     = STATUS_MAP[item.status] || { label: item.status, cls: 'bg-gray-100 text-gray-600' };
        const col    = AVATAR_COLORS[i % AVATAR_COLORS.length];
        const period = PERIOD_MAP[item.period] || item.period;

        const subCatBadge = item.sub_category_name
            ? `<span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">${item.sub_category_name}</span>`
            : '';

        // Badge auto/manual
        const autoBadge = item.is_auto
            ? `<span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full border border-blue-100">🔄 Auto</span>`
            : `<span class="text-xs bg-gray-50 text-gray-400 px-2 py-0.5 rounded-full border border-gray-200">Manual</span>`;

        const spkBtn = item.spk_sent_at
            ? `<button onclick="openWorkOrder(${item.wo_id}, 'scheduled')"
                class="text-xs px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                📄 Lihat SPK
              </button>`
            : (item.worker_confirmed_at && !item.spk_sent_at)
            ? `<button onclick="sendScheduledSpk(${item.id})"
                class="text-xs px-3 py-1 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                📤 Kirim SPK
              </button>`
            : item.worker_id && !item.worker_confirmed_at
            ? `<span class="text-xs text-amber-600">⏳ Menunggu konfirmasi tukang</span>`
            : '';

        const actionBtn = item.status === 'done'
            ? `<button onclick="openReport(${item.id})"
                class="text-xs px-3 py-1 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition">
                Lihat laporan
              </button>`
            : `<button onclick="openReassign(${item.id})"
                class="text-xs px-3 py-1 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                Ubah tukang
              </button>`;

        // Toggle auto button
        const toggleAutoBtn = `
            <button onclick="toggleAuto(${item.id}, this)"
                class="text-xs px-3 py-1 rounded-lg border ${item.is_auto ? 'border-blue-200 text-blue-600 bg-blue-50 hover:bg-blue-100' : 'border-gray-200 text-gray-500 hover:bg-gray-50'} transition">
                ${item.is_auto ? '⏹ Matikan Auto' : '▶ Aktifkan Auto'}
            </button>`;

        const confirmNote = item.worker_confirmed_at
            ? `<p class="text-xs text-gray-400 mt-1">Dikonfirmasi tukang: ${formatDate(item.worker_confirmed_at)}</p>` : '';
        const doneNote = item.completed_at
            ? `<p class="text-xs text-gray-400 mt-1">Selesai: ${formatDate(item.completed_at)}</p>` : '';

        container.innerHTML += `
            <div class="bg-white rounded-xl shadow-sm p-4">
                <div class="flex justify-between items-start gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-medium text-sm text-gray-800 truncate">${item.title}</p>
                            ${subCatBadge}
                            ${autoBadge}
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">
                            ${item.category_name ?? '-'} &middot; ${period} &middot; ${formatDate(item.scheduled_date)}
                        </p>
                        ${confirmNote}${doneNote}
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium shrink-0 ${st.cls}">${st.label}</span>
                </div>

                ${item.note ? `<p class="text-xs text-gray-500 mt-2 border-l-2 border-gray-200 pl-2">${item.note}</p>` : ''}

                <div class="flex justify-between items-center pt-3 mt-3 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-medium ${col.bg} ${col.text}">
                            ${initials(item.worker_name)}
                        </div>
                        <p class="text-xs text-gray-500">${item.worker_name || '-'}</p>
                    </div>
                    <div class="flex gap-2 flex-wrap justify-end">
                        ${toggleAutoBtn}
                        ${spkBtn}
                        ${actionBtn}
                    </div>
                </div>
            </div>
        `;
    });
}

/* ─── TOGGLE AUTO ─── */
async function toggleAuto(id, btn) {
    btn.disabled = true;
    const res  = await fetch(`/api/scheduled-maintenances/${id}/toggle-auto`, {
        method: 'PUT',
        headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();
    console.log('toggle response:', data);
    if (!res.ok) { alert(data.message ?? 'Gagal'); btn.disabled = false; return; }
    await loadSchedules(); // refresh list
}

/* ─── FORMAT DATE ─── */
function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

/* ─── MODAL BUAT JADWAL ─── */
function openCreateModal() { document.getElementById('createModal').style.display = 'flex'; }
function closeCreateModal() {
    document.getElementById('createModal').style.display = 'none';
    ['formTitle','formDate','formNote'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('formCategory').value = '';
    document.getElementById('formSubCategory').innerHTML = '<option value="">Pilih Sub Kategori</option>';
    document.getElementById('formSubCategoryWrapper').classList.add('hidden');
    document.getElementById('formIsAuto').checked = false;
}

async function createSchedule() {
    const payload = {
        title:                     document.getElementById('formTitle').value,
        category_id:               document.getElementById('formCategory').value,
        scheduled_sub_category_id: document.getElementById('formSubCategory').value || null,
        scheduled_date:            document.getElementById('formDate').value,
        period:                    document.getElementById('formPeriod').value,
        worker_id:                 document.getElementById('formWorker').value,
        note:                      document.getElementById('formNote').value,
        is_auto:                   document.getElementById('formIsAuto').checked,
    };
    if (!payload.title || !payload.scheduled_date || !payload.worker_id) {
        alert('Judul, tanggal, dan tukang wajib diisi!'); return;
    }
    const res = await fetch('/api/scheduled-maintenances', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    });
    if (!res.ok) { const d = await res.json(); alert(d.message ?? 'Gagal membuat jadwal'); return; }
    alert('Jadwal berhasil dibuat. Notifikasi dikirim ke tukang.');
    closeCreateModal();
    loadSchedules();
}

async function sendScheduledSpk(id) {
    if (!confirm('Kirim SPK ke tukang?')) return;
    const res  = await fetch(`/api/scheduled-maintenances/${id}/send-spk`, {
        method: 'POST', headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();
    if (!res.ok) { alert(data.message ?? 'Gagal kirim SPK'); return; }
    await loadSchedules();
    openWorkOrder(data.wo_id, 'scheduled');
}

function openWorkOrder(id, type) { window.open(`/work-order/${type}/${id}`); }

/* ─── MODAL UBAH TUKANG ─── */
function openReassign(id) { selectedScheduleId = id; document.getElementById('reassignModal').style.display = 'flex'; }
function closeReassignModal() { document.getElementById('reassignModal').style.display = 'none'; selectedScheduleId = null; }
async function confirmReassign() {
    const workerId = document.getElementById('reassignWorker').value;
    await fetch(`/api/scheduled-maintenances/${selectedScheduleId}/assign`, {
        method: 'PUT',
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify({ worker_id: workerId }),
    });
    alert('Penugasan tukang berhasil diperbarui.');
    closeReassignModal(); loadSchedules();
}

/* ─── MODAL LAPORAN ─── */
async function openReport(id) {
    const res  = await fetch(`/api/scheduled-maintenances/${id}`, { headers: { Authorization: 'Bearer ' + token } });
    const item = await res.json();
    const categoryName    = item.category_name ?? '-';
    const subCategoryName = item.sub_category_name ?? '';
    const workerName      = item.worker_name ?? item.worker?.name ?? '-';
    document.getElementById('reportContent').innerHTML = `
        <div class="space-y-2">
            <p><span class="text-gray-400">Pekerjaan:</span> ${item.title ?? '-'}</p>
            <p><span class="text-gray-400">Kategori:</span> ${categoryName}${subCategoryName ? ' / ' + subCategoryName : ''}</p>
            <p><span class="text-gray-400">Tukang:</span> ${workerName}</p>
            <p><span class="text-gray-400">Selesai pada:</span> ${formatDate(item.completed_at)}</p>
            ${item.completion_note ? `<p><span class="text-gray-400">Catatan tukang:</span> ${item.completion_note}</p>` : ''}
            ${item.completion_photo ? `<img src="/storage/${item.completion_photo}" class="w-full rounded-lg border mt-2" />` : ''}
        </div>
    `;
    document.getElementById('reportModal').style.display = 'flex';
}
function closeReportModal() { document.getElementById('reportModal').style.display = 'none'; }

/* ═══════════════════════════════════════════
   KELOLA KATEGORI
═══════════════════════════════════════════ */
let editingCatId = null;

async function openCatModal() {
    document.getElementById('catModal').style.display = 'flex';
    editingCatId = null;
    document.getElementById('catFormName').value = '';
    document.getElementById('catFormBtn').textContent = 'Tambah';
    document.getElementById('catCancelEdit').classList.add('hidden');
    await renderCatList();
}
function closeCatModal() {
    document.getElementById('catModal').style.display = 'none';
    loadCategories(); // refresh semua dropdown setelah tutup
}

async function renderCatList() {
    const res  = await fetch('/api/categories', { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();
    const el   = document.getElementById('catList');
    if (!Array.isArray(data) || !data.length) {
        el.innerHTML = '<p class="text-xs text-gray-400 text-center py-3">Belum ada kategori.</p>';
        return;
    }
    el.innerHTML = data.map(c => `
        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
            <p class="text-sm text-gray-700 font-medium">${c.name}</p>
            <div class="flex gap-2 shrink-0">
                <button onclick="editCat(${c.id}, '${c.name.replace(/'/g, "\\'")}')"
                    class="text-xs px-2 py-1 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">Edit</button>
                <button onclick="deleteCat(${c.id})"
                    class="text-xs px-2 py-1 rounded-lg border border-red-100 text-red-400 hover:bg-red-50">Hapus</button>
            </div>
        </div>
    `).join('');
}

function editCat(id, name) {
    editingCatId = id;
    document.getElementById('catFormName').value = name;
    document.getElementById('catFormBtn').textContent = 'Simpan Perubahan';
    document.getElementById('catCancelEdit').classList.remove('hidden');
    document.getElementById('catFormName').focus();
}
function cancelEditCat() {
    editingCatId = null;
    document.getElementById('catFormName').value = '';
    document.getElementById('catFormBtn').textContent = 'Tambah';
    document.getElementById('catCancelEdit').classList.add('hidden');
}

async function saveCat() {
    const name = document.getElementById('catFormName').value.trim();
    if (!name) { alert('Nama kategori wajib diisi!'); return; }

    const isEdit = !!editingCatId;
    const url    = isEdit ? `/api/categories/${editingCatId}` : '/api/categories';
    const res    = await fetch(url, {
        method: isEdit ? 'PUT' : 'POST',
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify({ name }),
    });
    const data = await res.json();
    if (!res.ok) { alert(data.message ?? 'Gagal menyimpan'); return; }
    cancelEditCat();
    await renderCatList();
}

async function deleteCat(id) {
    if (!confirm('Yakin hapus kategori ini?')) return;
    const res  = await fetch(`/api/categories/${id}`, {
        method: 'DELETE', headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();
    if (!res.ok) { alert(data.message ?? 'Gagal menghapus'); return; }
    await renderCatList();
}

/* ═══════════════════════════════════════════
   KELOLA SUB KATEGORI
═══════════════════════════════════════════ */
let editingSubCatId = null;

async function openSubCatModal() {
    document.getElementById('subCatModal').style.display = 'flex';
    resetSubCatForm();
    await loadCategories(); // pastikan dropdown sub cat terisi
}
function closeSubCatModal() { document.getElementById('subCatModal').style.display = 'none'; resetSubCatForm(); }
function resetSubCatForm() {
    editingSubCatId = null;
    document.getElementById('subCatFormCategory').value  = '';
    document.getElementById('subCatFormName').value      = '';
    document.getElementById('subCatFormDesc').value      = '';
    document.getElementById('subCatFormBtn').textContent = 'Tambah';
    document.getElementById('subCatCancelEdit').classList.add('hidden');
    document.getElementById('subCatList').innerHTML      = '';
}

async function onSubCatCategoryChange() {
    const catId  = document.getElementById('subCatFormCategory').value;
    const listEl = document.getElementById('subCatList');
    listEl.innerHTML = '';
    if (!catId) return;
    const res  = await fetch(`/api/scheduled-sub-categories?category_id=${catId}`, {
        headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();
    if (!Array.isArray(data) || !data.length) {
        listEl.innerHTML = '<p class="text-xs text-gray-400 text-center py-3">Belum ada sub kategori.</p>';
        return;
    }
    data.forEach(s => {
        const row = document.createElement('div');
        row.className = 'flex justify-between items-center py-2 border-b border-gray-100 last:border-0';
        row.innerHTML = `
            <div>
                <p class="text-sm text-gray-700 font-medium">${s.name}</p>
                ${s.description ? `<p class="text-xs text-gray-400">${s.description}</p>` : ''}
            </div>
            <div class="flex gap-2 shrink-0">
                <button onclick="editSubCat(${s.id}, '${s.name.replace(/'/g,"\\'")}', '${(s.description??'').replace(/'/g,"\\'")}', ${s.category_id})"
                    class="text-xs px-2 py-1 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">Edit</button>
                <button onclick="deleteSubCat(${s.id})"
                    class="text-xs px-2 py-1 rounded-lg border border-red-100 text-red-400 hover:bg-red-50">Hapus</button>
            </div>
        `;
        listEl.appendChild(row);
    });
}

function editSubCat(id, name, desc, catId) {
    editingSubCatId = id;
    document.getElementById('subCatFormCategory').value  = catId;
    document.getElementById('subCatFormName').value      = name;
    document.getElementById('subCatFormDesc').value      = desc;
    document.getElementById('subCatFormBtn').textContent = 'Simpan Perubahan';
    document.getElementById('subCatCancelEdit').classList.remove('hidden');
}
function cancelEditSubCat() {
    editingSubCatId = null;
    document.getElementById('subCatFormName').value      = '';
    document.getElementById('subCatFormDesc').value      = '';
    document.getElementById('subCatFormBtn').textContent = 'Tambah';
    document.getElementById('subCatCancelEdit').classList.add('hidden');
}

async function saveSubCat() {
    const catId = document.getElementById('subCatFormCategory').value;
    const name  = document.getElementById('subCatFormName').value.trim();
    const desc  = document.getElementById('subCatFormDesc').value.trim();
    if (!catId || !name) { alert('Kategori dan nama sub kategori wajib diisi!'); return; }

    const isEdit = !!editingSubCatId;
    const url    = isEdit ? `/api/scheduled-sub-categories/${editingSubCatId}` : '/api/scheduled-sub-categories';
    const res    = await fetch(url, {
        method: isEdit ? 'PUT' : 'POST',
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify({ category_id: catId, name, description: desc }),
    });
    const data = await res.json();
    if (!res.ok) { alert(data.message ?? 'Gagal menyimpan'); return; }
    cancelEditSubCat();
    await onSubCatCategoryChange();
}

async function deleteSubCat(id) {
    if (!confirm('Yakin hapus sub kategori ini?')) return;
    await fetch(`/api/scheduled-sub-categories/${id}`, {
        method: 'DELETE', headers: { Authorization: 'Bearer ' + token }
    });
    await onSubCatCategoryChange();
}
</script>

</body>
</html>