<!DOCTYPE html>
<html>
 <button onclick="toggleSidebar()" 
    class="md:hidden mb-4 bg-gray-800 text-white px-3 py-2 rounded">
    ☰ 
</button>   
<head>
    <title>Request List</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<style>
@keyframes scaleIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.animate-scaleIn {
    animation: scaleIn 0.2s ease;
}
</style>
<body class="bg-gray-50 font-[Inter]">

@include('components.sidebar')

<div class="flex h-screen">

    <!-- CONTENT -->
    <div class="flex-1 md:ml-64 p-6 overflow-auto">

        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Perbaikan Gedung</h1>
            <p class="text-sm text-gray-500">Monitoring & approval request</p>
        </div>

        <!-- CARD LIST -->
        <div id="requestTable" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>

    </div>

</div>

<!-- DETAIL PANEL -->
<div id="detailPanel"
    class="fixed top-0 right-0 w-full sm:w-[420px] h-full bg-white shadow-xl transform translate-x-full transition duration-300 z-50 flex flex-col">

    <!-- HEADER -->
    <div class="flex items-center justify-between p-4 border-b">
        <h2 class="font-semibold text-gray-800">Detail</h2>
        <button onclick="closeDetail()" class="text-xl">✕</button>
    </div>

    <!-- CONTENT -->
    <div id="detailContent" class="flex-1 overflow-auto p-4"></div>

    <!-- ACTION BUTTON (STICKY BAWAH) -->
    <div id="detailAction" class="p-4 border-t space-y-2 bg-white">
        <!-- nanti diisi JS -->
    </div>

</div>
<!-- OVERLAY -->
<div id="overlay" 
    onclick="closeDetail()" 
    class="fixed inset-0 bg-black/40 hidden z-40">
</div>

<!-- IMAGE MODAL -->
<div id="imageModal"
    class="fixed inset-0 bg-black/80 flex items-center justify-center hidden z-50">

    <img id="modalImage" class="max-w-[90%] max-h-[90%] rounded-xl shadow-lg">

    <button onclick="closeImage()" 
        class="absolute top-5 right-5 text-white text-3xl">✕</button>
</div>

<div id="approveModal" 
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white w-[90%] max-w-sm rounded-2xl p-5 shadow-xl animate-scaleIn">

        <h3 class="font-semibold text-gray-800 mb-4 text-center">
            Pilih Tingkat Urgensi
        </h3>

        <div class="space-y-3">

            <!-- LOW -->
            <button onclick="submitApprove('low')" 
                class="w-full flex items-center justify-between px-4 py-3 rounded-xl border hover:bg-gray-100 transition">
                <span class="font-medium">Santai</span>
                <span class="text-xs text-gray-400">Low</span>
            </button>

            <!-- MEDIUM -->
            <button onclick="submitApprove('medium')" 
                class="w-full flex items-center justify-between px-4 py-3 rounded-xl bg-yellow-400 text-white hover:bg-yellow-500 transition">
                <span class="font-medium">Segera</span>
                <span class="text-xs opacity-80">Medium</span>
            </button>

            <!-- HIGH -->
            <button onclick="submitApprove('high')" 
                class="w-full flex items-center justify-between px-4 py-3 rounded-xl bg-red-500 text-white hover:bg-red-600 transition">
                <span class="font-medium">Prioritas</span>
                <span class="text-xs opacity-80">High</span>
            </button>

        </div>

        <button onclick="closeApproveModal()" 
            class="mt-4 w-full text-gray-500 text-sm hover:underline">
            Batal
        </button>

    </div>
</div>
<script>

const token = localStorage.getItem('token');

