<!DOCTYPE html>
<html>
<head>
    <title>Tugas Maintenance</title>
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
                <h1 class="text-xl font-bold">Tugas Maintenance</h1>
                <p class="text-sm text-gray-500 mt-0.5">Konfirmasi & selesaikan pekerjaan kamu</p>
            </div>
            <span id="userInfo" class="text-sm text-gray-500"></span>
        </div>

        <!-- PROFILE CARD -->
        <div id="profileCard" class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-3 mb-6">
            <div id="avatarCircle"
                class="w-11 h-11 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-sm font-semibold shrink-0">
            </div>
            <div class="flex-1 min-w-0">
                <p id="profileName" class="text-sm font-semibold text-gray-800"></p>
                <p id="profileRole" class="text-xs text-gray-400">Teknisi</p>
            </div>
            <div class="text-right shrink-0">
                <p class="text-xs text-gray-400">Tugas bulan ini</p>
                <p id="statTotal" class="text-xl font-semibold text-gray-800">0</p>
            </div>
        </div>

        <!-- TABS -->
        <div class="flex gap-1 bg-gray-100 rounded-xl p-1 mb-5">
            <button id="tabBtnPending"
                onclick="switchTab('pending')"
                class="flex-1 text-sm py-2 rounded-lg font-medium transition tab-btn active-tab">
                Perlu Dikonfirmasi
                <span id="badgePending" class="ml-1 text-xs bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded-full"></span>
            </button>
            <button id="tabBtnOngoing"
                onclick="switchTab('ongoing')"
                class="flex-1 text-sm py-2 rounded-lg font-medium transition tab-btn">
                Sedang Berjalan
                <span id="badgeOngoing" class="ml-1 text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full"></span>
            </button>
            <button id="tabBtnDone"
                onclick="switchTab('done')"
                class="flex-1 text-sm py-2 rounded-lg font-medium transition tab-btn">
                Selesai
                <span id="badgeDone" class="ml-1 text-xs bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full"></span>
            </button>
        </div>

        <!-- LIST -->
        <div id="taskList" class="space-y-3"></div>

    </div>
</div>

<!-- ===================== MODAL: TANDAI SELESAI ===================== -->
<div id="doneModal"
     class="fixed inset-0 bg-black bg-opacity-50 z-50"
     style="display: none; align-items: center; justify-content: center;">

    <div class="bg-white rounded-2xl w-full max-w-md mx-4 p-6 shadow-xl">
        <h2 class="font-semibold text-base mb-4">Laporkan Penyelesaian</h2>

        <div class="space-y-3">
            <div>
                <label class="text-xs text-gray-500 block mb-1">Catatan hasil pekerjaan</label>
                <textarea id="doneNote" rows="3"
                    placeholder="Deskripsikan apa yang sudah dikerjakan..."
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 resize-none"></textarea>
            </div>

            <div>
                <label class="text-xs text-gray-500 block mb-1">Foto dokumentasi</label>
                <label class="flex items-center gap-2 border border-dashed border-gray-300 rounded-lg px-3 py-3 cursor-pointer hover:bg-gray-50 transition">
                    <i data-feather="upload" class="w-4 h-4 text-gray-400"></i>
                    <span id="photoLabel" class="text-sm text-gray-400">Pilih foto...</span>
                    <input type="file" id="donePhoto" accept="image/*" class="hidden" onchange="updatePhotoLabel(this)" />
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-5">
            <button onclick="closeDoneModal()"
                class="text-sm px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                Batal
            </button>
            <button onclick="submitDone()"
                class="text-sm bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition">
                Tandai Selesai
            </button>
        </div>
    </div>
</div>

