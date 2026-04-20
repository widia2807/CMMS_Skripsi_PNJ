<!DOCTYPE html>
<html>
<head>
    <title>Pekerjaan Tukang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    
</head>

<body class="bg-gray-100">

@include('components.sidebar')

<div class="flex h-screen">

    <div class="flex-1 md:ml-64 p-6">

        <h1 class="text-xl font-semibold mb-4">Daftar Pekerjaan</h1>

        <div id="jobList" class="space-y-3"></div>

    </div>

</div>

<div id="detailModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white w-[90%] max-w-md rounded-xl p-4">

        <h3 class="font-semibold text-lg mb-2">Detail Pekerjaan</h3>

        <div id="detailContent" class="space-y-2"></div>

        <button onclick="closeDetail()"
            class="mt-4 w-full bg-gray-500 text-white py-2 rounded">
            Tutup
        </button>

    </div>
</div>

<div id="imageModal"
    class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">

    <img id="modalImage" class="max-w-[90%] max-h-[90%] rounded">

    <button onclick="closeImage()"
        class="absolute top-5 right-5 text-white text-2xl">✕</button>
</div>

<div id="materialModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white p-5 rounded-xl w-[90%] max-w-md">

        <h3 class="font-semibold mb-3">Ajukan Material</h3>

        <div id="materialList" class="space-y-2">
            <div class="flex gap-2">
                <input type="text" placeholder="Nama barang" class="border p-2 w-full name">
                <input type="number" placeholder="Qty" class="border p-2 w-20 qty">

                <select class="border p-2 unit">
                    <option value="pcs">pcs</option>
                    <option value="meter">meter</option>
                    <option value="kg">kg</option>
                </select>
            </div>
        </div>

        <button onclick="addMaterialField()" class="text-blue-500 text-sm mt-2">
            + Tambah item
        </button>

        <button onclick="submitMaterial()" class="w-full bg-green-500 text-white py-2 mt-3 rounded">
            Submit
        </button>

        <button onclick="closeMaterialModal()" class="w-full mt-2 text-gray-500">
            Batal
        </button>

    </div>
</div>

<script>
const token = localStorage.getItem('token');

