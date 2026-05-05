<!DOCTYPE html>
<html>
<head>
    <title>Pekerjaan Tukang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .card { transition: box-shadow 0.2s, transform 0.2s; }
        .card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-1px); }

        .btn { transition: all 0.15s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }

        .modal-box { animation: modalIn 0.2s ease; }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.96) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        input:focus, select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">

@include('components.sidebar')

<div class="flex min-h-screen">
<div class="flex-1 md:ml-64">

<!-- TOPBAR -->
<div class="bg-white border-b border-slate-100 px-8 py-4 flex justify-between items-center sticky top-0 z-30">
    <div>
        <h1 class="font-bold text-slate-800 text-lg">Pekerjaan Saya</h1>
        <p class="text-xs text-slate-400 mt-0.5">Daftar pekerjaan yang ditugaskan</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
            <i data-feather="user" class="w-4 h-4 text-blue-600"></i>
        </div>
        <span class="text-sm font-medium text-slate-600">Teknisi</span>
    </div>
</div>

<div class="p-8">

<!-- FILTER TABS -->
<div class="flex gap-2 mb-6 flex-wrap">
    <button onclick="filterJobs('all')" id="tab-all" class="tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-slate-800 text-white">
        Semua
    </button>
    <button onclick="filterJobs('approved')" id="tab-approved" class="tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-white text-slate-500 border border-slate-200">
        Disetujui
    </button>
    <button onclick="filterJobs('scheduled')" id="tab-scheduled" class="tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-white text-slate-500 border border-slate-200">
        Terjadwal
    </button>
    <button onclick="filterJobs('on_progress')" id="tab-on_progress" class="tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-white text-slate-500 border border-slate-200">
        Sedang Dikerjakan
    </button>
    <button onclick="filterJobs('done')" id="tab-done" class="tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-white text-slate-500 border border-slate-200">
        Selesai
    </button>
</div>

<!-- JOB GRID -->
<div id="jobList" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4"></div>

<!-- EMPTY STATE -->
<div id="emptyState" class="hidden text-center py-20">
    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <i data-feather="briefcase" class="w-8 h-8 text-slate-400"></i>
    </div>
    <p class="font-semibold text-slate-500">Tidak ada pekerjaan</p>
    <p class="text-slate-400 text-sm mt-1">Belum ada pekerjaan yang ditugaskan</p>
</div>

</div>
</div>
</div>

<!-- DETAIL MODAL -->
<div id="detailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl modal-box max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Detail Pekerjaan</h3>
            <button onclick="closeDetail()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div id="detailContent" class="p-6 space-y-3"></div>
    </div>
</div>

<!-- IMAGE MODAL -->
<div id="imageModal" class="fixed inset-0 bg-black/90 hidden items-center justify-center z-50">
    <img id="modalImage" class="max-w-[90%] max-h-[85vh] rounded-xl shadow-2xl object-contain">
    <button onclick="closeImage()" class="absolute top-5 right-5 w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-colors">
        <i data-feather="x" class="w-5 h-5"></i>
    </button>
</div>

<!-- MATERIAL MODAL -->
<div id="materialModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl modal-box max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-800">Ajukan Material</h3>
                <p class="text-xs text-slate-400 mt-0.5">Isi kebutuhan material pekerjaan</p>
            </div>
            <button onclick="closeMaterialModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="p-6">
            <div id="materialList" class="space-y-2">
                <div class="flex gap-2 items-center">
                    <input type="text" placeholder="Nama barang" class="border border-slate-200 rounded-lg p-2 w-full text-sm name">
                    <input type="number" placeholder="Qty" class="border border-slate-200 rounded-lg p-2 w-20 text-sm qty">
                    <select class="border border-slate-200 rounded-lg p-2 text-sm unit">
                        <option value="pcs">pcs</option>
                        <option value="meter">meter</option>
                        <option value="kg">kg</option>
                    </select>
                </div>
            </div>

            <button onclick="addMaterialField()" class="flex items-center gap-1.5 text-indigo-600 text-sm font-medium mt-3 hover:text-indigo-700">
                <i data-feather="plus-circle" class="w-4 h-4"></i> Tambah item
            </button>

            <div class="flex gap-2 mt-5">
                <button onclick="closeMaterialModal()" class="btn flex-1 border border-slate-200 text-slate-500 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-50">
                    Batal
                </button>
                <button onclick="submitMaterial()" class="btn flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-lg text-sm font-semibold">
                    Submit Material
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const token = localStorage.getItem('token');
if (!token) window.location.href = '/login';

let selectedId = null;
let currentFilter = 'all';

const statusLabel = {
    'approved': 'Disetujui',
    'scheduled': 'Terjadwal',
    'waiting_material': 'Menunggu Material',
    'on_progress': 'Sedang Dikerjakan',
    'done': 'Selesai',
    'material_ready': 'Material Siap',
    'verified': 'Terverifikasi'
};