<style>
    .tab-btn { color: #6b7280; background: transparent; }
    .active-tab { background: white; color: #111827; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
</style>

<script>
const token = localStorage.getItem('token');
const user  = JSON.parse(localStorage.getItem('user'));
const initials = name => name ? name.split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase() : '??';

if (!user) { window.location.href = '/login'; }

document.getElementById('userInfo').innerText = user.name + ' (' + user.role + ')';

let currentTab       = 'pending';
let selectedId       = null;
let allTasks         = [];

const PERIOD_MAP = {
    weekly:    'Mingguan',
    monthly:   'Bulanan',
    quarterly: 'Triwulan',
    yearly:    'Tahunan',
};

/* ─── INIT ─── */
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    renderProfile();
    loadTasks();
});

/* ─── PROFILE ─── */
function renderProfile() {
    document.getElementById('profileName').innerText = user.name;
    document.getElementById('avatarCircle').innerText = initials(user.name);
}

/* ─── LOAD TASKS ─── */
async function loadTasks() {
    const res  = await fetch('/api/scheduled-maintenances/my-tasks', {
        headers: { 'Authorization': 'Bearer ' + token }
    });
    allTasks = await res.json();

    updateBadges();
    renderList();
}

/* ─── UPDATE BADGE COUNT ─── */
function updateBadges() {
    const pending = allTasks.filter(t => t.status === 'pending').length;
    const ongoing = allTasks.filter(t => ['confirmed','in_progress'].includes(t.status)).length;
    const done    = allTasks.filter(t => t.status === 'done').length;

    document.getElementById('statTotal').innerText   = allTasks.length;
    document.getElementById('badgePending').innerText = pending || '';
    document.getElementById('badgeOngoing').innerText = ongoing || '';
    document.getElementById('badgeDone').innerText    = done    || '';
}

/* ─── SWITCH TAB ─── */
function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active-tab'));
    document.getElementById('tabBtn' + tab.charAt(0).toUpperCase() + tab.slice(1))
        .classList.add('active-tab');
    renderList();
}

/* ─── RENDER LIST ─── */
function renderList() {
    const filtered = allTasks.filter(t => {
        if (currentTab === 'pending') return t.status === 'pending';
        if (currentTab === 'ongoing') return ['confirmed','in_progress'].includes(t.status);
        if (currentTab === 'done')    return t.status === 'done';
        return true;
    });

    const container = document.getElementById('taskList');
    container.innerHTML = '';

    if (!filtered.length) {
        container.innerHTML = `<p class="text-sm text-gray-400 text-center py-8">Tidak ada tugas di sini.</p>`;
        return;
    }

    filtered.forEach(item => {
        const period = PERIOD_MAP[item.period] || item.period;

        if (currentTab === 'pending') {
            container.innerHTML += cardPending(item, period);
        } else if (currentTab === 'ongoing') {
            container.innerHTML += cardOngoing(item, period);
        } else {
            container.innerHTML += cardDone(item, period);
        }
    });

    feather.replace();
}

/* ─── CARD: PENDING ─── */
function cardPending(item, period) {
    return `
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex justify-between items-start gap-3 mb-3">
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm text-gray-800 truncate">${item.title}</p>
                <p class="text-xs text-gray-400 mt-0.5">${item.category} · ${period}</p>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 font-medium shrink-0">
                Menunggu konfirmasi
            </span>
        </div>

        <div class="bg-gray-50 rounded-lg p-3 mb-3 space-y-2">
            <div>
                <p class="text-xs text-gray-400">Jadwal pelaksanaan</p>
                <p class="text-sm font-semibold text-gray-800 mt-0.5">${formatDate(item.scheduled_date)}</p>
            </div>
            ${item.note ? `
            <div>
                <p class="text-xs text-gray-400">Instruksi dari Admin GA</p>
                <p class="text-xs text-gray-700 mt-0.5">${item.note}</p>
            </div>` : ''}
        </div>

        <button onclick="confirmSchedule(${item.id})"
            class="w-full text-sm bg-gray-900 text-white py-2 rounded-lg hover:bg-gray-700 transition font-medium">
            Konfirmasi Jadwal
        </button>
    </div>`;
}

