<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>


<body class="bg-gray-100">
@include('components.sidebar')
<div class="flex h-screen">

<!-- OVERLAY -->
    <div id="overlay"
     class="fixed inset-0 bg-black opacity-40 hidden md:hidden"
     onclick="toggleSidebar()">
    </div>
    <!-- MAIN -->
    <div class="flex-1 md:ml-64">

        <!-- TOPBAR -->
        <div class="bg-white shadow px-6 py-4 flex items-center justify-between">

            <button onclick="toggleSidebar()" class="md:hidden">
                <i data-feather="menu"></i>
            </button>

            <h1 class="font-semibold">User Management</h1>

            <button onclick="openModal()"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                + Tambah User
            </button>
        </div>

        <!-- CONTENT -->
        <div class="p-6">

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Role</th>
                            <th class="p-3 text-left">Cabang</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody id="userTable"></tbody>
                </table>
            </div>

        </div>
    </div>

</div>
<!-- MODAL -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded w-96">

        <h2 class="mb-3 font-semibold">Tambah User</h2>

        <input id="name" placeholder="Nama" class="w-full border p-2 mb-2">
        <input id="email" placeholder="Email" class="w-full border p-2 mb-2">

        <select id="role" class="w-full border p-2 mb-2">
            <option value="admin">Admin GA</option>
            <option value="pic">PIC</option>
            <option value="technician">Technician</option>
        </select>

        <select id="branchType" onchange="handleBranchType()" class="w-full border p-2 mb-2">
    <option value="">-- pilih tipe lokasi --</option>
    <option value="ho">HO</option>
    <option value="branch">Cabang</option>
</select>
<select id="branch" class="w-full border p-2 mb-2 hidden"></select>
        <button onclick="saveUser()" class="bg-blue-600 text-white w-full p-2 rounded">
            Simpan
        </button>

    </div>
</div>

<script>
    
const token = localStorage.getItem('token');
const user = JSON.parse(localStorage.getItem('user'));

// Proteksi login
if (!user || !token) {
    window.location.href = '/login';
}

function isLite() {
    return user?.system_type === 'lite';
}

loadUsers();
loadBranch();

function handleBranchType() {
    const type = document.getElementById('branchType').value;
    const branchSelect = document.getElementById('branch');

    if (type === 'branch') {
        branchSelect.classList.remove('hidden');
        loadBranch('branch');
    } else if (type === 'ho') {
        branchSelect.classList.remove('hidden');
        loadBranch('ho'); // 🔥 INI YANG KURANG
    } else {
        branchSelect.classList.add('hidden');
    }
}

function goTo(url) {
    window.location.href = url;
}

function goToDashboard() {
    const user = JSON.parse(localStorage.getItem('user'));

    if (user?.system_type === 'lite') {
        window.location.href = '/dashboard-lite';
    } else {
        window.location.href = '/dashboard-full';
    }
}

function logout() {
    localStorage.clear();
    window.location.href = '/login';
}
// ================= LOAD USERS =================
async function loadUsers() {
    const res = await fetch('/api/users', {
    headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + token
    }
});

if (!res.ok) {
    console.error('Status:', res.status);
    const text = await res.text();
    console.error(text);
    return;
}

    const data = await res.json();

    let html = '';

    data.forEach(user => {

        // ================= STATUS =================
        const statusBadge = user.status === 'active'
            ? '<span class="text-green-600 font-semibold">Active</span>'
            : '<span class="text-red-500 font-semibold">Inactive</span>';

        // ================= ACTION BUTTON =================
       let actionButtons = '';

if (user.role === 'super_admin') {

    // ✅ hanya reset
    actionButtons += `
    <button onclick="resetPassword(${user.id})"
        class="bg-blue-500 px-2 py-1 text-white rounded text-xs">
        Reset
    </button>`;

} else {

    // Activate / Disable
    if (user.status === 'inactive') {
        actionButtons += `
        <button onclick="activateUser(${user.id})"
            class="bg-green-600 px-2 py-1 text-white rounded text-xs">
            Activate
        </button>`;
    } else {
        actionButtons += `
        <button onclick="disableUser(${user.id})"
            class="bg-yellow-500 px-2 py-1 text-white rounded text-xs">
            Disable
        </button>`;
    }

    // Reset
    actionButtons += `
    <button onclick="resetPassword(${user.id})"
        class="bg-blue-500 px-2 py-1 text-white rounded text-xs">
        Reset
    </button>`;

    // Delete
    actionButtons += `
    <button onclick="deleteUser(${user.id})"
        class="bg-red-500 px-2 py-1 text-white rounded text-xs">
        Delete
    </button>`;
}

        html += `
        <tr class="border-b hover:bg-gray-50">
            <td class="p-2">${user.name}</td>
            <td class="p-2">${user.email}</td>
            <td class="p-2 capitalize">${user.role}</td>
            <td class="p-2">${user.branch?.name ?? '-'}</td>
            <td class="p-2">${statusBadge}</td>
            <td class="p-2 flex gap-2 flex-wrap">${actionButtons}</td>
        </tr>`;
    });

    document.getElementById('userTable').innerHTML = html;
}

async function loadBranch(filterType = null) {
    const res = await fetch('/api/branches', {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    });

    const data = await res.json();

    let options = '<option value="">-- pilih cabang --</option>';

    data.forEach(b => {

        if (filterType && b.type !== filterType) return;

        options += `<option value="${b.id}">
            ${b.name} (${b.type === 'ho' ? 'HO' : 'Cabang'})
        </option>`;
    });

    document.getElementById('branch').innerHTML = options;
}

async function saveUser() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const role = document.getElementById('role').value;
    const type = document.getElementById('branchType').value;
    const branch_id = document.getElementById('branch').value;

    let finalBranch = null;

    if (type === 'branch') {
        finalBranch = branch_id;
    }

    if (type === 'ho') {
        // ambil HO otomatis
        const res = await fetch('/api/branches', {
    headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + token
    }
});
        const data = await res.json();
        const ho = data.find(b => b.type === 'ho');
        finalBranch = ho?.id;
    }

    await fetch('/api/users', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({
            name,
            email,
            role,
            branch_id: finalBranch
        })
    });

    closeModal();
    loadUsers();
}
async function activateUser(id) {
    await fetch(`/api/users/${id}/activate`, {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    });

    loadUsers();
}

// ================= DISABLE =================
async function disableUser(id) {
    await fetch(`/api/users/${id}/disable`, {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    });

    loadUsers();
}

// ================= DELETE =================
async function deleteUser(id) {
    if (!confirm('Yakin hapus user ini?')) return;

    await fetch(`/api/users/${id}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    });

    loadUsers();
}

// ================= RESET PASSWORD =================
async function resetPassword(id) {
    if (!confirm('Reset password ke default (123456)?')) return;

    await fetch(`/api/users/${id}/reset-password`, {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    });

    alert('Password berhasil direset!');
}

// ================= MODAL =================
function openModal() {
    document.getElementById('modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

// ================= NAVIGATION =================
function goTo(url){
    window.location.href = url;
}

// ================= SIDEBAR =================
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

// ================= INIT =================

feather.replace();
</script>

</body>
</html>