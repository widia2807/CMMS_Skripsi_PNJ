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

            <input type="month" id="filterMonth" onchange="loadSchedules()"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none" />
        </div>

        <!-- LIST -->
        <div id="scheduleList" class="space-y-3"></div>

    </div>
</div>

<!-- ===================== MODAL BUAT JADWAL ===================== -->
<div id="createModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden z-50"
     style="display: none; align-items: center; justify-content: center;">

    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 p-6 shadow-xl">
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
                    <select id="formCategory"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none">
                        <option value="mekanikal">Mekanikal</option>
                        <option value="elektrikal">Elektrikal</option>
                        <option value="sipil">Sipil / Bangunan</option>
                        <option value="kebersihan">Kebersihan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
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
                    <!-- Diisi dari API -->
                </select>
            </div>

            <div>
                <label class="text-xs text-gray-500 block mb-1">Catatan / Instruksi</label>
                <textarea id="formNote" rows="3"
                    placeholder="Tambahkan instruksi khusus untuk tukang..."
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 resize-none"></textarea>
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
<div id="reassignModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden z-50"
     style="display: none; align-items: center; justify-content: center;">

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
                class="text-sm px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                Batal
            </button>
            <button onclick="confirmReassign()"
                class="text-sm bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                Simpan
            </button>
        </div>
    </div>
</div>

<!-- ===================== MODAL LAPORAN SELESAI ===================== -->
<div id="reportModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden z-50"
     style="display: none; align-items: center; justify-content: center;">

    <div class="bg-white rounded-2xl w-full max-w-sm mx-4 p-6 shadow-xl">
        <h2 class="font-semibold text-base mb-4">Laporan Penyelesaian</h2>
        <div id="reportContent" class="text-sm text-gray-600 space-y-2"></div>

        <div class="flex justify-end mt-5">
            <button onclick="closeReportModal()"
                class="text-sm px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
const token   = localStorage.getItem('token');
const user    = JSON.parse(localStorage.getItem('user'));
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

async function loadCategories() {
    const res = await fetch('/api/categories');
    const data = await res.json();

    const el = document.getElementById('formCategory');
    el.innerHTML = '';

    data.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.name;
        el.appendChild(opt);
    });
}
async function loadWorkers() {
    const res  = await fetch('/api/workers', { headers: { 'Authorization': 'Bearer ' + token } });
    workers    = await res.json();

    ['formWorker', 'filterWorker', 'reassignWorker'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        if (id === 'filterWorker') {
            el.innerHTML = '<option value="">Semua tukang</option>';
        } else {
            el.innerHTML = '';
        }
        workers.forEach((w, i) => {
            const opt = document.createElement('option');
            opt.value = w.id;
            opt.textContent = w.name;
            el.appendChild(opt);
        });
    });
}

/* ─── LOAD JADWAL ─── */
async function loadSchedules() {
    const status = document.getElementById('filterStatus').value;
    const worker = document.getElementById('filterWorker').value;
    const month  = document.getElementById('filterMonth').value;

    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (worker) params.append('worker_id', worker);
    if (month)  params.append('month', month);

    const res  = await fetch('/api/scheduled-maintenances?' + params.toString(), {
        headers: { 'Authorization': 'Bearer ' + token }
    });
    const data = await res.json();

    updateStats(data);
    renderList(data);
}

