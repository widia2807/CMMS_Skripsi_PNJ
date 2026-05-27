<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Template SPK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn { transition: all 0.15s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        input:focus, select:focus, textarea:focus {
            outline: none; border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }
        .upload-zone { border: 2px dashed #e2e8f0; transition: all 0.2s; }
        .upload-zone:hover { border-color: #6366f1; background: #f5f3ff; }
        .upload-zone.dragover { border-color: #6366f1; background: #ede9fe; }
        .preview-card {
            background: white; border: 1px solid #e2e8f0;
            border-radius: 12px; padding: 24px;
            font-family: 'Times New Roman', serif;
        }
        .section-card {
            background: white; border: 1px solid #f1f5f9;
            border-radius: 16px; padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .toast {
            position: fixed; bottom: 24px; right: 24px; z-index: 999;
            transform: translateY(100px); opacity: 0;
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

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
                        <h1 class="font-bold text-slate-800 text-lg">Pengaturan Template SPK</h1>
                        <p class="text-xs text-slate-400 mt-0.5">Konfigurasi kop surat, logo, tanda tangan & penomoran</p>
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

<div class="p-8 max-w-5xl mx-auto space-y-6">

<!-- IDENTITAS PERUSAHAAN -->
<div class="section-card">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center">
            <i data-feather="briefcase" class="w-4 h-4 text-indigo-600"></i>
        </div>
        <div>
            <h2 class="font-bold text-slate-800">Identitas Perusahaan</h2>
            <p class="text-xs text-slate-400">Informasi yang tampil di kop surat SPK</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama Perusahaan / Instansi <span class="text-red-400">*</span></label>
            <input type="text" id="set_name" placeholder="PT. Nama Perusahaan"
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm">
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Alamat</label>
            <textarea id="set_address" rows="2" placeholder="Jl. Contoh No. 1, Kota, Provinsi"
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm resize-none"></textarea>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Telepon</label>
            <input type="text" id="set_phone" placeholder="021-1234567"
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Email</label>
            <input type="email" id="set_email" placeholder="info@perusahaan.com"
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm">
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nama Manager / Penanggung Jawab GA</label>
            <input type="text" id="set_manager_name" placeholder="Nama lengkap manager GA"
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm">
        </div>
    </div>
</div>

<!-- LOGO & TTD -->
<div class="section-card">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-9 h-9 bg-violet-100 rounded-xl flex items-center justify-center">
            <i data-feather="image" class="w-4 h-4 text-violet-600"></i>
        </div>
        <div>
            <h2 class="font-bold text-slate-800">Logo & Tanda Tangan</h2>
            <p class="text-xs text-slate-400">File gambar untuk kop surat dan kolom tanda tangan</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Logo -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Logo Perusahaan</label>
            <div class="upload-zone rounded-xl p-5 text-center cursor-pointer"
                onclick="document.getElementById('logoInput').click()"
                ondragover="handleDragOver(event,'logoZone')"
                ondragleave="handleDragLeave('logoZone')"
                ondrop="handleDrop(event,'logoInput','logoPreview','logoZone')"
                id="logoZone">
                <input type="file" id="logoInput" accept="image/*" class="hidden" onchange="previewFile(this,'logo')">
                <div id="logoPreviewWrap" class="hidden mb-3">
                    <img id="logoPreview" class="max-h-20 mx-auto rounded-lg border border-slate-200 object-contain">
                </div>
                <div id="logoPlaceholder">
                    <i data-feather="upload-cloud" class="w-8 h-8 text-slate-300 mx-auto mb-2"></i>
                    <p class="text-sm font-semibold text-slate-500">Klik atau drag & drop</p>
                    <p class="text-xs text-slate-400 mt-1">PNG, JPG — maks 2MB</p>
                </div>
            </div>
            <button onclick="clearFile('logo')" id="clearLogo"
                class="hidden mt-2 text-xs text-red-400 hover:text-red-600 font-medium">✕ Hapus logo</button>
        </div>
        <!-- TTD -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">
                Tanda Tangan Manager GA
                <span class="normal-case font-normal text-slate-400 ml-1">(PNG transparan)</span>
            </label>
            <div class="upload-zone rounded-xl p-5 text-center cursor-pointer"
                onclick="document.getElementById('sigInput').click()"
                ondragover="handleDragOver(event,'sigZone')"
                ondragleave="handleDragLeave('sigZone')"
                ondrop="handleDrop(event,'sigInput','sigPreview','sigZone')"
                id="sigZone">
                <input type="file" id="sigInput" accept="image/*" class="hidden" onchange="previewFile(this,'sig')">
                <div id="sigPreviewWrap" class="hidden mb-3">
                    <img id="sigPreview" class="max-h-20 mx-auto rounded-lg border border-slate-200 object-contain">
                </div>
                <div id="sigPlaceholder">
                    <i data-feather="pen-tool" class="w-8 h-8 text-slate-300 mx-auto mb-2"></i>
                    <p class="text-sm font-semibold text-slate-500">Upload tanda tangan</p>
                    <p class="text-xs text-slate-400 mt-1">Gunakan PNG dengan background transparan</p>
                </div>
            </div>
            <button onclick="clearFile('sig')" id="clearSig"
                class="hidden mt-2 text-xs text-red-400 hover:text-red-600 font-medium">✕ Hapus tanda tangan</button>
        </div>
    </div>
</div>

<!-- PENOMORAN -->
<div class="section-card">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center">
            <i data-feather="hash" class="w-4 h-4 text-amber-600"></i>
        </div>
        <div>
            <h2 class="font-bold text-slate-800">Format Penomoran SPK</h2>
            <p class="text-xs text-slate-400">Konfigurasi format nomor surat perintah kerja</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Prefix Perbaikan</label>
            <input type="text" id="set_prefix_repair" value="REP"
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono" oninput="updatePreview()">
            <p class="text-xs text-slate-400 mt-1">Contoh: REP, PRB, FIX</p>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Prefix Maintenance</label>
            <input type="text" id="set_prefix_scheduled" value="SCH"
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono" oninput="updatePreview()">
            <p class="text-xs text-slate-400 mt-1">Contoh: SCH, MNT, PM</p>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Panjang Nomor Urut</label>
            <select id="set_seq_length" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm" onchange="updatePreview()">
                <option value="3">3 digit (001)</option>
                <option value="4" selected>4 digit (0001)</option>
                <option value="5">5 digit (00001)</option>
            </select>
        </div>
    </div>
    <div class="bg-slate-50 rounded-xl p-4">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Preview Nomor SPK</p>
        <div class="flex flex-wrap gap-3">
            <div class="bg-white border border-slate-200 rounded-lg px-4 py-2">
                <p class="text-xs text-slate-400 mb-0.5">Perbaikan</p>
                <p class="font-mono font-bold text-slate-700 text-sm" id="previewRepair">SPK-REP-2026-0001</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-lg px-4 py-2">
                <p class="text-xs text-slate-400 mb-0.5">Maintenance</p>
                <p class="font-mono font-bold text-slate-700 text-sm" id="previewScheduled">SPK-SCH-2026-0001</p>
            </div>
        </div>
    </div>
</div>

<!-- PREVIEW KOP -->
<div class="section-card">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center">
            <i data-feather="file-text" class="w-4 h-4 text-slate-600"></i>
        </div>
        <div>
            <h2 class="font-bold text-slate-800">Preview Kop Surat</h2>
            <p class="text-xs text-slate-400">Tampilan live berdasarkan pengaturan di atas</p>
        </div>
    </div>
    <div class="preview-card max-w-2xl mx-auto">
        <div style="display:flex;align-items:center;gap:16px;padding-bottom:12px;border-bottom:3px double #111;">
            <img id="prevLogo" src="" alt="" style="width:60px;height:60px;object-fit:contain;display:none">
            <div style="flex:1;text-align:center;">
                <div id="prevName" style="font-size:14pt;font-weight:bold;text-transform:uppercase;">NAMA INSTANSI</div>
                <div id="prevAddress" style="font-size:9pt;color:#444;margin-top:2px;">Alamat Instansi</div>
                <div id="prevContact" style="font-size:9pt;color:#444;margin-top:2px;"></div>
            </div>
        </div>
        <div style="text-align:center;margin-top:12px;">
            <div style="font-size:13pt;font-weight:bold;text-transform:uppercase;letter-spacing:1px;">Surat Perintah Kerja (Work Order)</div>
            <div id="prevWoNum" style="font-size:9pt;color:#666;margin-top:4px;">No: SPK-REP-2026-0001</div>
        </div>
    </div>
</div>

</div>
</div>
</div>

<!-- TOAST -->
<div class="toast" id="toast">
    <div class="bg-slate-800 text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 text-sm font-semibold">
        <i data-feather="check-circle" class="w-4 h-4 text-green-400"></i>
        <span id="toastMsg">Pengaturan berhasil disimpan</span>
    </div>
</div>

<script>
const token = localStorage.getItem('token');
if (!token) window.location.href = '/login';

async function loadSettings() {
    const res = await fetch('/api/company-settings', {
        headers: { Authorization: 'Bearer ' + token }
    });
    if (!res.ok) return;
    const data = await res.json();

    document.getElementById('set_name').value         = data.name ?? '';
    document.getElementById('set_address').value      = data.address ?? '';
    document.getElementById('set_phone').value        = data.phone ?? '';
    document.getElementById('set_email').value        = data.email ?? '';
    document.getElementById('set_manager_name').value = data.manager_name ?? '';

    updateKopPreview();

    if (data.logo_url) {
        document.getElementById('logoPreview').src = data.logo_url;
        document.getElementById('logoPreviewWrap').classList.remove('hidden');
        document.getElementById('logoPlaceholder').classList.add('hidden');
        document.getElementById('clearLogo').classList.remove('hidden');
        document.getElementById('prevLogo').src = data.logo_url;
        document.getElementById('prevLogo').style.display = 'block';
    }
    if (data.manager_sig_url) {
        document.getElementById('sigPreview').src = data.manager_sig_url;
        document.getElementById('sigPreviewWrap').classList.remove('hidden');
        document.getElementById('sigPlaceholder').classList.add('hidden');
        document.getElementById('clearSig').classList.remove('hidden');
    }

    updatePreview();
    feather.replace();
}

async function saveSettings() {
    const btn = document.getElementById('btnSave');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    const fd = new FormData();
    fd.append('name',         document.getElementById('set_name').value);
    fd.append('address',      document.getElementById('set_address').value);
    fd.append('phone',        document.getElementById('set_phone').value);
    fd.append('email',        document.getElementById('set_email').value);
    fd.append('manager_name', document.getElementById('set_manager_name').value);

    const logoFile = document.getElementById('logoInput').files[0];
    const sigFile  = document.getElementById('sigInput').files[0];
    if (logoFile) fd.append('logo', logoFile);
    if (sigFile)  fd.append('manager_signature', sigFile);

    const res = await fetch('/api/company-settings', {
        method: 'POST',
        headers: { Authorization: 'Bearer ' + token },
        body: fd,
    });

    btn.disabled = false;
    btn.innerHTML = '<i data-feather="save" class="w-4 h-4"></i> Simpan Pengaturan';
    feather.replace();

    if (!res.ok) { showToast('❌ Gagal menyimpan'); return; }
    updateKopPreview();
    showToast('✅ Pengaturan berhasil disimpan!');
}

function updateKopPreview() {
    document.getElementById('prevName').textContent    = document.getElementById('set_name').value || 'NAMA INSTANSI';
    document.getElementById('prevAddress').textContent = document.getElementById('set_address').value || 'Alamat Instansi';
    const phone = document.getElementById('set_phone').value;
    const email = document.getElementById('set_email').value;
    document.getElementById('prevContact').textContent = [
        phone ? 'Telp: ' + phone : '',
        email ? 'Email: ' + email : '',
    ].filter(Boolean).join('  |  ');
}

function updatePreview() {
    const rep  = document.getElementById('set_prefix_repair').value || 'REP';
    const sch  = document.getElementById('set_prefix_scheduled').value || 'SCH';
    const len  = parseInt(document.getElementById('set_seq_length').value) || 4;
    const year = new Date().getFullYear();
    const seq  = '1'.padStart(len, '0');
    document.getElementById('previewRepair').textContent    = `SPK-${rep}-${year}-${seq}`;
    document.getElementById('previewScheduled').textContent = `SPK-${sch}-${year}-${seq}`;
    document.getElementById('prevWoNum').textContent        = `No: SPK-${rep}-${year}-${seq}`;
}

function previewFile(input, type) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        if (type === 'logo') {
            document.getElementById('logoPreview').src = e.target.result;
            document.getElementById('logoPreviewWrap').classList.remove('hidden');
            document.getElementById('logoPlaceholder').classList.add('hidden');
            document.getElementById('clearLogo').classList.remove('hidden');
            document.getElementById('prevLogo').src = e.target.result;
            document.getElementById('prevLogo').style.display = 'block';
        } else {
            document.getElementById('sigPreview').src = e.target.result;
            document.getElementById('sigPreviewWrap').classList.remove('hidden');
            document.getElementById('sigPlaceholder').classList.add('hidden');
            document.getElementById('clearSig').classList.remove('hidden');
        }
    };
    reader.readAsDataURL(file);
}

function clearFile(type) {
    if (type === 'logo') {
        document.getElementById('logoInput').value = '';
        document.getElementById('logoPreview').src = '';
        document.getElementById('logoPreviewWrap').classList.add('hidden');
        document.getElementById('logoPlaceholder').classList.remove('hidden');
        document.getElementById('clearLogo').classList.add('hidden');
        document.getElementById('prevLogo').style.display = 'none';
    } else {
        document.getElementById('sigInput').value = '';
        document.getElementById('sigPreview').src = '';
        document.getElementById('sigPreviewWrap').classList.add('hidden');
        document.getElementById('sigPlaceholder').classList.remove('hidden');
        document.getElementById('clearSig').classList.add('hidden');
    }
    feather.replace();
}

function handleDragOver(e, zoneId) { e.preventDefault(); document.getElementById(zoneId).classList.add('dragover'); }
function handleDragLeave(zoneId) { document.getElementById(zoneId).classList.remove('dragover'); }
function handleDrop(e, inputId, previewId, zoneId) {
    e.preventDefault();
    document.getElementById(zoneId).classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (!file) return;
    const input = document.getElementById(inputId);
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    const type = inputId === 'logoInput' ? 'logo' : 'sig';
    previewFile(input, type);
}

function showToast(msg) {
    document.getElementById('toastMsg').textContent = msg;
    const toast = document.getElementById('toast');
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadSettings();
    ['set_name','set_address','set_phone','set_email'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', updateKopPreview);
    });
});
</script>
</body>
</html>