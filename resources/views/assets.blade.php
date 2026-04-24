<!DOCTYPE html>
<html>
<head>
    <title>Data Aset</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100">

@include('components.sidebar')

<div class="flex h-screen">

<div class="flex-1 md:ml-64">

<!-- TOPBAR -->
<div class="bg-white shadow px-6 py-4 flex justify-between">
    <h1 class="font-semibold">Manajemen Aset</h1>
    <span class="text-sm text-gray-600">Admin</span>
</div>

<div class="p-6">

<!-- HEADER -->
<div class="flex justify-between mb-4">
    <h2 class="text-lg font-semibold">Daftar Aset</h2>

    <div class="flex gap-2">

    <button onclick="openCategoryModal()" class="bg-purple-600 text-white px-3 py-2 rounded">
        + Category
    </button>

    <button onclick="openSubCategoryModal()" class="bg-indigo-600 text-white px-3 py-2 rounded">
        + Sub Category
    </button>

    <a href="/api/assets/export" class="bg-green-600 text-white px-3 py-2 rounded">
        Export
    </a>

    <button onclick="openForm()" class="bg-blue-600 text-white px-3 py-2 rounded">
        + Tambah
    </button>

</div>
</div>

<!-- TABLE -->
<div class="bg-white rounded-xl shadow overflow-hidden">
<table class="w-full text-sm">
<thead class="bg-gray-100">
<tr>
<th class="p-3">Nama</th>
<th class="p-3">Cabang</th>
<th class="p-3">Kategori</th>
<th class="p-3">Sub Kategori</th>
<th class="p-3">Kondisi</th>
<th class="p-3">Brand</th>
<th class="p-3">Nilai</th>
<th class="p-3">Foto</th>
<th class="p-3">Action</th>
</tr>
</thead>
<tbody id="assetTable"></tbody>
</table>
</div>

</div>
</div>
</div>

<!-- MODAL -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-40 hidden flex justify-center items-center">
<div class="bg-white p-6 rounded-xl w-96">

<h2 class="mb-3 font-semibold">Form Asset</h2>

<input id="name" class="w-full border p-2 mb-2" placeholder="Nama Asset">
<input id="location" class="w-full border p-2 mb-2" placeholder="Lokasi">

<select id="condition" class="w-full border p-2 mb-2">
    <option value="">Pilih Kondisi</option>
    <option value="baik">Baik</option>
    <option value="rusak ringan">Rusak Ringan</option>
    <option value="rusak berat">Rusak Berat</option>
</select>

<select id="category_id" class="w-full border p-2 mb-2"></select>
<select id="sub_category_id" class="w-full border p-2 mb-2">
    <option value="">Pilih Sub Category</option>
</select>
<select id="branch_id" class="w-full border p-2 mb-2"></select>

<input id="brand" class="w-full border p-2 mb-2" placeholder="Brand (optional)">
<input id="value" type="number" class="w-full border p-2 mb-2" placeholder="Nilai (optional)">
<input id="acquisition_year" type="number" class="w-full border p-2 mb-2" placeholder="Tahun (optional)">

<input type="file" id="photo" class="mb-3">

<button onclick="saveAsset()" class="bg-blue-600 text-white w-full py-2 rounded">
Simpan
</button>

</div>
</div>

<!-- CATEGORY MODAL -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-40 hidden flex justify-center items-center">
<div class="bg-white p-6 rounded-xl w-80">
<input id="new_category" class="w-full border p-2 mb-3" placeholder="Nama Category">
<button onclick="saveCategory()" class="bg-purple-600 text-white w-full py-2 rounded">Simpan</button>
</div>
</div>

<!-- SUB CATEGORY MODAL -->
<div id="subCategoryModal" class="fixed inset-0 bg-black bg-opacity-40 hidden flex justify-center items-center">
<div class="bg-white p-6 rounded-xl w-80">
<select id="sub_parent" class="w-full border p-2 mb-3"></select>
<input id="new_sub_category" class="w-full border p-2 mb-3" placeholder="Nama Sub Category">
<button onclick="saveSubCategory()" class="bg-indigo-600 text-white w-full py-2 rounded">Simpan</button>
</div>
</div>

<script>
const API = '/api/assets';
const token = localStorage.getItem('token');
let editId = null;

