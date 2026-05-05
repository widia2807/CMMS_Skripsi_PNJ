<!DOCTYPE html>
<html>
<head>
    <title>Perbaikan Gedung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .card { transition: box-shadow 0.2s, transform 0.2s; }
        .card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.09); transform: translateY(-2px); }

        .btn { transition: all 0.15s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }

        @keyframes scaleIn {
            from { transform: scale(0.95); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }
        .modal-box { animation: scaleIn 0.2s ease; }

        .detail-panel { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }

        input:focus, select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
        <h1 class="font-bold text-slate-800 text-lg">Perbaikan Gedung</h1>
        <p class="text-xs text-slate-400 mt-0.5">Monitoring & approval request</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
            <i data-feather="user" class="w-4 h-4 text-indigo-600"></i>
        </div>
        <span class="text-sm font-medium text-slate-600">Admin</span>
    </div>
</div>

<div class="p-8">

<!-- FILTER + STATS -->
<div class="flex flex-wrap gap-2 mb-6" id="filterTabs">
    <button onclick="filterRequests('all')" id="tab-all" class="tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-slate-800 text-white">
        Semua
    </button>
    <button onclick="filterRequests('pending')" id="tab-pending" class="tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-white text-slate-500 border border-slate-200">
        Pending
    </button>
    <button onclick="filterRequests('approved')" id="tab-approved" class="tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-white text-slate-500 border border-slate-200">
        Disetujui
    </button>
    <button onclick="filterRequests('on_progress')" id="tab-on_progress" class="tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-white text-slate-500 border border-slate-200">
        Dikerjakan
    </button>
    <button onclick="filterRequests('done')" id="tab-done" class="tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-white text-slate-500 border border-slate-200">
        Selesai
    </button>
    <button onclick="filterRequests('rejected')" id="tab-rejected" class="tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-white text-slate-500 border border-slate-200">
        Ditolak
    </button>
</div>

<!-- CARD GRID -->
<div id="requestTable" class="grid md:grid-cols-2 xl:grid-cols-3 gap-4"></div>

<!-- EMPTY STATE -->
<div id="emptyState" class="hidden text-center py-20">
    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <i data-feather="inbox" class="w-8 h-8 text-slate-400"></i>
    </div>
    <p class="font-semibold text-slate-500">Tidak ada request</p>
    <p class="text-slate-400 text-sm mt-1">Belum ada data pada kategori ini</p>
</div>

</div>
</div>
</div>

<!-- DETAIL PANEL (SLIDE FROM RIGHT) -->
<div id="detailPanel"
    class="detail-panel fixed top-0 right-0 w-full sm:w-[440px] h-full bg-white shadow-2xl transform translate-x-full z-50 flex flex-col">

    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
        <h2 class="font-bold text-slate-800">Detail Request</h2>
        <button onclick="closeDetail()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
            <i data-feather="x" class="w-4 h-4"></i>
        </button>
    </div>

    <div id="detailContent" class="flex-1 overflow-auto p-6 space-y-4"></div>

    <div id="detailAction" class="p-6 border-t border-slate-100 space-y-2 bg-white"></div>
</div>

<!-- OVERLAY -->
<div id="overlay" onclick="closeDetail()" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-40"></div>

<!-- IMAGE MODAL -->
<div id="imageModal" class="fixed inset-0 bg-black/90 hidden items-center justify-center z-50">
    <img id="modalImage" class="max-w-[90%] max-h-[85vh] rounded-xl shadow-2xl object-contain">
    <button onclick="closeImage()" class="absolute top-5 right-5 w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-colors">
        <i data-feather="x" class="w-5 h-5"></i>
    </button>
</div>

<!-- APPROVE MODAL (URGENCY) -->
<div id="approveModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-sm rounded-2xl p-6 shadow-2xl modal-box">
        <div class="text-center mb-5">
            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-feather="check-circle" class="w-6 h-6 text-green-600"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-lg">Tingkat Urgensi</h3>
            <p class="text-slate-400 text-sm mt-1">Tentukan prioritas penanganan</p>
        </div>

        <div class="space-y-2.5">
            <button onclick="submitApprove('low')" class="btn w-full flex items-center justify-between px-4 py-3.5 rounded-xl border border-slate-200 hover:bg-slate-50 transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                        <i data-feather="clock" class="w-4 h-4 text-slate-500"></i>
                    </div>
                    <span class="font-semibold text-slate-700">Santai</span>
                </div>
                <span class="text-xs bg-slate-100 text-slate-500 px-2 py-1 rounded-full font-medium">Low</span>
            </button>

            <button onclick="submitApprove('medium')" class="btn w-full flex items-center justify-between px-4 py-3.5 rounded-xl bg-amber-50 border border-amber-200 hover:bg-amber-100 transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                        <i data-feather="alert-triangle" class="w-4 h-4 text-amber-600"></i>
                    </div>
                    <span class="font-semibold text-amber-700">Segera</span>
                </div>
                <span class="text-xs bg-amber-200 text-amber-700 px-2 py-1 rounded-full font-medium">Medium</span>
            </button>

            <button onclick="submitApprove('high')" class="btn w-full flex items-center justify-between px-4 py-3.5 rounded-xl bg-red-50 border border-red-200 hover:bg-red-100 transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <i data-feather="zap" class="w-4 h-4 text-red-600"></i>
                    </div>
                    <span class="font-semibold text-red-700">Prioritas</span>
                </div>
                <span class="text-xs bg-red-200 text-red-700 px-2 py-1 rounded-full font-medium">High</span>
            </button>
        </div>

        <button onclick="closeApproveModal()" class="mt-4 w-full text-slate-400 text-sm hover:text-slate-600 font-medium py-2">
            Batal
        </button>
    </div>
</div>

<!-- ASSIGN TECHNICIAN MODAL -->
<div id="assignModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-sm rounded-2xl p-6 shadow-2xl modal-box">
        <div class="text-center mb-5">
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-feather="user-check" class="w-6 h-6 text-blue-600"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-lg">Tentukan Tukang</h3>
            <p class="text-slate-400 text-sm mt-1">Pilih teknisi yang akan menangani</p>
        </div>

        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 block">Pilih Teknisi</label>
        <select id="technicianSelect" class="w-full border border-slate-200 rounded-xl p-3 text-sm mb-5">
            <option value="">Memuat data...</option>
        </select>

        <div class="flex gap-2">
            <button onclick="closeAssignModal()" class="btn flex-1 border border-slate-200 text-slate-500 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50">
                Batal
            </button>
            <button onclick="submitAssign()" class="btn flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-semibold">
                Assign
            </button>
        </div>
    </div>
</div>

<script>
let selectedCategory = null;
let selectedId = null;
let currentFilter = 'all';
const token = localStorage.getItem('token');
if (!token) { alert('Session habis'); window.location.href = '/login'; }

const statusLabel = {
    'pending':          'Pending',
    'approved':         'Disetujui',
    'waiting_material': 'Menunggu Material',
    'material_ready':   'Material Siap',
    'on_progress':      'Dikerjakan',
    'done':             'Selesai',
    'rejected':         'Ditolak',
};

const statusStyle = {
    'pending':          'bg-amber-100 text-amber-700',
    'approved':         'bg-green-100 text-green-700',
    'waiting_material': 'bg-orange-100 text-orange-700',
    'material_ready':   'bg-blue-100 text-blue-700',
    'on_progress':      'bg-purple-100 text-purple-700',
    'done':             'bg-slate-100 text-slate-600',
    'rejected':         'bg-red-100 text-red-700',
};

const urgencyStyle = {
    'low':    'bg-slate-100 text-slate-500',
    'medium': 'bg-amber-100 text-amber-700',
    'high':   'bg-red-100 text-red-700',
};

function filterRequests(status) {
    currentFilter = status;
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.className = 'tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-white text-slate-500 border border-slate-200';
    });
    document.getElementById('tab-' + status).className = 'tab-btn btn px-4 py-2 rounded-lg text-sm font-semibold bg-slate-800 text-white';
    renderRequests(window.requestData || []);
}

