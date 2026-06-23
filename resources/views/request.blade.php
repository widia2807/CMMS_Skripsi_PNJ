<!DOCTYPE html>
<html>
<head>
    <title>Ajukan Perbaikan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f1f5f9; }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 6px;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            background: white;
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }
        .form-input::placeholder, .form-textarea::placeholder {
            color: #94a3b8;
        }
        .form-textarea { resize: none; }

        .file-upload {
            width: 100%;
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #fafbfc;
        }
        .file-upload:hover {
            border-color: #6366f1;
            background: #f5f3ff;
        }
        .file-upload input[type="file"] {
            display: none;
        }

        .btn-submit {
            width: 100%;
            background: #1e293b;
            color: white;
            border-radius: 12px;
            padding: 13px;
            font-size: 14px;
            font-weight: 700;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            border: none;
        }
        .btn-submit:hover { background: #334155; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled { background: #94a3b8; cursor: not-allowed; transform: none; }

        .step-badge {
            width: 24px; height: 24px;
            border-radius: 50%;
            background: #6366f1;
            color: white;
            font-size: 11px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .photo-preview {
            width: 100%;
            max-height: 180px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            margin-top: 10px;
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>
</head>

<body>

@include('components.sidebar')

<div class="flex min-h-screen">
<div class="flex-1 md:ml-64">
    <!-- TOPBAR -->
            <div class="bg-white border-b border-slate-100 px-4 md:px-8 py-4 flex justify-between items-center sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <!-- Hamburger mobile -->
                    <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-400">
                        <i data-feather="menu" class="w-5 h-5"></i>
                    </button>
                    <div>
                        <h1 class="font-bold text-slate-800 text-lg">Ajukan Perbaikan</h1>
                        <p class="text-xs text-slate-400 mt-0.5">Isi formulir pengajuan perbaikan</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div id="userBadge" class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                        <div class="w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center">
                            <span id="userInitial" class="text-white font-bold text-xs"></span>
                        </div>
                        <span id="userInfo" class="text-sm font-medium text-slate-600"></span>
                    </div>
                </div>
            </div>
   

    <div class="p-6 md:p-8 max-w-2xl mx-auto">
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 mb-6 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i data-feather="info" class="w-4 h-4 text-indigo-600"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-indigo-800">Cara Pengajuan</p>
                <p class="text-xs text-indigo-600 mt-0.5">Isi semua field yang diperlukan, lampirkan foto kondisi kerusakan, lalu klik Submit. Tim akan segera menindaklanjuti.</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-50">
                <h2 class="font-bold text-slate-800">Form Pengajuan Perbaikan</h2>
                <p class="text-xs text-slate-400 mt-0.5">Lengkapi informasi di bawah ini</p>
            </div>

            <div class="p-6 space-y-5">

                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <div class="step-badge">1</div>
                        <label class="form-label mb-0">Judul Masalah <span class="text-red-500">*</span></label>
                    </div>
                    <input id="title" type="text" placeholder="Contoh: AC Ruang Rapat Tidak Dingin"
                        class="form-input">
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <div class="step-badge">2</div>
                        <label class="form-label mb-0">Kategori & Jenis Kerusakan <span class="text-red-500">*</span></label>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <select id="category" onchange="updateSubCategory()" class="form-select">
                                <option value="">Pilih Kategori</option>
                            </select>
                        </div>
                        <div>
                            <select id="sub_category" class="form-select" disabled>
                                <option value="">Pilih kategori dulu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <div class="step-badge">3</div>
                        <label class="form-label mb-0">Deskripsi Masalah <span class="text-red-500">*</span></label>
                    </div>
                    <textarea id="description" rows="4"
                        placeholder="Jelaskan masalah secara detail: kapan terjadi, seberapa parah, dampak yang dirasakan..."
                        class="form-textarea"></textarea>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <div class="step-badge">4</div>
                        <label class="form-label mb-0">Foto Kerusakan <span class="text-slate-400 font-normal normal-case">(opsional)</span></label>
                    </div>
                    
                     
                        <div class="file-upload" id="uploadLabel" onclick="document.getElementById('photo').click()">
                            <input type="file" id="photo" accept="image/*" onchange="previewPhoto(event)" style="display:none">
                            <div id="uploadPlaceholder">
                                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                    <i data-feather="camera" class="w-5 h-5 text-slate-400"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-500">Klik untuk upload foto</p>
                                <p class="text-xs text-slate-400 mt-1">PNG, JPG, JPEG hingga 5MB</p>
                            </div>
                            <img id="photoPreview" alt="Preview" style="display:none; width:100%; max-height:180px; object-fit:cover; border-radius:10px; border:1px solid #e2e8f0; margin-top:10px;">
                        </div>
                </div>
                <div class="border-t border-slate-100 pt-2"></div>
                <button onclick="submitRequest()" id="submitBtn" class="btn-submit">
                    <i data-feather="send" class="w-4 h-4"></i> Submit Pengajuan</button>
            </div>
        </div>
    </div>
</div>
</div>

<script>
function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
}
const token = getCookie('token');
const user  = JSON.parse(getCookie('user') || 'null');
if (!user || !token) { window.location.href = '/login'; }
document.getElementById('userInfo').innerText    = user.name + ' · ' + user.role;
document.getElementById('userInitial').innerText = user.name?.split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase();

async function loadCategories() {
    const select = document.getElementById('category');
    const res    = await fetch('/api/categories', {
        headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' }
    });
    const data = await res.json();
    if (!Array.isArray(data)) return;

    select.innerHTML = '<option value="">Pilih Kategori</option>' +
        data.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
}

async function updateSubCategory() {
    const categoryId = document.getElementById('category').value;
    const sub        = document.getElementById('sub_category');
    if (!categoryId) {
        sub.innerHTML = '<option value="">Pilih kategori dulu</option>';
        sub.disabled  = true;
        return;
    }
    sub.innerHTML = '<option>Memuat...</option>';
    sub.disabled  = true;
    const res = await fetch(`/api/request/sub-categories/${categoryId}`, {
        headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' }
    });
    if (!res.ok) {
        sub.innerHTML = '<option value="">Gagal memuat data</option>';
        return;
    }
    const data = await res.json();
    sub.innerHTML = '<option value="">Pilih Jenis Kerusakan</option>' +
    data.map(item => `<option value="${item.id}">${item.name}</option>`).join(''); 
    sub.disabled = false;
}
function previewPhoto(event) {
    const file        = event.target.files[0];
    const preview     = document.getElementById('photoPreview');
    const placeholder = document.getElementById('uploadPlaceholder');

    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';     
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}

async function submitRequest() {
    const title       = document.getElementById('title').value.trim();
    const description = document.getElementById('description').value.trim();
    const categoryId  = document.getElementById('category').value;
    const subCategory = document.getElementById('sub_category').value;

    if (!title)       { alert('Judul masalah wajib diisi!'); return; }
     if (!categoryId)  { alert('Pilih kategori terlebih dahulu!'); return; }
    if (!categoryId)  { alert('Pilih kategori terlebih dahulu!'); return; }
    if (!description) { alert('Deskripsi masalah wajib diisi!'); return; }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div> Mengirim...';

    const fd = new FormData();
    fd.append('title', title);
    fd.append('description', description);
    fd.append('category_id', categoryId);
    fd.append('sub_category_id', subCategory);

    const file = document.getElementById('photo').files[0];
    if (file) fd.append('photo', file);

    try {
        const res  = await fetch('/api/requests', {
            method: 'POST',
            headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' },
            body: fd
        });
        const data = await res.json();

        if (!res.ok) {
            alert('Error: ' + JSON.stringify(data.errors || data.message));
            btn.disabled = false;
            btn.innerHTML = '<i data-feather="send" class="w-4 h-4"></i> Submit Pengajuan';
            feather.replace();
            return;
        }

        alert('✅ Pengajuan berhasil dikirim! Tim akan segera menindaklanjuti.');
        window.location.href = '/status';

    } catch (err) {
        alert('Gagal mengirim. Periksa koneksi Anda.');
        btn.disabled = false;
        btn.innerHTML = '<i data-feather="send" class="w-4 h-4"></i> Submit Pengajuan';
        feather.replace();
    }
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
}
function goTo(url)  { window.location.href = url; }
function logout()   { localStorage.clear(); window.location.href = '/login'; }
function goToDashboard() {
    if (user?.role === 'pic') window.location.href = '/dashboard-pic';
    else if (user?.system_type === 'lite') window.location.href = '/dashboard-lite';
    else window.location.href = '/dashboard-full';
}

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadCategories();
});
</script>

</body>
</html>