/* ─── CARD: ONGOING ─── */
function cardOngoing(item, period) {
    return `
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex justify-between items-start gap-3 mb-3">
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm text-gray-800 truncate">${item.title}</p>
                <p class="text-xs text-gray-400 mt-0.5">${item.category} · ${period} · ${formatDate(item.scheduled_date)}</p>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-medium shrink-0">
                Berjalan
            </span>
        </div>

        ${item.note ? `
        <div class="bg-gray-50 rounded-lg p-3 mb-3">
            <p class="text-xs text-gray-400 mb-0.5">Instruksi</p>
            <p class="text-xs text-gray-700">${item.note}</p>
        </div>` : ''}

        <div class="border-t border-gray-100 pt-3 mt-1">
            <p class="text-xs font-medium text-gray-600 mb-2">Laporkan penyelesaian</p>
            <button onclick="openDoneModal(${item.id})"
                class="w-full text-sm bg-green-700 text-white py-2 rounded-lg hover:bg-green-800 transition font-medium">
                Tandai Selesai
            </button>
        </div>
    </div>`;
}

/* ─── CARD: DONE ─── */
function cardDone(item, period) {
    return `
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex justify-between items-start gap-3 mb-3">
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm text-gray-800 truncate">${item.title}</p>
                <p class="text-xs text-gray-400 mt-0.5">${item.category} · ${period}</p>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium shrink-0">
                Selesai
            </span>
        </div>

        <div class="bg-green-50 rounded-lg p-3 space-y-1">
            <p class="text-xs font-medium text-green-800">Selesai pada ${formatDate(item.completed_at)}</p>
            ${item.completion_note ? `<p class="text-xs text-green-700">${item.completion_note}</p>` : ''}
        </div>

        ${item.completion_photo ? `
        <img src="/storage/${item.completion_photo}"
            class="w-full rounded-lg border border-gray-100 mt-3 object-cover max-h-40" />
        ` : ''}
    </div>`;
}

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
async function confirmSchedule(id) {
    if (!confirm('Konfirmasi bahwa kamu siap melaksanakan jadwal ini?')) return;

    await fetch(`/api/scheduled-maintenances/${id}/confirm`, {
        method: 'PUT',
        headers: { 'Authorization': 'Bearer ' + token }
    });

    alert('Jadwal dikonfirmasi. Status pekerjaan berubah jadi Sedang Berjalan.');
    loadTasks();
}

/* ─── MODAL: TANDAI SELESAI ─── */
function openDoneModal(id) {
    selectedId = id;
    document.getElementById('doneNote').value  = '';
    document.getElementById('photoLabel').innerText = 'Pilih foto...';
    document.getElementById('doneModal').style.display = 'flex';
}
function closeDoneModal() {
    document.getElementById('doneModal').style.display = 'none';
    selectedId = null;
}
function updatePhotoLabel(input) {
    document.getElementById('photoLabel').innerText =
        input.files.length ? input.files[0].name : 'Pilih foto...';
}

/* ─── SUBMIT DONE (step 4 alur) ─── */
async function submitDone() {
    const note  = document.getElementById('doneNote').value;
    const photo = document.getElementById('donePhoto').files[0];

    if (!note) {
        alert('Catatan hasil pekerjaan wajib diisi!');
        return;
    }

    const formData = new FormData();
    formData.append('completion_note', note);
    if (photo) formData.append('completion_photo', photo);

    await fetch(`/api/scheduled-maintenances/${selectedId}/complete`, {
        method:  'POST',
        headers: { 'Authorization': 'Bearer ' + token },
        body:    formData,
    });

    alert('Pekerjaan berhasil dilaporkan selesai!');
    closeDoneModal();
    loadTasks();
}

/* ─── UTILS ─── */
function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });

     await loadCategories();
    await loadWorkers();
    await loadSchedules();
}
</script>

</body>
</html>