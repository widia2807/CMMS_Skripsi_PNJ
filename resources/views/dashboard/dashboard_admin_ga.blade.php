<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin GA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100">

@include('components.sidebar')

<div class="flex h-screen">

    <!-- MAIN -->
    <div class="flex-1 md:ml-64">

        <!-- TOPBAR -->
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="font-semibold">Dashboard Admin GA</h1>
            <span id="userInfo" class="text-sm text-gray-600"></span>
        </div>

        <!-- CONTENT -->
        <div class="p-6">

            <!-- CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Total Request</p>
                    <h2 id="totalRequest" class="text-2xl font-bold">0</h2>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Pending</p>
                    <h2 id="pendingRequest" class="text-2xl font-bold text-yellow-500">0</h2>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Approved</p>
                    <h2 id="approvedRequest" class="text-2xl font-bold text-green-600">0</h2>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Total Categories</p>
                    <h2 id="totalCategories" class="text-2xl font-bold text-blue-600">0</h2>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Total Sub Categories</p>
                    <h2 id="totalSubCategories" class="text-2xl font-bold text-purple-600">0</h2>
                </div>

            </div>

            <!-- INFO -->
            <div class="bg-white p-6 rounded-xl shadow mt-6">
                <h2 class="font-semibold mb-2">Halo Admin GA 👋</h2>
                <p class="text-gray-600">
                    Kelola seluruh pengajuan perbaikan, approval, dan monitoring pekerjaan di sini.
                </p>
            </div>

           <div class="bg-white p-6 rounded-xl shadow mt-6">
            <div class="mb-4">
                <h2 class="text-lg font-bold">Pengaturan Kategori Perbaikan</h2>
                <p class="text-sm text-gray-500">
                    Tambahkan dan kelola kategori utama untuk pengajuan perbaikan.
                </p>
            </div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold">Categories</h2>
                    <div class="space-x-2">
                        <button onclick="showAddCategory()" class="bg-blue-500 text-white px-3 py-1 rounded">+ Tambah</button>
                        <button onclick="toggleCategoryList()" class="bg-gray-500 text-white px-3 py-1 rounded">Lihat List</button>
                    </div>
                </div>

                <!-- FORM -->
                <div id="categoryForm" class="hidden mb-4">
                    <input id="categoryName" type="text" placeholder="Nama Category"
                        class="border p-2 rounded w-full mb-2">
                    <button onclick="addCategory()" class="bg-green-500 text-white px-3 py-1 rounded">Simpan</button>
                </div>

                <!-- TABLE -->
                <div id="categoryTableWrapper" class="hidden max-h-60 overflow-y-auto">
                    <table class="w-full text-sm border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">Nama</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTable"></tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow mt-6">
                 <div class="mb-4">
                    <h2 class="text-lg font-bold">Pengaturan Sub Kategori Perbaikan</h2>
                    <p class="text-sm text-gray-500">
                        Tentukan detail kategori agar pengajuan lebih spesifik.
                    </p>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold">Sub Categories</h2>
                    <div class="space-x-2">
                        <button onclick="showAddSubCategory()" class="bg-purple-500 text-white px-3 py-1 rounded">+ Tambah</button>
                        <button onclick="toggleSubCategoryList()" class="bg-gray-500 text-white px-3 py-1 rounded">Lihat List</button>
                    </div>
                </div>

                <!-- FORM -->
                <div id="subCategoryForm" class="hidden mb-4">
                    <select id="subCategoryCategory" class="border p-2 rounded w-full mb-2"></select>
                    <input id="subCategoryName" type="text" placeholder="Nama Sub Category"
                        class="border p-2 rounded w-full mb-2">
                    <button onclick="addSubCategory()" class="bg-green-500 text-white px-3 py-1 rounded">Simpan</button>
                </div>

                <!-- TABLE -->
                <div id="subCategoryTableWrapper" class="hidden max-h-60 overflow-y-auto">
                    <table class="w-full text-sm border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">Sub Category</th>
                                <th class="p-2 text-left">Category</th>
                            </tr>
                        </thead>
                        <tbody id="subCategoryTable"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
const token = localStorage.getItem('token');
localStorage.getItem('token')
const user = JSON.parse(localStorage.getItem('user'));

// 🔥 PROTEKSI LOGIN
if (!token || !user) {
    localStorage.clear();
    window.location.href = '/login';
}

// USER INFO
document.getElementById('userInfo').innerText =
    user.name + ' (' + user.role + ')';