// LOAD ASSET
async function loadAssets() {
    const res = await fetch(API, { headers: { Authorization: 'Bearer ' + token } });
    const data = await res.json();

    assetTable.innerHTML = data.map(a => `
        <tr>
            <td class="p-3 border-b">${a.name}</td>
            <td class="p-3 border-b">${a.branch?.name ?? '-'}</td>
            <td class="p-3 border-b">${a.category?.name ?? '-'}</td>
            <td class="p-3 border-b">${a.sub_category?.name ?? '-'}</td>
            <td class="p-3 border-b">${a.condition ?? '-'}</td>
            <td class="p-3 border-b">${a.brand ?? '-'}</td>
            <td class="p-3 border-b">${a.value ?? '-'}</td>
            <td class="p-3 border-b">${a.photo ? `<img src="/storage/${a.photo}" class="w-12 h-12">` : '-'}</td>
            <td class="p-3 border-b">
                <button onclick="editAsset(${a.id})">Edit</button>
                <button onclick="deleteAsset(${a.id})">Delete</button>
            </td>
        </tr>
    `).join('');
}

// DROPDOWN
async function loadDropdowns() {
    const categories = await fetch('/api/categories', { headers: { Authorization: 'Bearer ' + token }}).then(r=>r.json());
    const branches = await fetch('/api/branches', { headers: { Authorization: 'Bearer ' + token }}).then(r=>r.json());

    category_id.innerHTML = '<option value="">Pilih Category</option>' + categories.map(c=>`<option value="${c.id}">${c.name}</option>`).join('');
    branch_id.innerHTML = '<option value="">Pilih Cabang</option>' + branches.map(b=>`<option value="${b.id}">${b.name}</option>`).join('');
}

// SUB CATEGORY
category_id.addEventListener('change', async function(){
    if(!this.value){
        sub_category_id.innerHTML='<option>Pilih Sub</option>';return;
    }
    const subs = await fetch('/api/sub-categories?category_id='+this.value,{headers:{Authorization:'Bearer '+token}}).then(r=>r.json());
    sub_category_id.innerHTML = '<option value="">Pilih Sub</option>'+subs.map(s=>`<option value="${s.id}">${s.name}</option>`).join('');
});

// SAVE
async function saveAsset(){
    if(!name.value || !condition.value){
        alert('Nama & kondisi wajib!');
        return;
    }

    const fd = new FormData();
    fd.append('name',name.value);
    fd.append('location',location.value);
    fd.append('condition',condition.value);
    fd.append('category_id',category_id.value);
    fd.append('sub_category_id',sub_category_id.value);
    fd.append('branch_id',branch_id.value);
    fd.append('brand',brand.value);
    fd.append('value',value.value);
    fd.append('acquisition_year',acquisition_year.value);

    if(photo.files[0]) fd.append('photo',photo.files[0]);

    let url=API;
    if(editId){url+='/'+editId;fd.append('_method','PUT');}

    await fetch(url,{method:'POST',headers:{Authorization:'Bearer '+token},body:fd});
    resetForm(); closeForm(); loadAssets();
}

// EDIT
async function editAsset(id){
    const d = await fetch(API+'/'+id,{headers:{Authorization:'Bearer '+token}}).then(r=>r.json());

    name.value=d.name;
    location.value=d.location;
    condition.value=d.condition;

    category_id.value=d.category_id;

    const subs = await fetch('/api/sub-categories?category_id='+d.category_id,{headers:{Authorization:'Bearer '+token}}).then(r=>r.json());
    sub_category_id.innerHTML = subs.map(s=>`<option value="${s.id}">${s.name}</option>`).join('');
    sub_category_id.value=d.sub_category_id;

    branch_id.value=d.branch_id;

    editId=id;
    openForm();
}

// DELETE
async function deleteAsset(id){
    if(confirm('Yakin?')){
        await fetch(API+'/'+id,{method:'DELETE',headers:{Authorization:'Bearer '+token}});
        loadAssets();
    }
}

// RESET
function resetForm(){
    editId=null;
    name.value='';
    location.value='';
    condition.value='';
    category_id.value='';
    sub_category_id.innerHTML='<option>Pilih Sub</option>';
    branch_id.value='';
    brand.value='';
    value.value='';
    acquisition_year.value='';
}

// MODAL
function openForm(){modal.classList.remove('hidden')}
function closeForm(){modal.classList.add('hidden')}

// INIT
loadAssets();
loadDropdowns();
</script>

</body>
</html>