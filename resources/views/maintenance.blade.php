<!DOCTYPE html>
<html>
<head>
    <title>Maintenance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100">

@include('components.sidebar')

<div class="flex h-screen">

    <!-- MAIN -->
    <div class="flex-1 md:ml-64 p-6">

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Maintenance Request</h1>
            <span id="userInfo" class="text-sm text-gray-600"></span>
        </div>

        <!-- LIST -->
        <div id="requestList" class="space-y-4"></div>

    </div>
</div>

<!-- MODAL REJECT -->
<div id="rejectModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">

    <div class="bg-white p-6 rounded w-96">
        <h2 class="font-semibold mb-3">Alasan Reject</h2>

        <textarea id="rejectReason"
            class="w-full border p-2 mb-3 rounded"></textarea>

        <div class="flex justify-end gap-2">
            <button onclick="closeModal()"
                class="px-3 py-1 bg-gray-300 rounded">Batal</button>

            <button onclick="confirmReject()"
                class="px-3 py-1 bg-red-600 text-white rounded">Reject</button>
        </div>
    </div>
</div>

<script>
const token = localStorage.getItem('token');
const user = JSON.parse(localStorage.getItem('user'));

if (!user) {
    window.location.href = '/login';
}

document.getElementById('userInfo').innerText =
    user.name + ' (' + user.role + ')';

let selectedId = null;

// 🔥 LOAD REQUEST
async function loadRequests() {
    const res = await fetch('/api/requests', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    });

    const data = await res.json();

    const container = document.getElementById('requestList');
    container.innerHTML = '';

    if (data.length === 0) {
        container.innerHTML = '<p class="text-gray-500">Tidak ada request</p>';
        return;
    }

    data.forEach(item => {
        container.innerHTML += `
            <div class="bg-white p-4 rounded-xl shadow">

                <h2 class="font-semibold text-lg">${item.title}</h2>
                <p class="text-sm text-gray-600">${item.description}</p>

                <p class="text-xs text-gray-400 mt-1">
                    Status: ${item.status}
                </p>

                ${item.photo ? `
                    <img src="/storage/${item.photo}"
                        class="w-40 mt-2 rounded border"/>
                ` : ''}

                <div class="flex gap-2 mt-4">

                    <button onclick="approve(${item.id})"
                        class="bg-green-600 text-white px-3 py-1 rounded">
                        Approve
                    </button>

                    <button onclick="openReject(${item.id})"
                        class="bg-red-600 text-white px-3 py-1 rounded">
                        Reject
                    </button>

                </div>

            </div>
        `;
    });
}


async function approve(id) {
    await fetch(`/api/requests/${id}/approve`, {
        method: 'PUT',
        headers: {
            'Authorization': 'Bearer ' + token
        }
    });

    alert('Request approved');
    loadRequests();
}

function goTo(url){
    window.location.href = url;
}
function openReject(id) {
    selectedId = id;
    document.getElementById('rejectModal').classList.remove('hidden');
}


function closeModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}


async function confirmReject() {
    const reason = document.getElementById('rejectReason').value;

    if (!reason) {
        alert('Alasan wajib diisi!');
        return;
    }

    await fetch(`/api/requests/${selectedId}/reject`, {
        method: 'PUT',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ reason })
    });

    alert('Request rejected');
    closeModal();
    loadRequests();
}

// INIT
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadRequests();
});
</script>

</body>
</html>