const statusStyle = {
    'approved':         'bg-green-100 text-green-700',
    'scheduled':        'bg-blue-100 text-blue-700',
    'waiting_material': 'bg-orange-100 text-orange-700',
    'material_ready':   'bg-cyan-100 text-cyan-700',
    'on_progress':      'bg-purple-100 text-purple-700',
    'done':             'bg-slate-100 text-slate-500',
    'verified':         'bg-teal-100 text-teal-700',
};

function filterJobs(status) {
    currentFilter = status;
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.className = 'tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-white text-slate-500 border border-slate-200';
    });
    document.getElementById('tab-' + status).className = 'tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-slate-800 text-white';
    renderJobs(window.jobData || []);
}

function renderJobs(data) {
    const filtered = currentFilter === 'all' ? data : data.filter(j => j.status === currentFilter);
    const list = document.getElementById('jobList');
    const empty = document.getElementById('emptyState');

    if (!filtered.length) {
        list.innerHTML = '';
        empty.classList.remove('hidden');
        feather.replace();
        return;
    }
    empty.classList.add('hidden');

    list.innerHTML = filtered.map(job => {
        let action = '';

        if (job.status === 'approved') {
            action = `
                <div class="flex gap-2 items-center">
                    <input type="date" id="date-${job.id}" class="border border-slate-200 rounded-lg px-2 py-1.5 text-xs flex-1">
                    <button onclick="setSchedule(${job.id})" class="btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap">
                        Jadwalkan
                    </button>
                </div>`;
        } else if (job.status === 'scheduled') {
            action = `
                <div class="flex gap-2 flex-wrap">
                    <button onclick="inspectJob(${job.id}, false)" class="btn bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                        <i data-feather="play" class="w-3 h-3"></i> Langsung Kerja
                    </button>
                    <button onclick="needMaterial(${job.id})" class="btn bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                        <i data-feather="package" class="w-3 h-3"></i> Butuh Material
                    </button>
                </div>`;
        } else if (job.status === 'waiting_material') {
            action = `
                <div class="space-y-2">
                    <div class="flex gap-2">
                        <button onclick="openMaterialForm(${job.id})" class="btn bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                            <i data-feather="clipboard" class="w-3 h-3"></i> Ajukan Material
                        </button>
                        <button onclick="startJob(${job.id})" class="btn bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                            <i data-feather="play" class="w-3 h-3"></i> Mulai
                        </button>
                    </div>
                    <div class="flex gap-2 items-center">
                        <input type="date" id="res-${job.id}" class="border border-slate-200 rounded-lg px-2 py-1.5 text-xs flex-1">
                        <button onclick="reschedule(${job.id})" class="btn bg-slate-500 hover:bg-slate-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap">
                            Reschedule
                        </button>
                    </div>
                </div>`;
        } else if (job.status === 'material_ready') {
            action = `
                <div class="flex gap-2">
                    <button onclick="openMaterialForm(${job.id})" class="btn bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                        <i data-feather="clipboard" class="w-3 h-3"></i> Ajukan Material
                    </button>
                    <button onclick="startJob(${job.id})" class="btn bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                        <i data-feather="play" class="w-3 h-3"></i> Mulai Kerja
                    </button>
                </div>`;
        } else if (job.status === 'on_progress') {
            action = `
                <button onclick="completeJob(${job.id})" class="btn bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                    <i data-feather="check-circle" class="w-3 h-3"></i> Tandai Selesai
                </button>`;
        }

        return `
        <div class="card bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            ${job.photo ? `
                <div class="relative h-36 overflow-hidden cursor-pointer" onclick="openImage('/storage/${job.photo}')">
                    <img src="/storage/${job.photo}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>
            ` : `<div class="h-2 bg-gradient-to-r from-slate-200 to-slate-100"></div>`}

            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-slate-800 text-sm leading-tight flex-1">${job.title ?? '-'}</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold ml-2 whitespace-nowrap ${statusStyle[job.status] ?? 'bg-slate-100 text-slate-500'}">
                        ${statusLabel[job.status] ?? job.status}
                    </span>
                </div>

                <div class="flex flex-col gap-1 mb-3">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <i data-feather="map-pin" class="w-3 h-3"></i>
                        <span>${job.branch ?? '-'}</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <i data-feather="tag" class="w-3 h-3"></i>
                        <span>${job.category ?? '-'}</span>
                    </div>
                </div>

                <div class="border-t border-slate-50 pt-3 space-y-2">
                    ${action}
                    <button onclick="openDetail(${job.id})" class="btn w-full border border-slate-200 text-slate-600 hover:bg-slate-50 px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center justify-center gap-1">
                        <i data-feather="info" class="w-3 h-3"></i> Lihat Detail
                    </button>
                </div>
            </div>
        </div>`;
    }).join('');

    feather.replace();
}