/* ─── UPDATE STAT CARDS ─── */
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

        const actionBtn = item.status === 'done'
            ? `<button onclick="openReport(${item.id})"
                  class="text-xs px-3 py-1 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition">
                  Lihat laporan
               </button>`
            : `<button onclick="openReassign(${item.id})"
                  class="text-xs px-3 py-1 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                  Ubah tukang
               </button>`;

        const confirmNote = item.worker_confirmed_at
            ? `<p class="text-xs text-gray-400 mt-1">Dikonfirmasi tukang: ${formatDate(item.worker_confirmed_at)}</p>`
            : '';

        const doneNote = item.completed_at
            ? `<p class="text-xs text-gray-400 mt-1">Selesai: ${formatDate(item.completed_at)}</p>`
            : '';

        container.innerHTML += `
            <div class="bg-white rounded-xl shadow-sm p-4">
                <div class="flex justify-between items-start gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm text-gray-800 truncate">${item.title}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            ${item.category} &middot; ${period} &middot; ${formatDate(item.scheduled_date)}
                        </p>
                        ${confirmNote}
                        ${doneNote}
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium shrink-0 ${st.cls}">
                        ${st.label}
                    </span>
                </div>

                ${item.note ? `<p class="text-xs text-gray-500 mt-2 border-l-2 border-gray-200 pl-2">${item.note}</p>` : ''}

                <div class="flex justify-between items-center pt-3 mt-3 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-medium ${col.bg} ${col.text}">
                            ${initials(item.worker_name)}
                        </div>
                        <p class="text-xs text-gray-500">${item.worker_name || '-'}</p>
                    </div>
                    ${actionBtn}
                </div>
            </div>
        `;
    });
}

/* ─── FORMAT DATE ─── */
function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

/* ─── MODAL: BUAT JADWAL ─── */
function openCreateModal() {
    document.getElementById('createModal').style.display = 'flex';
}
function closeCreateModal() {
    document.getElementById('createModal').style.display = 'none';
    ['formTitle','formDate','formNote'].forEach(id => document.getElementById(id).value = '');
}
async function createSchedule() {
    const payload = {
        title:     document.getElementById('formTitle').value,
        category:  document.getElementById('formCategory').value,
        scheduled_date: document.getElementById('formDate').value,
        period:    document.getElementById('formPeriod').value,
        worker_id: document.getElementById('formWorker').value,
        note:      document.getElementById('formNote').value,
    };

    if (!payload.title || !payload.scheduled_date || !payload.worker_id) {
        alert('Judul, tanggal, dan tukang wajib diisi!');
        return;
    }

    await fetch('/api/scheduled-maintenances', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
    });

    alert('Jadwal berhasil dibuat. Notifikasi dikirim ke tukang.');
    closeCreateModal();
    loadSchedules();
}

/* ─── MODAL: UBAH TUKANG ─── */
function openReassign(id) {
    selectedScheduleId = id;
    document.getElementById('reassignModal').style.display = 'flex';
}
function closeReassignModal() {
    document.getElementById('reassignModal').style.display = 'none';
    selectedScheduleId = null;
}
async function confirmReassign() {
    const workerId = document.getElementById('reassignWorker').value;
    await fetch(`/api/scheduled-maintenances/${selectedScheduleId}/assign`, {
        method: 'PUT',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ worker_id: workerId }),
    });

    alert('Penugasan tukang berhasil diperbarui.');
    closeReassignModal();
    loadSchedules();
}

/* ─── MODAL: LAPORAN SELESAI ─── */
async function openReport(id) {
    const res  = await fetch(`/api/scheduled-maintenances/${id}`, {
        headers: { 'Authorization': 'Bearer ' + token }
    });
    const item = await res.json();

    document.getElementById('reportContent').innerHTML = `
        <div class="space-y-2">
            <p><span class="text-gray-400">Pekerjaan:</span> ${item.title}</p>
            <p><span class="text-gray-400">Tukang:</span> ${item.worker_name}</p>
            <p><span class="text-gray-400">Selesai pada:</span> ${formatDate(item.completed_at)}</p>
            ${item.completion_note ? `<p><span class="text-gray-400">Catatan tukang:</span> ${item.completion_note}</p>` : ''}
            ${item.completion_photo ? `<img src="/storage/${item.completion_photo}" class="w-full rounded-lg border mt-2" />` : ''}
        </div>
    `;
    document.getElementById('reportModal').style.display = 'flex';
}
function closeReportModal() {
    document.getElementById('reportModal').style.display = 'none';
}

function goTo(url) {
    window.location.href = url;
}
</script>

</body>
</html>