async function loadRequests() {
    const res = await fetch('/api/requests', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    });

    const data = await res.json();

    let html = '';

    data.forEach(item => {
        html += `
        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition">

            <div class="flex justify-between items-start mb-2">
                <h3 class="font-semibold text-gray-800">${item.title}</h3>

                <span class="text-xs px-2 py-1 rounded 
                    ${item.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                      item.status === 'approved' ? 'bg-green-100 text-green-700' : 
                      'bg-red-100 text-red-700'}">
                    ${item.status}
                </span>
            </div>

            <p class="text-xs text-gray-500 mb-2">${item.category}</p>

            <p class="text-sm text-gray-700 mb-3 line-clamp-2">
                ${item.description}
            </p>

            ${item.photo ? `
                <img src="/storage/${item.photo}" 
                     onclick="openImage('/storage/${item.photo}')"
                     class="w-full h-32 object-cover rounded mb-3 cursor-pointer">
            ` : ''}

            <div class="flex flex-wrap gap-2">

                <button onclick="detail(${item.id})"
                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-1 rounded text-xs">
                    Detail
                </button>

                ${item.status === 'pending' ? `
                    <button onclick="approve(${item.id})"
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white py-1 rounded text-xs">
                        Approve
                    </button>

                    <button onclick="reject(${item.id})"
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white py-1 rounded text-xs">
                        Reject
                    </button>
                ` : ''}

            </div>

        </div>
        `;
    });

    document.getElementById('requestTable').innerHTML = html;
}

async function detail(id) {
    const res = await fetch(`/api/requests/${id}`, {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    });

    const data = await res.json();

    // CONTENT
    document.getElementById('detailContent').innerHTML = `
        <div class="space-y-4">

            <div>
                <h3 class="text-lg font-semibold">${data.title}</h3>
                <p class="text-xs text-gray-500">${data.category}</p>
            </div>

            <div class="flex gap-2 text-xs">
                <span class="px-2 py-1 rounded bg-blue-100 text-blue-700">
                    ${data.status}
                </span>
                <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700">
                    ${data.urgency ?? '-'}
                </span>
            </div>

            <div class="bg-gray-100 p-3 rounded text-sm">
                ${data.description}
            </div>

            ${data.photo ? `
                <img src="/storage/${data.photo}" 
                     onclick="openImage('/storage/${data.photo}')"
                     class="w-full h-52 object-cover rounded cursor-pointer">
            ` : ''}

        </div>
    `;

    // ACTION BUTTON (INI YANG PENTING)
    let actionHTML = '';

    if (data.status === 'pending') {
        actionHTML = `
            <div class="flex gap-2">
                <button onclick="approve(${data.id})"
                    class="flex-1 bg-green-500 text-white py-2 rounded">
                    Approve
                </button>
                <button onclick="reject(${data.id})"
                    class="flex-1 bg-red-500 text-white py-2 rounded">
                    Reject
                </button>
            </div>
        `;
    }

    document.getElementById('detailAction').innerHTML = actionHTML;

    // OPEN PANEL
    document.getElementById('detailPanel').classList.remove('translate-x-full');
    document.getElementById('overlay').classList.remove('hidden');
}

function closeDetail() {
    document.getElementById('detailPanel').classList.add('translate-x-full');
    document.getElementById('overlay').classList.add('hidden');
}

async function approve(id) {
    const urgency = prompt("1. rendah\n2. sedang\n3. tinggi");

    if (!urgency) return;

    let urgencyValue = '';

    if (urgency == '1') urgencyValue = 'low';
    else if (urgency == '2') urgencyValue = 'medium';
    else if (urgency == '3') urgencyValue = 'high';
    else return alert('Input salah');

    await fetch(`/api/requests/${id}/approve`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({ urgency: urgencyValue })
    });

    loadRequests();
    closeDetail();
}

async function reject(id) {
    const reason = prompt('Alasan reject:');
    if (!reason) return;

    await fetch(`/api/requests/${id}/reject`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({ reason })
    });

    loadRequests();
    closeDetail();
}

function openImage(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImage() {
    document.getElementById('imageModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadRequests();
});

let selectedId = null;

function approve(id) {
    selectedId = id;

    const modal = document.getElementById('approveModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex'); 
}

function closeApproveModal() {
    const modal = document.getElementById('approveModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

async function submitApprove(level) {
    await fetch(`/api/requests/${selectedId}/approve`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({ urgency: level })
    });

    closeApproveModal();
    loadRequests();
    closeDetail();
}

</script>

</body>
</html>