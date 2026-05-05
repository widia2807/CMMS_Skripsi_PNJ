<!DOCTYPE html>
<html>
<head>
    <title>Status Pengajuan</title>
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
            <h1 class="font-semibold">Status Pengajuan</h1>
            <span id="userInfo" class="text-sm text-gray-600"></span>
        </div>

        <!-- CONTENT -->
        <div class="p-6">

            <!-- TABLE -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Judul</th>
                            <th class="p-3 text-left">Kategori</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="list"></tbody>
                </table>
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

// LOAD DATA
async function loadRequests() {
    const list = document.getElementById('list');

    list.innerHTML = '<tr><td colspan="4" class="p-3 text-center">Loading...</td></tr>';

    try {
        const res = await fetch('/api/requests', {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await res.json();

        list.innerHTML = '';

        if (data.length === 0) {
            list.innerHTML = '<tr><td colspan="4" class="p-3 text-center">Belum ada data</td></tr>';
            return;
        }

        data.forEach(item => {

            let statusColor = 'bg-gray-200';

            if (item.status === 'pending') statusColor = 'bg-yellow-200';
            if (item.status === 'approved') statusColor = 'bg-green-200';
            if (item.status === 'rejected') statusColor = 'bg-red-200';
            if (item.status === 'scheduled') statusColor = 'bg-blue-200';
            if (item.status === 'material_ready') statusColor = 'bg-purple-200';

            list.innerHTML += `
                <tr id="row-${item.id}" class="border-b hover:bg-gray-50">
                    <td class="p-3 font-medium">${item.title}</td>
                    <td class="p-3">${item.category}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-xs ${statusColor}">
                            ${item.status}
                        </span>
                    </td>
                    <td class="p-3 text-center">
                        <button onclick="showDetail(${item.id})"
                            class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                            Detail
                        </button>
                    </td>
                </tr>
            `;
        });

    } catch (error) {
        list.innerHTML = '<tr><td colspan="4" class="p-3 text-center text-red-500">Gagal load data</td></tr>';
    }
}

async function showDetail(id) {

    // cek kalau sudah ada → hapus (toggle)
    const existing = document.getElementById(`detail-${id}`);
    if (existing) {
        existing.remove();
        return;
    }

    try {
        const res = await fetch(`/api/requests/${id}`, {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Accept': 'application/json'
            }
        });

        const data = await res.json();

        const row = document.getElementById(`row-${id}`);

        // hapus detail lain (biar cuma 1 terbuka)
        document.querySelectorAll('[id^="detail-"]').forEach(el => el.remove());

        const detailRow = document.createElement('tr');
        detailRow.id = `detail-${id}`;

        detailRow.innerHTML = `
            <td colspan="4" class="p-4 bg-gray-50">
                <div class="space-y-2">
                    <p><strong>Judul:</strong> ${data.title}</p>
                    <p><strong>Deskripsi:</strong> ${data.description}</p>
                    <p><strong>Status:</strong> ${data.status}</p>
                    <p><strong>Kategori:</strong> ${data.category}</p>

                    ${data.photo ? `<img src="/storage/${data.photo}" class="mt-3 w-40 rounded">` : ''}
                </div>
            </td>
        `;

        row.insertAdjacentElement('afterend', detailRow);

    } catch (error) {
        alert('Gagal load detail');
    }
}

function goTo(url){
    window.location.href = url;
}
// INIT
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadRequests();
});
</script>

</body>
</html>