function renderRequests(data) {
    const filtered = currentFilter === 'all' ? data : data.filter(i => i.status === currentFilter);
    const grid = document.getElementById('requestTable');
    const empty = document.getElementById('emptyState');

    if (!filtered.length) {
        grid.innerHTML = '';
        empty.classList.remove('hidden');
        feather.replace();
        return;
    }
    empty.classList.add('hidden');

    grid.innerHTML = filtered.map(item => `
        <div class="card bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            ${item.photo ? `
                <div class="h-36 overflow-hidden cursor-pointer relative" onclick="openImage('/storage/${item.photo}')">
                    <img src="/storage/${item.photo}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                </div>
            ` : `<div class="h-1.5 bg-gradient-to-r from-indigo-400 to-blue-300"></div>`}

            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-slate-800 text-sm leading-snug flex-1 pr-2">${item.title}</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold whitespace-nowrap ${statusStyle[item.status] ?? 'bg-slate-100 text-slate-500'}">
                        ${statusLabel[item.status] ?? item.status}
                    </span>
                </div>

                <div class="flex items-center gap-1.5 text-xs text-slate-400 mb-1">
                    <i data-feather="tag" class="w-3 h-3"></i>
                    <span>${item.category ?? '-'}</span>
                </div>

                ${item.urgency ? `
                <div class="flex items-center gap-1.5 mb-2">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium ${urgencyStyle[item.urgency] ?? ''}">
                        ${item.urgency === 'high' ? '🔴 Prioritas' : item.urgency === 'medium' ? '🟡 Segera' : '🟢 Santai'}
                    </span>
                </div>` : ''}

                <p class="text-xs text-slate-500 line-clamp-2 mb-3">${item.description ?? ''}</p>

                <div class="flex flex-wrap gap-1.5 border-t border-slate-50 pt-3">
                    <button onclick="detail(${item.id})" class="btn flex items-center gap-1 bg-slate-800 hover:bg-slate-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold">
                        <i data-feather="eye" class="w-3 h-3"></i> Detail
                    </button>

                    ${item.status === 'pending' ? `
                        <button onclick="approve(${item.id}, ${item.category_id})" class="btn flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold">
                            <i data-feather="check" class="w-3 h-3"></i> Approve
                        </button>
                        <button onclick="reject(${item.id})" class="btn flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold">
                            <i data-feather="x" class="w-3 h-3"></i> Reject
                        </button>
                    ` : ''}

                    ${item.status === 'waiting_material' || item.status === 'material_ready' ? `
                        <button onclick="reviewMaterial(${item.id})" class="btn flex items-center gap-1 bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold">
                            <i data-feather="package" class="w-3 h-3"></i> Review Material
                        </button>
                    ` : ''}
                </div>
            </div>
        </div>
    `).join('');

    feather.replace();
}