async function loadJobs() {
    try {
        const res = await fetch('/api/technician/jobs', { headers: { 'Authorization': 'Bearer ' + token } });
        const data = await res.json();
        if (!res.ok) { alert(data.message || 'Gagal load data'); return; }
        window.jobData = data;
        renderJobs(data);
    } catch (err) {
        console.error(err);
        alert('Terjadi error');
    }
}

async function needMaterial(id) {
    const res = await fetch(`/api/technician/inspect/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ needs_material: true, notes: 'Butuh material' })
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    openMaterialForm(id);
}

function openDetail(id) {
    const job = window.jobData.find(j => j.id === id);
    if (!job) return;
    document.getElementById('detailContent').innerHTML = `
        <div class="flex justify-between items-start">
            <h4 class="font-bold text-slate-800">${job.title}</h4>
            <span class="text-xs px-2.5 py-1 rounded-full font-semibold ${statusStyle[job.status] ?? ''}">${statusLabel[job.status] ?? job.status}</span>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <i data-feather="map-pin" class="w-4 h-4"></i> ${job.branch}
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <i data-feather="tag" class="w-4 h-4"></i> ${job.category}
        </div>
        <p class="text-sm text-slate-600 bg-slate-50 rounded-lg p-3">${job.description ?? '-'}</p>
        ${job.photo ? `
            <img src="/storage/${job.photo}" onclick="openImage('/storage/${job.photo}')"
                class="w-full h-44 object-cover rounded-xl cursor-pointer hover:opacity-90 transition-opacity">
        ` : ''}
    `;
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
    feather.replace();
}

function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
}

function openImage(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function closeImage() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
}

async function setSchedule(id) {
    const date = document.getElementById(`date-${id}`).value;
    if (!date) return alert('Pilih tanggal dulu!');
    const res = await fetch(`/api/technician/schedule/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ schedule_date: date })
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    alert('Jadwal berhasil diset');
    loadJobs();
}

async function inspectJob(id, needsMaterial) {
    const res = await fetch(`/api/technician/inspect/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ needs_material: needsMaterial, notes: 'Hasil pengecekan' })
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    alert('Inspection selesai');
    loadJobs();
}

async function startJob(id) {
    const res = await fetch(`/api/technician/start/${id}`, { method: 'POST', headers: { 'Authorization': 'Bearer ' + token } });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    alert('Pekerjaan dimulai');
    loadJobs();
}

async function completeJob(id) {
    const res = await fetch(`/api/technician/complete/${id}`, { method: 'POST', headers: { 'Authorization': 'Bearer ' + token } });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    alert('Pekerjaan selesai');
    loadJobs();
}

async function reschedule(id) {
    const date = document.getElementById(`res-${id}`).value;
    if (!date) return alert('Pilih tanggal dulu!');
    const res = await fetch(`/api/technician/schedule/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ schedule_date: date })
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    alert('Reschedule berhasil');
    loadJobs();
}

function openMaterialForm(id) {
    selectedId = id;
    document.getElementById('materialModal').classList.remove('hidden');
    document.getElementById('materialModal').classList.add('flex');
    feather.replace();
}

function addMaterialField() {
    const div = document.createElement('div');
    div.className = 'flex gap-2 items-center';
    div.innerHTML = `
        <input type="text" placeholder="Nama barang" class="border border-slate-200 rounded-lg p-2 w-full text-sm name">
        <input type="number" placeholder="Qty" class="border border-slate-200 rounded-lg p-2 w-20 text-sm qty">
        <select class="border border-slate-200 rounded-lg p-2 text-sm unit">
            <option value="pcs">pcs</option>
            <option value="meter">meter</option>
            <option value="kg">kg</option>
        </select>
    `;
    document.getElementById('materialList').appendChild(div);
}

async function submitMaterial() {
    const names = document.querySelectorAll('.name');
    const qtys = document.querySelectorAll('.qty');
    const units = document.querySelectorAll('.unit');
    let items = [];
    for (let i = 0; i < names.length; i++) {
        if (names[i].value && qtys[i].value) {
            items.push({ name: names[i].value, qty: qtys[i].value, unit: units[i].value });
        }
    }
    if (!items.length) return alert('Isi minimal 1 item');
    const res = await fetch(`/api/technician/material/${selectedId}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ items })
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    alert('Material diajukan');
    closeMaterialModal();
    loadJobs();
}

function closeMaterialModal() {
    document.getElementById('materialModal').classList.remove('flex');
    document.getElementById('materialModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadJobs();
});
</script>

</body>
</html>