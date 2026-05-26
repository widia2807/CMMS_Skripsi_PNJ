<!DOCTYPE html>
<html>
<head>
    <title>Peminjaman Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        tr:hover td { background: #f8fafc; }

        .btn {
            transition: all 0.15s ease;
        }
        .btn:hover { transform: translateY(-1px); }

        .card {
            background: white;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
    </style>
</head>

<body class="bg-slate-50">

@include('components.sidebar')

<div class="flex min-h-screen">
<div class="flex-1 md:ml-64">

<!-- TOPBAR -->
<div class="bg-white border-b px-8 py-4">
    <h1 class="font-bold text-lg text-slate-800">Peminjaman Alat</h1>
    <p class="text-xs text-slate-400">Ajukan dan pantau peminjaman alat</p>
</div>

<div class="p-8">

<!-- ACTION -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-slate-800">Daftar Peminjaman</h2>

    <button onclick="openBorrowModal()" 
        class="btn flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm">
        <i data-feather="plus" class="w-4 h-4"></i>
        Ajukan
    </button>
</div>

<!-- TABLE -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b">
            <th class="px-5 py-3 text-left text-xs text-slate-500 uppercase">Asset & Alasan</th>
            <th class="px-5 py-3 text-left text-xs text-slate-500 uppercase">Tanggal</th>
            <th class="px-5 py-3 text-left text-xs text-slate-500 uppercase">Status</th>
        </tr>
        </thead>
        <tbody id="borrowTable"></tbody>
    </table>
    </div>

    <!-- EMPTY -->
    <div id="emptyState" class="hidden text-center py-16 text-slate-400">
        Belum ada pengajuan
    </div>
</div>

</div>
</div>
</div>

<!-- MODAL -->
<div id="borrowModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
<div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">

    <div class="flex justify-between items-center px-6 py-4 border-b">
        <h2 class="font-bold text-slate-800">Ajukan Peminjaman</h2>
        <button onclick="closeBorrowModal()">✖</button>
    </div>

    <div class="p-6 space-y-3">

        <select id="asset_id" class="w-full border rounded-lg px-3 py-2 text-sm"></select>

        <input type="date" id="start_date" class="w-full border rounded-lg px-3 py-2 text-sm">
        <input type="date" id="end_date" class="w-full border rounded-lg px-3 py-2 text-sm">

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-xs text-slate-500 block mb-1">Jumlah</label>
                <input type="number" id="qty" min="1" value="1"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="text-xs text-slate-500 block mb-1">Alasan Peminjaman</label>
                <input type="text" id="reason" placeholder="Cth: Untuk event..."
                    class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <textarea id="notes" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Keterangan"></textarea>

        <button onclick="submitBorrow()" 
            class="btn w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold text-sm">
            Submit
        </button>

    </div>
</div>
</div>


<script>
const token = localStorage.getItem('token');

function statusBadge(s){
    const map = {
        requested:'bg-yellow-50 text-yellow-700 border-yellow-200',
        approved:'bg-blue-50 text-blue-700 border-blue-200',
        picked:'bg-indigo-50 text-indigo-700 border-indigo-200',
        returned:'bg-green-50 text-green-700 border-green-200'
    };

    return `<span class="px-2 py-1 text-xs rounded-full border ${map[s] || 'bg-slate-100'}">${s}</span>`;
}

function openBorrowModal(){
    document.getElementById('borrowModal').classList.remove('hidden');
    loadAssets();
}

function closeBorrowModal(){
    document.getElementById('borrowModal').classList.add('hidden');
}

async function loadAssets(){
    const data = await fetch('/api/assets', {
        headers:{Authorization:'Bearer '+token}
    }).then(r=>r.json());

    document.getElementById('asset_id').innerHTML =
        data.map(a => `<option value="${a.id}">${a.name}</option>`).join('');
}

async function submitBorrow(){
    const payload = {
        asset_id: document.getElementById('asset_id').value,
        start_date: document.getElementById('start_date').value,
        end_date: document.getElementById('end_date').value,
        qty: document.getElementById('qty').value,           
        reason: document.getElementById('reason').value,
        notes: document.getElementById('notes').value
    };

    const res = await fetch('/api/borrowings',{
        method:'POST',
        headers:{
            Authorization:'Bearer '+token,
            'Content-Type':'application/json'
        },
        body: JSON.stringify(payload)
    });

    if(!res.ok){
        alert('Gagal!');
        return;
    }

    alert('Berhasil!');
    closeBorrowModal();
    loadBorrowings();
}

async function loadBorrowings(){
    const data = await fetch('/api/borrowings/my',{
        headers:{Authorization:'Bearer '+token}
    }).then(r=>r.json());

    const table = document.getElementById('borrowTable');
    const empty = document.getElementById('emptyState');

    if(!data.length){
        table.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');

    table.innerHTML = data.map(b=>`
    <tr class="border-b">
        <td class="px-5 py-4">
            <p class="font-semibold text-slate-700">${b.asset?.name ?? '-'}</p>
            ${b.qty ? `<p class="text-xs text-slate-400 mt-0.5">Qty: ${b.qty}</p>` : ''}
            ${b.reason ? `<p class="text-xs text-slate-400 italic">"${b.reason}"</p>` : ''}
        </td>
        <td class="px-5 py-4 text-slate-500 text-xs">
            <p>${b.start_date} - ${b.end_date}</p>
            ${b.notes ? `<p class="mt-0.5 text-slate-400">${b.notes}</p>` : ''}
        </td>
        <td class="px-5 py-4">${statusBadge(b.status)}</td>
    </tr>
`).join('');
}
function goTo(url) {
    window.location.href = url;
}
loadBorrowings();
feather.replace();
</script>

</body>
</html>