async function loadRequests() {
    const res = await fetch('/api/requests', { headers: { 'Authorization': 'Bearer ' + token } });
    const data = await res.json();
    if (!res.ok) { alert('Gagal load data'); return; }
    window.requestData = data;
    renderRequests(data);
}

async function detail(id) {
    const res = await fetch(`/api/requests/${id}`, { headers: { 'Authorization': 'Bearer ' + token } });
    const data = await res.json();

    document.getElementById('detailContent').innerHTML = `
        <div class="flex justify-between items-start">
            <h3 class="font-bold text-slate-800 text-base leading-snug flex-1 pr-3">${data.title}</h3>
            <span class="text-xs px-2.5 py-1 rounded-full font-semibold whitespace-nowrap ${statusStyle[data.status] ?? ''}">
                ${statusLabel[data.status] ?? data.status}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-2">
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-xs text-slate-400 mb-0.5">Cabang</p>
                <p class="text-sm font-semibold text-slate-700">${data.branch ?? '-'}</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-xs text-slate-400 mb-0.5">Kategori</p>
                <p class="text-sm font-semibold text-slate-700">${data.category ?? '-'}</p>
            </div>
            ${data.urgency ? `
            <div class="bg-slate-50 rounded-xl p-3 col-span-2">
                <p class="text-xs text-slate-400 mb-0.5">Urgensi</p>
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold ${urgencyStyle[data.urgency] ?? ''}">
                    ${data.urgency === 'high' ? '🔴 Prioritas' : data.urgency === 'medium' ? '🟡 Segera' : '🟢 Santai'}
                </span>
            </div>` : ''}
        </div>

        <div class="bg-slate-50 rounded-xl p-4">
            <p class="text-xs text-slate-400 mb-1">Deskripsi</p>
            <p class="text-sm text-slate-700 leading-relaxed">${data.description ?? '-'}</p>
        </div>

        ${data.photo ? `
            <img src="/storage/${data.photo}" onclick="openImage('/storage/${data.photo}')"
                class="w-full h-48 object-cover rounded-xl cursor-pointer hover:opacity-90 transition-opacity">
        ` : ''}
    `;

    let actionHTML = '';
    if (data.status === 'pending') {
        actionHTML = `
            <div class="flex gap-2">
                <button onclick="approve(${data.id}, '${data.category}')" class="btn flex-1 bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                    <i data-feather="check" class="w-4 h-4"></i> Approve
                </button>
                <button onclick="reject(${data.id})" class="btn flex-1 bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                    <i data-feather="x" class="w-4 h-4"></i> Reject
                </button>
            </div>`;
    }
    if (data.status === 'approved' && !data.technician_id) {
        actionHTML += `
            <button onclick="openAssignFromDetail(${data.id}, ${data.category_id})" class="btn w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                <i data-feather="user-plus" class="w-4 h-4"></i> Tentukan Tukang
            </button>`;
    }

    document.getElementById('detailAction').innerHTML = actionHTML;
    document.getElementById('detailPanel').classList.remove('translate-x-full');
    document.getElementById('overlay').classList.remove('hidden');
    feather.replace();
}

