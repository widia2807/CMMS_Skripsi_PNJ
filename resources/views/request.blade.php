<!DOCTYPE html>
<html>
<head>
    <title>Ajukan Perbaikan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100">
@include('components.sidebar')
<div class="flex h-screen">

    <!-- MAIN -->
    <div class="flex-1 md:ml-64">

        <!-- TOPBAR -->
        <div class="bg-white shadow px-6 py-4 flex justify-between">
            <button onclick="toggleSidebar()" class="md:hidden">
                <i data-feather="menu"></i>
            </button>
            <h1 class="font-semibold">Ajukan Perbaikan</h1>
            <span id="userInfo" class="text-sm text-gray-600"></span>
        </div>

        <!-- FORM -->
        <div class="p-6">

            <div class="bg-white p-6 rounded-xl shadow max-w-xl">

                <h2 class="font-semibold mb-4">Form Pengajuan</h2>

                <!-- TITLE -->
                <input id="title" placeholder="Judul Masalah"
                    class="w-full border p-2 mb-3 rounded">

                <!-- CATEGORY -->
                <select id="category" onchange="updateSubCategory()"
                    class="w-full border p-2 mb-3 rounded">
                    <option value="">Pilih Kategori</option>
                    <option value="kelistrikan">Kelistrikan</option>
                    <option value="sipil">Sipil</option>
                </select>

                <!-- SUB CATEGORY -->
                <select id="sub_category"
                    class="w-full border p-2 mb-3 rounded">
                    <option value="">Pilih Jenis Kerusakan</option>
                </select>

                <!-- DESCRIPTION -->
                <textarea id="description" placeholder="Deskripsi"
                    class="w-full border p-2 mb-3 rounded"></textarea>

                <!-- PHOTO -->
                <input type="file" id="photo" class="mb-4">

                <!-- BUTTON -->
                <button onclick="submitRequest()"
                    class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                    Submit
                </button>

            </div>

        </div>

    </div>

</div>

<script>
    
const user = JSON.parse(localStorage.getItem('user'));

if (!user) {
    window.location.href = '/login';
}

document.getElementById('userInfo').innerText =
    user.name + ' (' + user.role + ')';
async function updateSubCategory() {
    const category = document.getElementById('category').value;
    const sub = document.getElementById('sub_category');

    // 🔥 handle kosong
    if (!category) {
        sub.innerHTML = '<option>Pilih kategori dulu</option>';
        return;
    }

    sub.innerHTML = '<option>Loading...</option>';

    const res = await fetch(`/api/sub-categories/${category}`, {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            'Accept': 'application/json'
        }
    });

    // 🔥 handle error
    if (!res.ok) {
        sub.innerHTML = '<option>Gagal load data</option>';
        return;
    }

    const data = await res.json();

    sub.innerHTML = '<option value="">Pilih Jenis Kerusakan</option>';

    data.forEach(item => {
        const label = item.replaceAll('_', ' ');
        sub.innerHTML += `<option value="${item}">${label}</option>`;
    });
}

// SUBMIT
async function submitRequest() {
    const formData = new FormData();

    formData.append('title', document.getElementById('title').value);
    formData.append('description', document.getElementById('description').value);
    formData.append('category', document.getElementById('category').value);
    formData.append('sub_category', document.getElementById('sub_category').value);

    const file = document.getElementById('photo').files[0];
    if (file) {
        formData.append('photo', file);
    }

    const res = await fetch('/api/requests', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            'Accept': 'application/json'
        },
        body: formData
    });

    const data = await res.json();
    console.log('RESPONSE:', data);

    // 🔥 INI KUNCINYA
    if (!res.ok) {
        alert('Error: ' + JSON.stringify(data.errors || data.message));
        return;
    }

    alert('Pengajuan berhasil!');
    window.location.href = '/status';
}

// NAV
function goTo(url){
    window.location.href = url;
}
function goToDashboard() {
    const user = JSON.parse(localStorage.getItem('user'));

    if (user?.role === 'pic') {
        window.location.href = '/dashboard-pic';
    } else if (user?.system_type === 'lite') {
        window.location.href = '/dashboard-lite';
    } else {
        window.location.href = '/dashboard-full';
    }
}
function logout(){ localStorage.clear(); window.location.href='/login'; }
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
}

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
});

</script>

</body>
</html>