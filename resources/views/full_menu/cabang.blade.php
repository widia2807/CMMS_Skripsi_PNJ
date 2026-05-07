<!DOCTYPE html>
<html>
<head>
    <title>Cabang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-disabled { background: #fee2e2; color: #991b1b; }
        .badge-ho { background: #ede9fe; color: #5b21b6; }
        .badge-branch { background: #f1f5f9; color: #475569; }

        .modal-enter { animation: modalIn 0.2s ease; }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        tr { transition: background 0.15s; }
        tr:hover td { background: #f8fafc; }

        .btn { transition: all 0.15s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }

        input, select {
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>
</head>

<body class="bg-slate-50">

@include('components.sidebar')

<div class="flex min-h-screen">
<div class="flex-1 md:ml-64">

<!-- TOPBAR -->
<div class="bg-white border-b border-slate-100 px-8 py-4 flex justify-between items-center sticky top-0 z-30">
    <div>
        <h1 class="font-bold text-slate-800 text-lg">Manajemen Cabang</h1>
        <p class="text-xs text-slate-400 mt-0.5">Kelola semua cabang dan head office</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
            <span class="text-indigo-600 font-semibold text-sm">A</span>
        </div>
        <span class="text-sm font-medium text-slate-600">Admin</span>
    </div>
</div>

<div class="p-8">

<!-- ACTION BAR -->
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <h2 class="text-xl font-bold text-slate-800">Daftar Cabang & HO</h2>

    <div class="flex flex-wrap gap-2">
        <button onclick="downloadTemplate()" class="btn flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">
            <i data-feather="file-text" class="w-4 h-4"></i> Template
        </button>
        <button onclick="openImportModal()" class="btn flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">
            <i data-feather="upload" class="w-4 h-4"></i> Import
        </button>
        <button onclick="openModal()" class="btn flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">
            <i data-feather="plus" class="w-4 h-4"></i> Tambah Cabang
        </button>
    </div>
</div>

<!-- TABLE -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Nama</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Tipe</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Status</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody id="branchTable"></tbody>
    </table>
    </div>

    <!-- Empty state -->
    <div id="emptyState" class="hidden py-16 text-center">
        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i data-feather="git-branch" class="w-8 h-8 text-slate-400"></i>
        </div>
        <p class="text-slate-500 font-medium">Belum ada data cabang</p>
        <p class="text-slate-400 text-xs mt-1">Klik tombol "+ Tambah Cabang" untuk menambahkan</p>
    </div>
</div>

</div>
</div>
</div>

<!-- IMPORT MODAL -->
<div id="importModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
<div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl modal-enter">
    <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
        <div>
            <h2 class="font-bold text-slate-800">Import Cabang</h2>
            <p class="text-xs text-slate-400 mt-0.5">Upload file Excel sesuai template</p>
        </div>
        <button onclick="closeImportModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
            <i data-feather="x" class="w-4 h-4"></i>
        </button>
    </div>
    <div class="p-6 space-y-4">
        <div class="bg-teal-50 border border-teal-200 rounded-lg p-3 text-xs text-teal-700">
            Belum punya template? <button onclick="downloadTemplate()" class="font-semibold underline">Download di sini</button>
        </div>
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 block">Pilih File Excel</label>
            <input type="file" id="importFile" accept=".xlsx,.xls" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-600 file:text-sm file:font-medium hover:file:bg-slate-200">
        </div>
        <div id="importResult" class="hidden"></div>
        <button onclick="submitImport()" class="btn w-full bg-orange-500 hover:bg-orange-600 text-white py-2.5 rounded-lg font-semibold text-sm">
            Upload & Import
        </button>
    </div>
</div>
</div>

<!-- MODAL TAMBAH/EDIT CABANG -->
<div id="modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
<div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl modal-enter">
    <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
        <h2 class="font-bold text-slate-800" id="modalTitle">Tambah Cabang</h2>
        <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <i data-feather="x" class="w-4 h-4"></i>
        </button>
    </div>
    <div class="p-6 space-y-3">
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Nama <span class="text-red-500">*</span></label>
            <input id="branchName" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm" placeholder="Nama cabang / HO">
        </div>
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Tipe <span class="text-red-500">*</span></label>
            <select id="branchType" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm">
                <option value="branch">Cabang</option>
                <option value="ho">Head Office (HO)</option>
            </select>
        </div>
        <button onclick="createBranch()" class="btn w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold text-sm mt-2">
            Simpan
        </button>
    </div>
</div>
</div>

<script>
const token = localStorage.getItem('token');
const user = JSON.parse(localStorage.getItem('user'));

if (!user || !token) window.location.href = '/login';

function goTo(url) { window.location.href = url; }
function logout() { localStorage.clear(); window.location.href = '/login'; }

// MODAL
function openModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Cabang';
    document.getElementById('branchName').value = '';
    document.getElementById('branchType').value = 'branch';
    window.editId = null;
    document.getElementById('modal').classList.remove('hidden');
    feather.replace();
}
function closeModal() { document.getElementById('modal').classList.add('hidden'); }

// IMPORT MODAL
function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
    document.getElementById('importResult').classList.add('hidden');
    document.getElementById('importFile').value = '';
    feather.replace();
}
function closeImportModal() { document.getElementById('importModal').classList.add('hidden'); }

// LOAD
async function loadBranches() {
    const res = await fetch('/api/branches', {
        headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();

    const empty = document.getElementById('emptyState');
    if (!data.length) {
        document.getElementById('branchTable').innerHTML = '';
        empty.classList.remove('hidden');
        feather.replace();
        return;
    }
    empty.classList.add('hidden');

    document.getElementById('branchTable').innerHTML = data.map(b => `
        <tr class="border-b border-slate-50 last:border-0">
            <td class="px-5 py-4 font-semibold text-slate-700">${b.name}</td>
            <td class="px-5 py-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${b.type === 'ho' ? 'badge-ho' : 'badge-branch'}">
                    ${b.type === 'ho' ? 'Head Office' : 'Cabang'}
                </span>
            </td>
            <td class="px-5 py-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${b.status === 'active' ? 'badge-active' : 'badge-disabled'}">
                    ${b.status === 'active' ? 'Aktif' : 'Nonaktif'}
                </span>
            </td>
            <td class="px-5 py-4">
                <div class="flex gap-1.5">
                    <button onclick="toggleBranch(${b.id})" class="btn flex items-center gap-1.5 ${b.status === 'active' ? 'bg-red-50 hover:bg-red-100 text-red-600 border border-red-200' : 'bg-green-50 hover:bg-green-100 text-green-600 border border-green-200'} px-3 py-1.5 rounded-lg text-xs font-semibold">
                        <i data-feather="${b.status === 'active' ? 'x-circle' : 'check-circle'}" class="w-3 h-3"></i>
                        ${b.status === 'active' ? 'Nonaktifkan' : 'Aktifkan'}
                    </button>
                    <button onclick="editBranch(${b.id}, '${b.name}', '${b.type}')" class="btn flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100 text-amber-600 px-3 py-1.5 rounded-lg text-xs font-semibold border border-amber-200">
                        <i data-feather="edit-2" class="w-3 h-3"></i> Edit
                    </button>
                    <button onclick="deleteBranch(${b.id})" class="btn flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-xs font-semibold border border-red-200">
                        <i data-feather="trash-2" class="w-3 h-3"></i> Hapus
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    feather.replace();
}

// CREATE / EDIT
async function createBranch() {
    const name = document.getElementById('branchName').value;
    const type = document.getElementById('branchType').value;
    if (!name) { alert('Nama wajib diisi!'); return; }

    const url = window.editId ? `/api/branches/${window.editId}` : '/api/branches';
    const method = window.editId ? 'PUT' : 'POST';

    await fetch(url, {
        method,
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, type })
    });

    window.editId = null;
    closeModal();
    loadBranches();
}

function editBranch(id, name, type) {
    document.getElementById('modalTitle').textContent = 'Edit Cabang';
    document.getElementById('branchName').value = name;
    document.getElementById('branchType').value = type;
    window.editId = id;
    document.getElementById('modal').classList.remove('hidden');
    feather.replace();
}

async function toggleBranch(id) {
    await fetch(`/api/branches/${id}/toggle`, {
        method: 'PUT',
        headers: { Authorization: 'Bearer ' + token }
    });
    loadBranches();
}

async function deleteBranch(id) {
    if (!confirm('Yakin ingin menghapus cabang ini?')) return;
    await fetch(`/api/branches/${id}`, {
        method: 'DELETE',
        headers: { Authorization: 'Bearer ' + token }
    });
    loadBranches();
}

// TEMPLATE DOWNLOAD
async function downloadTemplate() {
    const res = await fetch('/api/branches/import-template', {
        headers: { Authorization: 'Bearer ' + token }
    });
    if (!res.ok) { alert('Gagal download template!'); return; }
    const blob = await res.blob();
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'Template_Import_Cabang.xlsx';
    a.click();
    URL.revokeObjectURL(url);
}

// IMPORT
async function submitImport() {
    const file = document.getElementById('importFile').files[0];
    if (!file) { alert('Pilih file dulu!'); return; }

    const fd = new FormData();
    fd.append('file', file);

    const res = await fetch('/api/import-branches', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token },
        body: fd
    });

    const data = await res.json();
    const resultDiv = document.getElementById('importResult');
    resultDiv.classList.remove('hidden');

    if (!res.ok || data.errors?.length) {
        resultDiv.innerHTML = `
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-xs text-amber-700">
                <p class="font-semibold mb-1">${data.message ?? 'Import selesai dengan beberapa masalah'}</p>
                ${data.errors?.length ? `<ul class="list-disc pl-4 space-y-0.5">${data.errors.map(e => `<li>${e}</li>`).join('')}</ul>` : ''}
            </div>`;
    } else {
        resultDiv.innerHTML = `<div class="bg-green-50 border border-green-200 rounded-lg p-3 text-xs text-green-700 font-semibold">${data.message ?? 'Import berhasil!'}</div>`;
    }

    loadBranches();
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay?.classList.toggle('hidden');
}

// INIT
loadBranches();
feather.replace();
</script>

</body>
</html>