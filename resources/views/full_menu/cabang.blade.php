<!DOCTYPE html>
<html>
<head>
    <title>Cabang</title>
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

            <h1 class="font-semibold">Manajemen Cabang</h1>

            <span class="text-sm text-gray-600">Super Admin</span>
        </div>

        <!-- CONTENT -->
        <div class="p-6">

    <!-- HEADER ACTION -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-4">

        <h2 class="text-lg font-semibold">Daftar Cabang & HO</h2>

        <div class="flex gap-2">
           <input type="file" id="fileInput"
            onchange="importFile()"
            class="text-sm border p-2 rounded">

            <button onclick="openModal()"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                + Tambah
            </button>
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody id="branchTable"></tbody>
        </table>
    </div>

</div>

    </div>
</div>

<!-- MODAL -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center" onclick="closeModalOutside(event)">
    <div class="bg-white p-6 rounded-xl w-80" onclick="event.stopPropagation()">

        <h2 class="font-semibold mb-3">Tambah Cabang / HO</h2>

        <input id="branchName"
            placeholder="Nama"
            class="w-full p-2 border rounded mb-3">

        <select id="branchType"
            class="w-full p-2 border rounded mb-4">
            <option value="branch">Cabang</option>
            <option value="ho">Head Office (HO)</option>
        </select>

        <button onclick="createBranch()"
            class="bg-blue-600 text-white px-4 py-2 rounded w-full">
            Simpan
        </button>

    </div>
</div>

<script>
 const token = localStorage.getItem('token');
const user = JSON.parse(localStorage.getItem('user'));

if (!user) {
    window.location.href = '/login';
}


// Proteksi login
if (!user || !token) {
    window.location.href = '/login';
}

function isLite() {
    return user?.system_type === 'lite';
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
// MODAL
function openModal() {
    document.getElementById('modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

function closeModalOutside(e) {
    if (e.target.id === 'modal') closeModal();
}

// LOAD DATA
async function loadBranches() {
    const token = localStorage.getItem('token');

    if (!token) {
        console.error('Token tidak ada, silakan login dulu');
        return;
    }

    const res = await fetch('/api/branches', {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    });

    const text = await res.text();

    try {
        const data = JSON.parse(text);

        if (!res.ok) {
            console.error('API Error:', data);
            return;
        }

        let html = '';

        data.forEach(branch => {
            html += `
            <tr>
                <td class="p-3 border-b">${branch.name}</td>

                <td class="p-3 border-b">
                    ${branch.type === 'ho'
                        ? '<span class="text-blue-600 font-semibold">HO</span>'
                        : '<span class="text-gray-600">Cabang</span>'}
                </td>

                <td class="p-3 border-b">
                    ${branch.status === 'active'
                        ? '<span class="text-green-600 font-semibold">Active</span>'
                        : '<span class="text-red-500 font-semibold">Disabled</span>'}
                </td>

                <td class="p-3 border-b flex gap-2">

                    <button onclick="toggleBranch(${branch.id})"
                        class="px-3 py-1 text-white rounded
                        ${branch.status === 'active'
                            ? 'bg-red-500'
                            : 'bg-green-600'}">
                        ${branch.status === 'active'
                            ? 'Disable'
                            : 'Enable'}
                        </button>

                        <button onclick="editBranch(${branch.id}, '${branch.name}', '${branch.type}')"
                            class="px-3 py-1 bg-yellow-500 text-white rounded">
                            Edit
                        </button>

                        <button onclick="deleteBranch(${branch.id})"
                            class="px-3 py-1 bg-gray-800 text-white rounded">
                            Delete
                        </button>

                            </td>
             </tr>`;
        });

        document.getElementById('branchTable').innerHTML = html;

    } catch (err) {
        console.error('Response bukan JSON:', text);
    }
}

async function importFile() {
    const file = document.getElementById('fileInput').files[0];

    const formData = new FormData();
    formData.append('file', file);

    const res = await fetch('/api/import-branches', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: formData
    });

    const data = await res.json();
    console.log('FULL ERROR:', data.error || data);

    if (res.ok) {
    alert('Import berhasil!');
    } else {
        alert('Import gagal!');
        console.error(data);
    };
    loadBranches();
}

async function toggleBranch(id) {
    await fetch(`/api/branches/${id}/toggle`, {
        method: 'PUT',
        headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token
                }
    });

    loadBranches();
}
// CREATE
async function createBranch() {
    const name = document.getElementById('branchName').value;
    const type = document.getElementById('branchType').value;

    let url = '/api/branches';
    let method = 'POST';

    if (window.editId) {
        url = `/api/branches/${window.editId}`;
        method = 'PUT';
    }

    await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({ name, type })
    });

    window.editId = null;
    closeModal();
    loadBranches();
}

async function deleteBranch(id) {
    if (!confirm('Yakin mau hapus?')) return;

    await fetch(`/api/branches/${id}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    });

    loadBranches();
}
// SIDEBAR
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

function editBranch(id, name, type) {
    document.getElementById('branchName').value = name;
    document.getElementById('branchType').value = type;

    openModal();

    window.editId = id;
}
// INIT
loadBranches();
feather.replace();

</script>

</body>
</html>