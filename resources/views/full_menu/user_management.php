<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>


<body class="bg-gray-100">

<div class="flex h-screen">

    <!-- SIDEBAR -->
   <div id="sidebar"
     class="fixed z-50 inset-y-0 left-0 w-64 bg-gray-900 text-white p-5 transform -translate-x-full md:translate-x-0 transition duration-300">

        <button onclick="toggleSidebar()" class="absolute top-4 right-4 text-white md:hidden">
            ✖
        </button>

        <h2 class="text-xl font-bold mb-10">CMMS</h2>

        <ul class="space-y-3 text-sm">

            <li onclick="goTo('/dashboard-full')"
                class="flex items-center gap-3 p-2 hover:bg-gray-800 cursor-pointer">
                <i data-feather="home"></i> Dashboard
            </li>

            <li onclick="goTo('/cabang')"
                class="flex items-center gap-3 p-2 hover:bg-gray-800 cursor-pointer">
                <i data-feather="briefcase"></i> Cabang
            </li>

            <li onclick="goTo('/users')"
                class="menu-item p-2 rounded flex items-center gap-3 cursor-pointer hover:bg-gray-800 transition duration-200">
                <i data-feather="users"></i> User Management
            </li>
            
            <li class="menu-item p-2 rounded flex items-center gap-3 cursor-pointer hover:bg-gray-800 transition duration-200">
                <i data-feather="file-text"></i> Laporan
            </li>

            <li class="menu-item p-2 rounded flex items-center gap-3 cursor-pointer hover:bg-gray-800 transition duration-200">
                <i data-feather="settings"></i> Settings
            </li>
        </ul>
    </div>
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
            <option value="admin">Admin</option>
            <option value="pic">PIC</option>
            <option value="technician">Technician</option>
        </select>

        <select id="branch" class="w-full border p-2 mb-4"></select>

        <button onclick="saveUser()" class="bg-blue-600 text-white w-full p-2 rounded">
            Simpan
        </button>

    </div>
</div>

<script>
const token = localStorage.getItem('token');

// ================= LOAD USERS =================
async function loadUsers() {
    const res = await fetch('/api/users', {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    });

    const data = await res.json();

    let html = '';

    data.forEach(user => {
        html += `
        <tr>
            <td class="p-2">${user.name}</td>
            <td class="p-2">${user.email}</td>
            <td class="p-2">${user.role}</td>
            <td class="p-2">${user.branch?.name ?? '-'}</td>

            <td class="p-2">
                ${user.status === 'active'
                    ? '<span class="text-green-600">Active</span>'
                    : '<span class="text-red-500">Inactive</span>'}
            </td>

            <td class="p-2 flex gap-2">
                <button onclick="toggleUser(${user.id})" class="bg-yellow-500 px-2 text-white rounded">Toggle</button>
                <button onclick="resetPassword(${user.id})" class="bg-blue-500 px-2 text-white rounded">Reset</button>
                <button onclick="deleteUser(${user.id})" class="bg-red-500 px-2 text-white rounded">Delete</button>
            </td>
        </tr>`;
    });

    document.getElementById('userTable').innerHTML = html;
}

// ================= LOAD BRANCH =================
async function loadBranch() {
    const res = await fetch('/api/branches', {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    });

    const data = await res.json();

    let options = '<option value="">-- pilih cabang --</option>';

    data.forEach(b => {
        options += `<option value="${b.id}">${b.name}</option>`;
    });

    document.getElementById('branch').innerHTML = options;
}

// ================= SAVE USER =================
async function saveUser() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const role = document.getElementById('role').value;
    const branch_id = document.getElementById('branch').value;

    await fetch('/api/users', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({ name, email, role, branch_id })
    });

    closeModal();
    loadUsers();
}

// ================= TOGGLE =================
async function toggleUser(id) {
    await fetch(`/api/users/${id}/toggle`, {
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
    if (!confirm('Hapus user?')) return;

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
    await fetch(`/api/users/${id}/reset-password`, {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    });

    alert('Password direset ke 123456');
}

// ================= MODAL =================
function openModal() {
    document.getElementById('modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

function goTo(url){
    window.location.href = url;
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}
// INIT
loadUsers();
loadBranch();
feather.replace();

</script>

</body>
</html>