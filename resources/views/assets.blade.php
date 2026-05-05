<!DOCTYPE html>
<html>
<head>
    <title>Data Aset</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .badge-baik { background: #d1fae5; color: #065f46; }
        .badge-ringan { background: #fef3c7; color: #92400e; }
        .badge-berat { background: #fee2e2; color: #991b1b; }

        .modal-enter { animation: modalIn 0.2s ease; }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        tr { transition: background 0.15s; }
        tr:hover td { background: #f8fafc; }

        .btn { transition: all 0.15s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }

        input, select {
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        .photo-thumb {
            width: 44px; height: 44px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            transition: transform 0.2s, border-color 0.2s;
        }
        .photo-thumb:hover {
            transform: scale(1.1);
            border-color: #6366f1;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px 24px;
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
<div class="bg-white border-b border-slate-100 px-8 py-4 flex justify-between items-center sticky top-0 z-30">
    <div>
        <h1 class="font-bold text-slate-800 text-lg">Manajemen Aset</h1>
        <p class="text-xs text-slate-400 mt-0.5">Kelola semua data aset perusahaan</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
            <span class="text-indigo-600 font-semibold text-sm">A</span>
        </div>
        <span class="text-sm font-medium text-slate-600">Admin</span>
    </div>
</div>

<div class="p-8">

<!-- ACTION BAR -->
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <h2 class="text-xl font-bold text-slate-800">Daftar Aset</h2>

    <div class="flex flex-wrap gap-2">
        <button onclick="openCategoryModal()" class="btn flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">
            <i data-feather="tag" class="w-4 h-4"></i> Kategori
        </button>
        <button onclick="openSubCategoryModal()" class="btn flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">
            <i data-feather="layers" class="w-4 h-4"></i> Sub Kategori
        </button>
        <a href="/api/assets/export" class="btn flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">
            <i data-feather="download" class="w-4 h-4"></i> Export
        </a>
        <button onclick="openForm()" class="btn flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">
            <i data-feather="plus" class="w-4 h-4"></i> Tambah Aset
        </button>
    </div>
</div>

<!-- TABLE -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100">
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Nama Aset</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Cabang</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Kategori</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Sub Kategori</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Kondisi</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Brand</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Nilai</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Foto</th>
                <th class="px-5 py-3.5 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody id="assetTable"></tbody>
    </table>
    </div>

    <!-- Empty state -->
    <div id="emptyState" class="hidden py-16 text-center">
        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i data-feather="package" class="w-8 h-8 text-slate-400"></i>
        </div>
        <p class="text-slate-500 font-medium">Belum ada data aset</p>
        <p class="text-slate-400 text-xs mt-1">Klik tombol "+ Tambah Aset" untuk menambahkan</p>
    </div>
</div>

</div>
</div>
</div>

<!-- MODAL TAMBAH/EDIT ASET -->
<div id="modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
<div class="bg-white rounded-2xl w-full max-w-md shadow-2xl modal-enter max-h-[90vh] overflow-y-auto">

    <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
        <h2 class="font-bold text-slate-800" id="modalTitle">Tambah Aset</h2>
        <button onclick="closeForm()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
            <i data-feather="x" class="w-4 h-4"></i>
        </button>
    </div>

    <div class="p-6 space-y-3">
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Nama Asset <span class="text-red-500">*</span></label>
            <input id="name" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm" placeholder="Nama Asset">
        </div>
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Lokasi</label>
            <input id="location" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm" placeholder="Lokasi">
        </div>
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Kondisi <span class="text-red-500">*</span></label>
            <select id="condition" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm">
                <option value="">Pilih Kondisi</option>
                <option value="baik">Baik</option>
                <option value="rusak ringan">Rusak Ringan</option>
                <option value="rusak berat">Rusak Berat</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Kategori</label>
                <select id="category_id" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm"></select>
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Sub Kategori</label>
                <select id="sub_category_id" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm">
                    <option value="">Pilih Sub</option>
                </select>
            </div>
        </div>
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Cabang</label>
            <select id="branch_id" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm"></select>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Brand</label>
                <input id="brand" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm" placeholder="Optional">
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Tahun</label>
                <input id="acquisition_year" type="number" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm" placeholder="Optional">
            </div>
        </div>
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Nilai (Rp)</label>
            <input id="value" type="number" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm" placeholder="Optional">
        </div>
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1 block">Foto</label>
            <input type="file" id="photo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-600 file:text-sm file:font-medium hover:file:bg-slate-200">
        </div>
        <button onclick="saveAsset()" class="btn w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold text-sm mt-2">
            Simpan Aset
        </button>
    </div>
</div>
</div>

<!-- CATEGORY MODAL -->
<div id="categoryModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
<div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl modal-enter">
    <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
        <div>
            <h2 class="font-bold text-slate-800">Tambah Kategori</h2>
            <p class="text-xs text-slate-400 mt-0.5">Kategori utama aset</p>
        </div>
        <button onclick="closeCategoryModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
            <i data-feather="x" class="w-4 h-4"></i>
        </button>
    </div>
    <div class="p-6">
        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 block">Nama Kategori</label>
        <input id="new_category" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm mb-4" placeholder="Contoh: Furniture, Elektronik...">
        <button onclick="saveCategory()" class="btn w-full bg-purple-600 hover:bg-purple-700 text-white py-2.5 rounded-lg font-semibold text-sm">
            Simpan Kategori
        </button>
    </div>
</div>
</div>

<!-- SUB CATEGORY MODAL -->
<div id="subCategoryModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex justify-center items-center z-50 p-4">
<div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl modal-enter">
    <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
        <div>
            <h2 class="font-bold text-slate-800">Tambah Sub Kategori</h2>
            <p class="text-xs text-slate-400 mt-0.5">Sub kategori dari kategori utama</p>
        </div>
        <button onclick="closeSubCategoryModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
            <i data-feather="x" class="w-4 h-4"></i>
        </button>
    </div>
    <div class="p-6 space-y-3">
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 block">Kategori Induk</label>
            <select id="sub_parent" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm"></select>
        </div>
        <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 block">Nama Sub Kategori</label>
            <input id="new_sub_category" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm" placeholder="Contoh: Sofa, Kursi Kantor...">
        </div>
        <button onclick="saveSubCategory()" class="btn w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-lg font-semibold text-sm">
            Simpan Sub Kategori
        </button>
    </div>
</div>
</div>

<script>
const API = '/api/assets';
const token = localStorage.getItem('token');
let editId = null;

function conditionBadge(c) {
    if (!c) return '<span class="text-slate-300">-</span>';
    const map = {
        'baik': 'badge-baik',
        'rusak ringan': 'badge-ringan',
        'rusak berat': 'badge-berat'
    };
    const cls = map[c] || 'bg-slate-100 text-slate-500';
    return `<span class="px-2.5 py-1 rounded-full text-xs font-semibold ${cls} capitalize">${c}</span>`;
}

function rupiah(v) {
    if (!v) return '<span class="text-slate-300">-</span>';
    return 'Rp ' + parseInt(v).toLocaleString('id-ID');
}

async function loadAssets() {
    const res = await fetch(API, { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();

    const empty = document.getElementById('emptyState');
    if (!data.length) {
        assetTable.innerHTML = '';
        empty.classList.remove('hidden');
        feather.replace();
        return;
    }
    empty.classList.add('hidden');

    assetTable.innerHTML = data.map(a => `
        <tr class="border-b border-slate-50 last:border-0">
            <td class="px-5 py-4">
                <span class="font-semibold text-slate-700">${a.name}</span>
                ${a.location ? `<p class="text-xs text-slate-400 mt-0.5">${a.location}</p>` : ''}
            </td>
            <td class="px-5 py-4 text-slate-500">${a.branch?.name ?? '<span class="text-slate-300">-</span>'}</td>
            <td class="px-5 py-4">
                ${a.category?.name
                    ? `<span class="bg-purple-50 text-purple-700 text-xs font-medium px-2.5 py-1 rounded-full">${a.category.name}</span>`
                    : '<span class="text-slate-300">-</span>'}
            </td>
            <td class="px-5 py-4">
                ${a.subCategory?.name
                    ? `<span class="bg-indigo-50 text-indigo-700 text-xs font-medium px-2.5 py-1 rounded-full">${a.subCategory.name}</span>`
                    : '<span class="text-slate-300">-</span>'}
            </td>
            <td class="px-5 py-4">${conditionBadge(a.condition)}</td>
            <td class="px-5 py-4 text-slate-500 text-sm">${a.brand ?? '<span class="text-slate-300">-</span>'}</td>
            <td class="px-5 py-4 text-slate-600 text-sm font-medium">${rupiah(a.value)}</td>
            <td class="px-5 py-4">
                ${a.photo
                    ? `<a href="/storage/${a.photo}" target="_blank">
                        <img src="/storage/${a.photo}" class="photo-thumb">
                       </a>`
                    : '<span class="text-slate-300">-</span>'}
            </td>
            <td class="px-5 py-4">
                <div class="flex gap-1.5">
                    <button onclick="editAsset(${a.id})" class="btn flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100 text-amber-600 px-3 py-1.5 rounded-lg text-xs font-semibold border border-amber-200">
                        <i data-feather="edit-2" class="w-3 h-3"></i> Edit
                    </button>
                    <button onclick="deleteAsset(${a.id})" class="btn flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-xs font-semibold border border-red-200">
                        <i data-feather="trash-2" class="w-3 h-3"></i> Hapus
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    feather.replace();
}

async function saveCategory() {
    const name = document.getElementById('new_category').value;
    if (!name) { alert('Nama category wajib diisi!'); return; }
    await fetch('/api/asset/categories', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify({ name })
    });
    document.getElementById('new_category').value = '';
    closeCategoryModal();
    loadDropdowns();
    loadCategoryForSub();
}

async function saveSubCategory() {
    const parent_id = document.getElementById('sub_parent').value;
    const name = document.getElementById('new_sub_category').value;
    if (!parent_id) { alert('Pilih category terlebih dahulu!'); return; }
    if (!name) { alert('Nama sub category wajib diisi!'); return; }
    await fetch('/api/asset/sub-categories', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, asset_category_id: parent_id })
    });
    document.getElementById('new_sub_category').value = '';
    closeSubCategoryModal();
}

async function loadCategoryForSub() {
    const categories = await fetch('/api/asset/categories', {
        headers: { Authorization: 'Bearer ' + token }
    }).then(r => r.json());
    const subParent = document.getElementById('sub_parent');
    if (!subParent) return;
    subParent.innerHTML = '<option value="">Pilih Category</option>' +
        categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
}

async function loadDropdowns() {
    const categories = await fetch('/api/asset/categories', { headers: { Authorization: 'Bearer ' + token }}).then(r=>r.json());
    const branches = await fetch('/api/branches', { headers: { Authorization: 'Bearer ' + token }}).then(r=>r.json());
    document.getElementById('category_id').innerHTML = '<option value="">Pilih Kategori</option>' + categories.map(c=>`<option value="${c.id}">${c.name}</option>`).join('');
    document.getElementById('branch_id').innerHTML = '<option value="">Pilih Cabang</option>' + branches.map(b=>`<option value="${b.id}">${b.name}</option>`).join('');
}

document.getElementById('category_id').addEventListener('change', async function(){
    if(!this.value){ document.getElementById('sub_category_id').innerHTML='<option>Pilih Sub</option>'; return; }
    const subs = await fetch('/api/asset/sub-categories?category_id='+this.value, {headers:{Authorization:'Bearer '+token}}).then(r=>r.json());
    document.getElementById('sub_category_id').innerHTML = '<option value="">Pilih Sub</option>'+subs.map(s=>`<option value="${s.id}">${s.name}</option>`).join('');
});

async function saveAsset(){
    const nameInput = document.getElementById('name');
    const conditionInput = document.getElementById('condition');
    if(!nameInput.value){ alert('Nama wajib diisi!'); return; }
    if(!conditionInput.value){ alert('Kondisi wajib diisi!'); return; }

    const fd = new FormData();
    fd.append('name', nameInput.value);
    fd.append('location', document.getElementById('location').value);
    fd.append('condition', conditionInput.value);
    fd.append('category_id', document.getElementById('category_id').value);
    fd.append('sub_category_id', document.getElementById('sub_category_id').value);
    fd.append('branch_id', document.getElementById('branch_id').value);
    fd.append('brand', document.getElementById('brand').value);
    fd.append('value', document.getElementById('value').value);
    fd.append('acquisition_year', document.getElementById('acquisition_year').value);
    const photo = document.getElementById('photo');
    if(photo.files[0]) fd.append('photo', photo.files[0]);

    let url = API;
    if(editId){ url += '/' + editId; fd.append('_method','PUT'); }

    await fetch(url,{ method:'POST', headers:{Authorization:'Bearer '+token}, body:fd });
    resetForm();
    closeForm();
    loadAssets();
}

async function editAsset(id){
    const d = await fetch(API+'/'+id, { headers:{Authorization:'Bearer '+token} }).then(r=>r.json());
    document.getElementById('modalTitle').textContent = 'Edit Aset';
    document.getElementById('name').value = d.name ?? '';
    document.getElementById('location').value = d.location ?? '';
    document.getElementById('condition').value = d.condition ?? '';
    document.getElementById('category_id').value = d.category_id;

    const subs = await fetch('/api/asset/sub-categories?category_id='+d.category_id, {headers:{Authorization:'Bearer '+token}}).then(r=>r.json());
    document.getElementById('sub_category_id').innerHTML = '<option value="">Pilih Sub</option>' + subs.map(s=>`<option value="${s.id}">${s.name}</option>`).join('');
    document.getElementById('sub_category_id').value = d.sub_category_id;
    document.getElementById('branch_id').value = d.branch_id;
    document.getElementById('brand').value = d.brand ?? '';
    document.getElementById('value').value = d.value ?? '';
    document.getElementById('acquisition_year').value = d.acquisition_year ?? '';

    editId = id;
    openForm();
}

async function deleteAsset(id){
    if(confirm('Yakin ingin menghapus aset ini?')){
        await fetch(API+'/'+id,{method:'DELETE',headers:{Authorization:'Bearer '+token}});
        loadAssets();
    }
}

function resetForm(){
    editId = null;
    document.getElementById('modalTitle').textContent = 'Tambah Aset';
    ['name','location','brand','value','acquisition_year'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('condition').value = '';
    document.getElementById('category_id').value = '';
    document.getElementById('sub_category_id').innerHTML = '<option>Pilih Sub</option>';
    document.getElementById('branch_id').value = '';
}

function openCategoryModal() { document.getElementById('categoryModal').classList.remove('hidden'); }
function closeCategoryModal() { document.getElementById('categoryModal').classList.add('hidden'); }
function openSubCategoryModal() { document.getElementById('subCategoryModal').classList.remove('hidden'); loadCategoryForSub(); }
function closeSubCategoryModal() { document.getElementById('subCategoryModal').classList.add('hidden'); }
function openForm() { document.getElementById('modal').classList.remove('hidden'); }
function closeForm() { document.getElementById('modal').classList.add('hidden'); resetForm(); }

// INIT
loadAssets();
loadDropdowns();

// Feather icons
document.addEventListener('DOMContentLoaded', () => feather.replace());
</script>

</body>
</html>