<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .badge-active   { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .badge-admin    { background: #ede9fe; color: #5b21b6; }
        .badge-pic      { background: #dbeafe; color: #1e40af; }
        .badge-tech     { background: #fef3c7; color: #92400e; }
        .badge-super    { background: #f1f5f9; color: #334155; }

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
        <h1 class="font-bold text-slate-800 text-lg">User Management</h1>
        <p class="text-xs text-slate-400 mt-0.5">Kelola akun dan hak akses pengguna</p>
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
    <h2 class="text-xl font-bold text-slate-800">Daftar Pengguna</h2>
    <button onclick="openModal()" class="btn flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">
        <i data-feather="user-plus" class="w-4 h-4"></i> Tambah User
    </button>
</div>

<!-- TABLE -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Pengguna</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Role</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Spesialisasi</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Cabang</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Status</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody id="userTable"></tbody>
    </table>
    </div>

    <!-- Empty state -->
    <div id="emptyState" class="hidden py-16 text-center">
        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i data-feather="users" class="w-8 h-8 text-slate-400"></i>
        </div>
        <p class="text-slate-500 font-medium">Belum ada pengguna</p>
        <p class="text-slate-400 text-xs mt-1">Klik tombol "+ Tambah User" untuk menambahkan</p>
    </div>
</div>

</div>
</div>
</div>

<!-- MODAL TAMBAH USER -->
<div id="modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
<div class="bg-white rounded-2xl w-full max-w-md shadow-2xl modal-enter max-h-[90vh] overflow-y-auto">

    <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
        <div>
            <h2 class="font-bold text-slate-800">Tambah User</h2>
            <p class="text-xs text-slate-400 mt-0.5">Buat akun pengguna baru</p>
        </div>
        <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <i data-feather="x" class="w-4 h-4"></i>
        </button>
    </div>

    <div class="p-6 space-y-3">
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Nama <span class="text-red-500">*</span></label>
            <input id="name" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm" placeholder="Nama lengkap">
        </div>
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Email <span class="text-red-500">*</span></label>
            <input id="email" type="email" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm" placeholder="email@perusahaan.com">
        </div>
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Role <span class="text-red-500">*</span></label>
            <select id="role" onchange="handleRole()" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm">
                <option value="admin">Admin GA</option>
                <option value="pic">PIC</option>
                <option value="technician">Technician</option>
            </select>
        </div>

        <!-- SPESIALISASI (technician only) -->
        <div id="categoryWrapper" class="hidden">
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Spesialisasi <span class="text-red-500">*</span></label>
            <select id="category" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm"></select>
        </div>

        <!-- BRANCH -->
        <div id="branchWrapper">
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Tipe Lokasi <span class="text-red-500">*</span></label>
            <select id="branchType" onchange="handleBranchType()" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mb-2">
                <option value="">Pilih tipe lokasi</option>
                <option value="ho">Head Office (HO)</option>
                <option value="branch">Cabang</option>
            </select>
            <select id="branch" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm hidden"></select>
        </div>

        <button onclick="saveUser()" class="btn w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold text-sm mt-2">
            Simpan User
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
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
    document.getElementById('overlay')?.classList.toggle('hidden');
}

function roleBadge(role) {
    const map = {
        'super_admin': ['badge-super', 'Super Admin'],
        'admin':       ['badge-admin', 'Admin GA'],
        'pic':         ['badge-pic',   'PIC'],
        'technician':  ['badge-tech',  'Technician'],
    };
    const [cls, label] = map[role] ?? ['badge-super', role];
    return `<span class="px-2.5 py-1 rounded-full text-xs font-semibold ${cls}">${label}</span>`;
}

// LOAD USERS
async function loadUsers() {
    const res = await fetch('/api/users', {
        headers: { Authorization: 'Bearer ' + token }
    });
    if (!res.ok) return;
    const data = await res.json();

    const empty = document.getElementById('emptyState');
    if (!data.length) {
        document.getElementById('userTable').innerHTML = '';
        empty.classList.remove('hidden');
        feather.replace();
        return;
    }
    empty.classList.add('hidden');

    document.getElementById('userTable').innerHTML = data.map(u => {
        let actions = '';

        if (u.role === 'super_admin') {
            actions = `
                <button onclick="resetPassword(${u.id})" class="btn flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-semibold border border-blue-200">
                    <i data-feather="refresh-cw" class="w-3 h-3"></i> Reset
                </button>`;
        } else {
            if (u.status === 'inactive') {
                actions += `
                    <button onclick="activateUser(${u.id})" class="btn flex items-center gap-1.5 bg-green-50 hover:bg-green-100 text-green-600 px-3 py-1.5 rounded-lg text-xs font-semibold border border-green-200">
                        <i data-feather="check-circle" class="w-3 h-3"></i> Aktifkan
                    </button>`;
            } else {
                actions += `
                    <button onclick="disableUser(${u.id})" class="btn flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100 text-amber-600 px-3 py-1.5 rounded-lg text-xs font-semibold border border-amber-200">
                        <i data-feather="pause-circle" class="w-3 h-3"></i> Nonaktifkan
                    </button>`;
            }
            actions += `
                <button onclick="resetPassword(${u.id})" class="btn flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-semibold border border-blue-200">
                    <i data-feather="refresh-cw" class="w-3 h-3"></i> Reset
                </button>
                <button onclick="deleteUser(${u.id})" class="btn flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-xs font-semibold border border-red-200">
                    <i data-feather="trash-2" class="w-3 h-3"></i> Hapus
                </button>`;
        }

        return `
        <tr class="border-b border-slate-50 last:border-0">
            <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-indigo-600 font-semibold text-xs">${u.name.charAt(0).toUpperCase()}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700">${u.name}</p>
                        <p class="text-xs text-slate-400">${u.email}</p>
                    </div>
                </div>
            </td>
            <td class="px-5 py-4">${roleBadge(u.role)}</td>
            <td class="px-5 py-4 text-slate-500 text-sm">${u.category?.name ?? '<span class="text-slate-300">-</span>'}</td>
            <td class="px-5 py-4 text-slate-500 text-sm">${u.branch?.name ?? '<span class="text-slate-300">-</span>'}</td>
            <td class="px-5 py-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${u.status === 'active' ? 'badge-active' : 'badge-inactive'}">
                    ${u.status === 'active' ? 'Aktif' : 'Nonaktif'}
                </span>
            </td>
            <td class="px-5 py-4">
                <div class="flex gap-1.5 flex-wrap">${actions}</div>
            </td>
        </tr>`;
    }).join('');

    feather.replace();
}

async function loadBranch(filterType = null) {
    const res = await fetch('/api/branches', { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();
    let options = '<option value="">Pilih cabang</option>';
    data.forEach(b => {
        if (filterType && b.type !== filterType) return;
        options += `<option value="${b.id}">${b.name} (${b.type === 'ho' ? 'HO' : 'Cabang'})</option>`;
    });
    document.getElementById('branch').innerHTML = options;
}

async function loadCategories() {
    const res = await fetch('/api/categories', { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();
    document.getElementById('category').innerHTML = '<option value="">Pilih spesialisasi</option>' +
        data.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
}

function handleRole() {
    const role = document.getElementById('role').value;
    const catWrap = document.getElementById('categoryWrapper');
    const branchWrap = document.getElementById('branchWrapper');
    if (role === 'technician') {
        catWrap.classList.remove('hidden');
        branchWrap.classList.add('hidden');
        loadCategories();
    } else {
        catWrap.classList.add('hidden');
        branchWrap.classList.remove('hidden');
    }
}

function handleBranchType() {
    const type = document.getElementById('branchType').value;
    const branchSelect = document.getElementById('branch');
    if (type) {
        branchSelect.classList.remove('hidden');
        loadBranch(type);
    } else {
        branchSelect.classList.add('hidden');
    }
}

async function saveUser() {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const role = document.getElementById('role').value;
    const type = document.getElementById('branchType').value;
    const branch_id = document.getElementById('branch').value;
    const category_id = document.getElementById('category').value;

    if (!name || !email || !role) { alert('Semua field wajib diisi!'); return; }
    if (name.length < 6) { alert('Nama minimal 6 karakter!'); return; }
    if (!email.includes('@')) { alert('Email tidak valid!'); return; }
    if (role === 'technician' && !category_id) { alert('Spesialisasi wajib dipilih!'); return; }
    if (role !== 'technician') {
        if (!type) { alert('Tipe lokasi wajib dipilih!'); return; }
        if (type === 'branch' && !branch_id) { alert('Cabang wajib dipilih!'); return; }
    }

    let finalBranch = null;
    if (type === 'branch') {
        finalBranch = Number(branch_id);
    } else if (type === 'ho') {
        const d = await fetch('/api/branches', { headers: { Authorization: 'Bearer ' + token } }).then(r => r.json());
        finalBranch = d.find(b => b.type === 'ho')?.id;
    }

    const res = await fetch('/api/users', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify({
            name, email, role,
            branch_id: role === 'technician' ? null : finalBranch,
            category_id: role === 'technician' ? Number(category_id) : null
        })
    });

    const data = await res.json();
    if (!res.ok) { alert(JSON.stringify(data.errors)); return; }
    closeModal();
    loadUsers();
}

async function activateUser(id) {
    await fetch(`/api/users/${id}/activate`, { method: 'PUT', headers: { Authorization: 'Bearer ' + token } });
    loadUsers();
}

async function disableUser(id) {
    await fetch(`/api/users/${id}/disable`, { method: 'PUT', headers: { Authorization: 'Bearer ' + token } });
    loadUsers();
}

async function deleteUser(id) {
    if (!confirm('Yakin hapus user ini?')) return;
    await fetch(`/api/users/${id}`, { method: 'DELETE', headers: { Authorization: 'Bearer ' + token } });
    loadUsers();
}

async function resetPassword(id) {
    if (!confirm('Reset password ke default (123456)?')) return;
    await fetch(`/api/users/${id}/reset-password`, { method: 'PUT', headers: { Authorization: 'Bearer ' + token } });
    alert('Password berhasil direset!');
}

function openModal() {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('role').value = 'admin';
    document.getElementById('branchType').value = '';
    document.getElementById('branch').classList.add('hidden');
    document.getElementById('categoryWrapper').classList.add('hidden');
    document.getElementById('branchWrapper').classList.remove('hidden');
    feather.replace();
}
function closeModal() { document.getElementById('modal').classList.add('hidden'); }

loadUsers();
loadBranch();
feather.replace();
</script>

</body>
</html>