function closeDetail() {
    document.getElementById('detailPanel').classList.add('translate-x-full');
    document.getElementById('overlay').classList.add('hidden');
}

async function reviewMaterial(id) {
    const res = await fetch(`/api/materials?request_id=${id}`, { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();
    if (!res.ok || !Array.isArray(data)) { alert('Gagal load material'); return; }

    document.getElementById('detailContent').innerHTML = `
        <div class="mb-4">
            <h4 class="font-bold text-slate-800 mb-1">Material Dibutuhkan</h4>
            <p class="text-xs text-slate-400">Review list material yang diajukan teknisi</p>
        </div>
        <div class="space-y-2">
            ${data.map((item, i) => `
                <div class="flex items-center justify-between bg-slate-50 rounded-xl px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-indigo-100 rounded-lg flex items-center justify-center text-xs font-bold text-indigo-600">${i+1}</div>
                        <span class="text-sm font-medium text-slate-700">${item.item_name}</span>
                    </div>
                    <span class="text-sm text-slate-500 font-semibold">${item.qty} <span class="text-xs font-normal">${item.unit}</span></span>
                </div>
            `).join('')}
        </div>
    `;

    document.getElementById('detailAction').innerHTML = `
        <button onclick="approveAllMaterial(${id})" class="btn w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
            <i data-feather="check-circle" class="w-4 h-4"></i> Konfirmasi Material Siap
        </button>`;

    document.getElementById('detailPanel').classList.remove('translate-x-full');
    document.getElementById('overlay').classList.remove('hidden');
    feather.replace();
}

async function approveAllMaterial(id) {
    const res = await fetch(`/api/materials/approve-all/${id}`, { method: 'POST', headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    alert('Material siap!');
    closeDetail();
    loadRequests();
}

function approve(id, category) {
    selectedId = id;
    selectedCategory = category;
    const modal = document.getElementById('approveModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.remove('flex');
    document.getElementById('approveModal').classList.add('hidden');
}

async function submitApprove(level) {
    const res = await fetch(`/api/requests/${selectedId}/approve`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ urgency: level })
    });
    const data = await res.json();
    if (!res.ok) { alert(data.message || 'Gagal approve'); return; }
    closeApproveModal();
    if (level === 'high') {
        openAssignModal();
    } else {
        alert('Berhasil approve!');
        loadRequests();
        closeDetail();
    }
}

async function openAssignModal() {
    document.getElementById('assignModal').classList.remove('hidden');
    document.getElementById('assignModal').classList.add('flex');
    const res = await fetch(`/api/technicians?category_id=${selectedCategory}`, { headers: { 'Authorization': 'Bearer ' + token } });
    const data = await res.json();
    if (!res.ok || !Array.isArray(data)) { alert('Gagal load teknisi'); closeAssignModal(); return; }
    document.getElementById('technicianSelect').innerHTML =
        '<option value="">Pilih Teknisi</option>' +
        data.map(t => `<option value="${t.id}">${t.name}</option>`).join('');
    feather.replace();
}

function openAssignFromDetail(id, category) {
    selectedId = id;
    selectedCategory = category;
    openAssignModal();
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.remove('flex');
    document.getElementById('assignModal').classList.add('hidden');
}

async function submitAssign() {
    const techId = document.getElementById('technicianSelect').value;
    if (!techId) return alert('Pilih tukang dulu!');
    const res = await fetch(`/api/requests/${selectedId}/assign-technician`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ technician_id: techId })
    });
    const data = await res.json();
    if (!res.ok) { alert(data.message || 'Terjadi error'); return; }
    alert('Tukang berhasil ditentukan!');
    closeAssignModal();
    closeDetail();
    loadRequests();
}

async function reject(id) {
    const reason = prompt('Alasan reject:');
    if (!reason) return;
    await fetch(`/api/requests/${id}/reject`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ reason })
    });
    loadRequests();
    closeDetail();
}

function openImage(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function goTo(url) {
    window.location.href = url;
}

function closeImage() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadRequests();
});
</script>

</body>
</html>