async function loadCategories() {
    try {
        const res = await fetch('/api/categories', {
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await res.json();

        if (!res.ok || !Array.isArray(data)) {
            console.error('CATEGORY ERROR:', data);
            return;
        }

        // TOTAL
        document.getElementById('totalCategories').innerText = data.length;

        // LIST
        const list = document.getElementById('categoryList');
        if (list) {
            list.innerHTML = data.map(c => `<li>• ${c.name}</li>`).join('');
        }

    } catch (err) {
        console.error('ERROR CATEGORY:', err);
    }
}

async function loadSubCategories() {
    try {
        const res = await fetch('/api/sub-categories', {
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await res.json();

        if (!res.ok || !Array.isArray(data)) {
            console.error('SUB CATEGORY ERROR:', data);
            return;
        }

        // TOTAL
        document.getElementById('totalSubCategories').innerText = data.length;

        // LIST
        const list = document.getElementById('subCategoryList');
        if (list) {
            list.innerHTML = data.map(s => `<li>• ${s.name}</li>`).join('');
        }

    } catch (err) {
        console.error('ERROR SUB CATEGORY:', err);
    }
}

function goTo(url) {
    window.location.href = url;
}
function showAddCategory() {
    document.getElementById('categoryForm').classList.toggle('hidden');
}

function toggleCategoryList() {
    document.getElementById('categoryTableWrapper').classList.toggle('hidden');
    loadCategoriesTable();
}

function showAddSubCategory() {
    document.getElementById('subCategoryForm').classList.toggle('hidden');
    loadCategoryDropdown();
}

function toggleSubCategoryList() {
    document.getElementById('subCategoryTableWrapper').classList.toggle('hidden');
    loadSubCategoriesTable();
}
function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/login';
}

function goToDashboard() {
    if (user?.role === 'super_admin') {
        window.location.href = '/dashboard-full';
    } else if (user?.role === 'admin') {
        if (user.system_type === 'lite') {
            window.location.href = '/dashboard-lite';
        } else {
            window.location.href = '/dashboard-admin';
        }
    } else if (user?.role === 'pic') {
        window.location.href = '/dashboard-pic';
    }
}

async function loadCategoriesTable() {
    const res = await fetch('/api/categories', {
        headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();

    document.getElementById('categoryTable').innerHTML =
        data.map(c => `<tr><td class="p-2">${c.name}</td></tr>`).join('');
}

async function loadSubCategoriesTable() {
    const res = await fetch('/api/sub-categories', {
        headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();

    document.getElementById('subCategoryTable').innerHTML =
        data.map(s => `
            <tr>
                <td class="p-2">${s.name}</td>
                <td class="p-2">${s.category?.name || '-'}</td>
            </tr>
        `).join('');
}

async function addCategory() {
    const name = document.getElementById('categoryName').value;

    await fetch('/api/categories', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({ name })
    });

    alert('Category ditambahkan');
    loadCategories();
}
async function loadCategoryDropdown() {
    const res = await fetch('/api/categories', {
        headers: { Authorization: 'Bearer ' + token }
    });
    const data = await res.json();

    document.getElementById('subCategoryCategory').innerHTML =
        data.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
}

async function addSubCategory() {
    const name = document.getElementById('subCategoryName').value;
    const category_id = document.getElementById('subCategoryCategory').value;

    await fetch('/api/sub-categories', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({ name, category_id })
    });

    alert('Sub Category ditambahkan');
    loadSubCategories();
}


async function loadDashboard() {
    try {
        const res = await fetch('/api/requests', {
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await res.json();

        // ❌ HANDLE ERROR (TOKEN / API)
        if (!res.ok || !Array.isArray(data)) {
            console.error('API ERROR:', data);

            if (res.status === 401) {
                alert('Session habis, login ulang ya');
                localStorage.clear();
                window.location.href = '/login';
            }

            return;
        }

        // ✅ AMAN DIGUNAKAN
        const total = data.length;
        const pending = data.filter(i => i.status === 'pending').length;
        const approved = data.filter(i => i.status === 'approved').length;

        document.getElementById('totalRequest').innerText = total;
        document.getElementById('pendingRequest').innerText = pending;
        document.getElementById('approvedRequest').innerText = approved;

    } catch (err) {
        console.error('ERROR FETCH:', err);
        alert('Gagal koneksi ke server');
    }
}

// INIT
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadDashboard();
    loadCategories();       
    loadSubCategories();   
});
</script>

</body>
</html>