if (!token) {
    window.location.href = '/login';
}
const statusLabel = {
    'approved': 'Disetujui',
    'scheduled': 'Terjadwal',
    'waiting_material': 'Menunggu Material',
    'on_progress': 'Sedang Dikerjakan',
    'done': 'Selesai',
    'material_ready': 'Material Siap',
    'verified': 'Terverifikasi'
};
// ================= LOAD JOB =================
async function loadJobs() {
    try {
        const res = await fetch('/api/technician/jobs', {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await res.json();
        window.jobData = data;

        if (!res.ok) {
            alert(data.message || 'Gagal load data');
            return;
        }

        let html = '';

        data.forEach(job => {
            let action = '';

            // APPROVED → SCHEDULE
            if (job.status === 'approved') {
                action = `
                    <input type="date" id="date-${job.id}" class="border p-1 rounded">
                    <button onclick="setSchedule(${job.id})" class="bg-blue-500 text-white px-2 py-1 rounded">
                        Jadwalkan
                    </button>
                `;
            }

            // SCHEDULED → INSPECTION
            else if (job.status === 'scheduled') {
                action = `
                    <button onclick="inspectJob(${job.id}, false)" class="bg-green-500 text-white px-2 py-1 rounded">
                        Langsung Kerja
                    </button>

                    <button onclick="needMaterial(${job.id})" 
                        class="bg-yellow-500 text-white px-2 py-1 rounded">
                        Butuh Material
                    </button>
                                    `;
            }

            // WAITING MATERIAL
            else if (job.status === 'waiting_material') {
                action = `
                    <button onclick="openMaterialForm(${job.id})" class="bg-orange-500 text-white px-2 py-1 rounded">
                        Ajukan Material
                    </button>

                    <button onclick="startJob(${job.id})" class="bg-green-600 text-white px-2 py-1 rounded">
                        Mulai (Jika Material Ready)
                    </button>

                    <input type="date" id="res-${job.id}" class="border p-1 rounded">
                    <button onclick="reschedule(${job.id})" class="bg-gray-500 text-white px-2 py-1 rounded">
                        Reschedule
                    </button>
                `;
            }
            else if (job.status === 'material_ready') {
                action = `
                    <button onclick="startJob(${job.id})"
                        class="bg-green-600 text-white px-2 py-1 rounded">
                        Mulai Kerja
                    </button>
                `;
            }

            // ON PROGRESS
            else if (job.status === 'on_progress') {
                action = `
                    <button onclick="completeJob(${job.id})" class="bg-purple-500 text-white px-2 py-1 rounded">
                        Selesai
                    </button>
                `;
            }

            html += `
                <div class="bg-white p-4 rounded shadow">
                   <h3 class="font-semibold">${job.title ?? '-'}</h3>

                    <p class="text-xs text-gray-400">🏢 ${job.branch ?? '-'}</p>

                    <p class="text-sm text-gray-500">${job.category ?? '-'}</p>

                    ${job.photo ? `
                        <img src="/storage/${job.photo}" 
                            class="w-full h-32 object-cover rounded mt-2">
                    ` : ''}
                    <p class="text-xs text-gray-400 mb-2">Status: ${statusLabel[job.status] ?? job.status}</p>

                    <div class="flex gap-2 flex-wrap">
                        ${action}
                    </div>
                    <button onclick="openDetail(${job.id})"
        class="bg-gray-800 text-white px-2 py-1 rounded">
        Detail
    </button>
                </div>
            `;
        });

        document.getElementById('jobList').innerHTML = html;

    } catch (err) {
        console.error(err);
        alert('Terjadi error');
    }
}

async function needMaterial(id) {
    const res = await fetch(`/api/technician/inspect/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({
            needs_material: true,
            notes: 'Butuh material'
        })
    });

    const data = await res.json();

    if (!res.ok) return alert(data.message);

    // 🔥 langsung buka form
    openMaterialForm(id);
}

function openDetail(id) {
    const job = window.jobData.find(j => j.id === id);

    if (!job) return;

    document.getElementById('detailContent').innerHTML = `
        <h4 class="font-semibold">${job.title}</h4>

        <p class="text-sm text-gray-500">🏢 ${job.branch}</p>

        <p class="text-sm">${job.category}</p>

        <p class="text-sm text-gray-700">${job.description}</p>

        ${job.photo ? `
            <img src="/storage/${job.photo}" 
                onclick="openImage('/storage/${job.photo}')"
                class="w-full h-40 object-cover rounded cursor-pointer">
        ` : ''}

        <p class="text-xs text-gray-400">
            Status: ${statusLabel[job.status] ?? job.status}
        </p>
    `;

    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
}

function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
}


function openImage(src) {
    document.getElementById('modalImage').src = src;

    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function closeImage() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
}

async function setSchedule(id) {
    const date = document.getElementById(`date-${id}`).value;

    if (!date) return alert('Pilih tanggal dulu!');

    const res = await fetch(`/api/technician/schedule/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({ schedule_date: date })
    });

    const data = await res.json();

    if (!res.ok) return alert(data.message);

    alert('Jadwal berhasil diset');
    loadJobs();
}

async function inspectJob(id, needsMaterial) {
    const res = await fetch(`/api/technician/inspect/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({
            needs_material: needsMaterial,
            notes: 'Hasil pengecekan'
        })
    });

    const data = await res.json();

    if (!res.ok) return alert(data.message);

    alert('Inspection selesai');
    loadJobs();
}

async function startJob(id) {
    const res = await fetch(`/api/technician/start/${id}`, {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token
        }
    });

    const data = await res.json();

    if (!res.ok) return alert(data.message);

    alert('Pekerjaan dimulai');
    loadJobs();
}

async function completeJob(id) {
    const res = await fetch(`/api/technician/complete/${id}`, {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token
        }
    });

    const data = await res.json();

    if (!res.ok) return alert(data.message);

    alert('Pekerjaan selesai');
    loadJobs();
}

async function reschedule(id) {
    const date = document.getElementById(`res-${id}`).value;

    if (!date) return alert('Pilih tanggal dulu!');

    const res = await fetch(`/api/technician/schedule/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({ schedule_date: date })
    });

    const data = await res.json();

    if (!res.ok) return alert(data.message);

    alert('Reschedule berhasil');
    loadJobs();
}

function openMaterialForm(id) {
    selectedId = id;

    const modal = document.getElementById('materialModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function addMaterialField() {
    const div = document.createElement('div');
    div.className = 'flex gap-2 mt-2';

    div.innerHTML = `
    <input type="text" placeholder="Nama barang" class="border p-2 w-full name">
    <input type="number" placeholder="Qty" class="border p-2 w-20 qty">
    
    <select class="border p-2 unit">
        <option value="pcs">pcs</option>
        <option value="meter">meter</option>
        <option value="kg">kg</option>
    </select>
`;

    document.getElementById('materialList').appendChild(div);
}

async function submitMaterial() {
    const names = document.querySelectorAll('.name');
    const qtys = document.querySelectorAll('.qty');
    const units = document.querySelectorAll('.unit');

    let items = [];

    for (let i = 0; i < names.length; i++) {
    if (names[i].value && qtys[i].value) {
        items.push({
            name: names[i].value,
            qty: qtys[i].value,
            unit: units[i].value
        });
    }
}

    if (items.length === 0) return alert('Isi minimal 1 item');

    const res = await fetch(`/api/technician/material/${selectedId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({ items })
    });

    const data = await res.json();

    if (!res.ok) return alert(data.message);

    alert('Material diajukan');
    closeMaterialModal();
    loadJobs();
}
function closeMaterialModal() {
    const modal = document.getElementById('materialModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}
function goTo(url) {
    window.location.href = url;
}
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadJobs();
});
</script>

</body>
</html>