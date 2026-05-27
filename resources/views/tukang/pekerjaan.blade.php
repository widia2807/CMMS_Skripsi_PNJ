<!DOCTYPE html>
<html>
<head>
    <title>Pekerjaan Tukang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .card { transition: box-shadow 0.2s, transform 0.2s; }
        .card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-1px); }
        .btn { transition: all 0.15s ease; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .modal-box { animation: modalIn 0.2s ease; }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.96) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        input:focus, select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">

@include('components.sidebar')

<div class="flex min-h-screen">
<div class="flex-1 md:ml-64">

<!-- TOPBAR -->
<div class="bg-white border-b border-slate-100 px-8 py-4 flex justify-between items-center sticky top-0 z-30">
    <div>
        <h1 class="font-bold text-slate-800 text-lg">Pekerjaan Saya</h1>
        <p class="text-xs text-slate-400 mt-0.5">Daftar pekerjaan yang ditugaskan</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
            <i data-feather="user" class="w-4 h-4 text-blue-600"></i>
        </div>
        <span id="techName" class="text-sm font-medium text-slate-600">Teknisi</span>
    </div>
</div>

<div class="p-8 space-y-10">

    <!-- ══════════════════════════════════════
         SECTION 1 — PERBAIKAN GEDUNG
    ══════════════════════════════════════ -->
    <div>
        <!-- Section header -->
        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                <i data-feather="tool" class="w-4 h-4 text-orange-600"></i>
            </div>
            <div>
                <h2 class="font-bold text-slate-800">Perbaikan Gedung</h2>
                <p class="text-xs text-slate-400">Pengajuan perbaikan yang ditugaskan ke kamu</p>
            </div>
        </div>

        <!-- Filter tabs perbaikan -->
        <div class="flex gap-2 mb-4 flex-wrap">
            <button onclick="filterRepair('all')" id="rtab-all"
                class="rtab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-white">Semua</button>
            <button onclick="filterRepair('approved')" id="rtab-approved"
                class="rtab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-500 border border-slate-200">Disetujui</button>
            <button onclick="filterRepair('scheduled')" id="rtab-scheduled"
                class="rtab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-500 border border-slate-200">Terjadwal</button>
            <button onclick="filterRepair('on_progress')" id="rtab-on_progress"
                class="rtab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-500 border border-slate-200">Sedang Dikerjakan</button>
            <button onclick="filterRepair('done')" id="rtab-done"
                class="rtab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-500 border border-slate-200">Selesai</button>
        </div>

        <div id="repairList" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4"></div>
        <div id="repairEmpty" class="hidden text-center py-12">
            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-feather="tool" class="w-7 h-7 text-orange-300"></i>
            </div>
            <p class="font-semibold text-slate-400 text-sm">Tidak ada pekerjaan perbaikan</p>
        </div>
    </div>

    <!-- DIVIDER -->
    <div class="border-t border-slate-200"></div>

    <!-- ══════════════════════════════════════
         SECTION 2 — MAINTENANCE TERJADWAL
    ══════════════════════════════════════ -->
    <div>
        <!-- Section header -->
        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                <i data-feather="calendar" class="w-4 h-4 text-blue-600"></i>
            </div>
            <div>
                <h2 class="font-bold text-slate-800">Maintenance Terjadwal</h2>
                <p class="text-xs text-slate-400">Jadwal maintenance berkala yang ditugaskan ke kamu</p>
            </div>
        </div>

        <!-- Filter tabs scheduled -->
        <div class="flex gap-2 mb-4 flex-wrap">
            <button onclick="filterScheduled('all')" id="stab-all"
                class="stab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-white">Semua</button>
            <button onclick="filterScheduled('pending')" id="stab-pending"
                class="stab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-500 border border-slate-200">Menunggu Konfirmasi</button>
            <button onclick="filterScheduled('confirmed')" id="stab-confirmed"
                class="stab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-500 border border-slate-200">Dikonfirmasi</button>
            <button onclick="filterScheduled('in_progress')" id="stab-in_progress"
                class="stab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-500 border border-slate-200">Sedang Berjalan</button>
            <button onclick="filterScheduled('done')" id="stab-done"
                class="stab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-500 border border-slate-200">Selesai</button>
        </div>

        <div id="scheduledList" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4"></div>
        <div id="scheduledEmpty" class="hidden text-center py-12">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i data-feather="calendar" class="w-7 h-7 text-blue-300"></i>
            </div>
            <p class="font-semibold text-slate-400 text-sm">Tidak ada jadwal maintenance</p>
        </div>
    </div>

</div>
</div>
</div>

<!-- ══════════════ MODAL DETAIL PERBAIKAN ══════════════ -->
<div id="detailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl modal-box max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Detail Pekerjaan</h3>
            <button onclick="closeDetail()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div id="detailContent" class="p-6 space-y-3"></div>
    </div>
</div>

<!-- ══════════════ MODAL DETAIL SCHEDULED ══════════════ -->
<div id="scheduledDetailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl modal-box max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i data-feather="calendar" class="w-3.5 h-3.5 text-blue-600"></i>
                </div>
                <h3 class="font-bold text-slate-800">Detail Maintenance</h3>
            </div>
            <button onclick="closeScheduledDetail()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div id="scheduledDetailContent" class="p-6 space-y-3"></div>

        <!-- Tombol aksi di footer modal -->
        <div id="scheduledDetailActions" class="px-6 pb-6 space-y-2"></div>
    </div>
</div>

<!-- ══════════════ MODAL LAPORAN SELESAI SCHEDULED ══════════════ -->
<div id="completeScheduledModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl modal-box">
        <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Laporan Penyelesaian</h3>
            <button onclick="closeCompleteScheduled()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="text-xs text-slate-500 block mb-1">Catatan Penyelesaian</label>
                <textarea id="completeNote" rows="3" placeholder="Tuliskan ringkasan hasil pekerjaan..."
                    class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"></textarea>
            </div>
            <div>
                <label class="text-xs text-slate-500 block mb-1">Foto Dokumentasi <span class="text-slate-400">(opsional)</span></label>
                <input id="completePhoto" type="file" accept="image/*"
                    class="w-full text-xs border border-slate-200 rounded-xl px-3 py-2 file:mr-3 file:text-xs file:border-0 file:bg-blue-50 file:text-blue-600 file:rounded-lg file:px-2 file:py-1" />
            </div>
            <div class="flex gap-2 pt-1">
                <button onclick="closeCompleteScheduled()"
                    class="flex-1 btn border border-slate-200 text-slate-500 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50">
                    Batal
                </button>
                <button onclick="submitCompleteScheduled()"
                    class="flex-1 btn bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-semibold">
                    Kirim Laporan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ══════════════ MODAL LAPORAN SELESAI PERBAIKAN ══════════════ -->
<div id="completeRepairModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl modal-box">
        <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-800">Laporan Penyelesaian Perbaikan</h3>
                <p class="text-xs text-slate-400 mt-0.5">Wajib diisi sebelum ditandai selesai</p>
            </div>
            <button onclick="closeCompleteRepair()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="text-xs text-slate-500 block mb-1">
                    Deskripsi pekerjaan yang dilakukan <span class="text-red-400">*</span>
                </label>
                <textarea id="repairCompleteNote" rows="3" placeholder="Jelaskan apa saja yang sudah dikerjakan..."
                    class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-purple-100 focus:border-purple-400"></textarea>
            </div>
            <div>
                <label class="text-xs text-slate-500 block mb-1">
                    Material yang digunakan <span class="text-slate-400">(opsional)</span>
                </label>
                <input id="repairCompleteMaterial" type="text" placeholder="Contoh: cat tembok 2 liter, kuas 1 pcs"
                    class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-100 focus:border-purple-400" />
            </div>
            <div>
                <label class="text-xs text-slate-500 block mb-1">
                    Foto sesudah perbaikan <span class="text-red-400">*</span>
                </label>
                <input id="repairCompletePhoto" type="file" accept="image/*"
                    class="w-full text-xs border border-slate-200 rounded-xl px-3 py-2 file:mr-3 file:text-xs file:border-0 file:bg-purple-50 file:text-purple-600 file:rounded-lg file:px-2 file:py-1" />
            </div>
            <div class="flex gap-2 pt-1">
                <button onclick="closeCompleteRepair()"
                    class="flex-1 btn border border-slate-200 text-slate-500 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50">
                    Batal
                </button>
                <button onclick="submitCompleteRepair()"
                    class="flex-1 btn bg-purple-600 hover:bg-purple-700 text-white py-2.5 rounded-xl text-sm font-semibold">
                    Kirim Laporan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ══════════════ MODAL IMAGE ══════════════ -->
<div id="imageModal" class="fixed inset-0 bg-black/90 hidden items-center justify-center z-50">
    <img id="modalImage" class="max-w-[90%] max-h-[85vh] rounded-xl shadow-2xl object-contain">
    <button onclick="closeImage()" class="absolute top-5 right-5 w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-colors">
        <i data-feather="x" class="w-5 h-5"></i>
    </button>
</div>

<!-- ══════════════ MODAL MATERIAL ══════════════ -->
<div id="materialModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl modal-box max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-6 py-5 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-800">Ajukan Material</h3>
                <p class="text-xs text-slate-400 mt-0.5">Isi kebutuhan material pekerjaan</p>
            </div>
            <button onclick="closeMaterialModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="p-6">
            <div id="materialList" class="space-y-2">
                <div class="flex gap-2 items-center">
                    <input type="text" placeholder="Nama barang" class="border border-slate-200 rounded-lg p-2 w-full text-sm name">
                    <input type="number" placeholder="Qty" class="border border-slate-200 rounded-lg p-2 w-20 text-sm qty">
                    <select class="border border-slate-200 rounded-lg p-2 text-sm unit">
                        <option value="pcs">pcs</option>
                        <option value="meter">meter</option>
                        <option value="kg">kg</option>
                    </select>
                </div>
            </div>
            <button onclick="addMaterialField()" class="flex items-center gap-1.5 text-indigo-600 text-sm font-medium mt-3 hover:text-indigo-700">
                <i data-feather="plus-circle" class="w-4 h-4"></i> Tambah item
            </button>
            <div class="flex gap-2 mt-5">
                <button onclick="closeMaterialModal()" class="btn flex-1 border border-slate-200 text-slate-500 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button onclick="submitMaterial()" class="btn flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-lg text-sm font-semibold">Submit Material</button>
            </div>
        </div>
    </div>
</div>

<script>
const token = localStorage.getItem('token');
if (!token) window.location.href = '/login';

const user = JSON.parse(localStorage.getItem('user') || '{}');
if (user.name) document.getElementById('techName').textContent = user.name;

let selectedId        = null;
let selectedSchedId   = null;
let currentRepairFilter    = 'all';
let currentScheduledFilter = 'all';

/* ── STATUS MAPS ── */
const repairStatusLabel = {
    approved: 'Disetujui', scheduled: 'Terjadwal',
    waiting_material: 'Menunggu Material', on_progress: 'Sedang Dikerjakan',
    done: 'Selesai', material_ready: 'Material Siap', verified: 'Terverifikasi'
};
const repairStatusStyle = {
    approved: 'bg-green-100 text-green-700', scheduled: 'bg-blue-100 text-blue-700',
    waiting_material: 'bg-orange-100 text-orange-700', material_ready: 'bg-cyan-100 text-cyan-700',
    on_progress: 'bg-purple-100 text-purple-700', done: 'bg-slate-100 text-slate-500',
    verified: 'bg-teal-100 text-teal-700',
};

const schedStatusLabel = {
    pending: 'Menunggu Konfirmasi', confirmed: 'Dikonfirmasi',
    in_progress: 'Sedang Berjalan', done: 'Selesai'
};
const schedStatusStyle = {
    pending: 'bg-amber-100 text-amber-700', confirmed: 'bg-blue-100 text-blue-700',
    in_progress: 'bg-purple-100 text-purple-700', done: 'bg-green-100 text-green-700',
};

const PERIOD_MAP = {
    weekly: 'Mingguan', monthly: 'Bulanan', quarterly: 'Triwulan', yearly: 'Tahunan'
};

/* ══════════════════════════════════════
   SECTION 1 — PERBAIKAN GEDUNG
══════════════════════════════════════ */

function filterRepair(status) {
    currentRepairFilter = status;
    document.querySelectorAll('.rtab').forEach(b => {
        b.className = 'rtab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-500 border border-slate-200';
    });
    document.getElementById('rtab-' + status).className = 'rtab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-white';
    renderRepair(window.repairData || []);
}

function renderRepair(data) {
    const filtered  = currentRepairFilter === 'all' ? data : data.filter(j => j.status === currentRepairFilter);
    const list      = document.getElementById('repairList');
    const empty     = document.getElementById('repairEmpty');

    if (!filtered.length) {
        list.innerHTML = '';
        empty.classList.remove('hidden');
        feather.replace();
        return;
    }
    empty.classList.add('hidden');

    list.innerHTML = filtered.map(job => {
        let action = '';
        if (job.status === 'approved') {
            action = `
                <div class="flex gap-2 items-center">
                    <input type="date" id="date-${job.id}" class="border border-slate-200 rounded-lg px-2 py-1.5 text-xs flex-1">
                    <button onclick="setSchedule(${job.id})" class="btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap">Jadwalkan</button>
                </div>`;
        } else if (job.status === 'scheduled') {
            if (!job.spk_sent_at) {
                // SPK belum dikirim — tampilkan pesan menunggu
                action = `<p class="text-xs text-amber-600 font-medium">⏳ Menunggu SPK dari admin GA</p>`;
            } else {
                // SPK sudah ada — baru boleh mulai
                action = `
                    <div class="flex gap-2 flex-wrap">
                        <button onclick="inspectJob(${job.id}, false)" class="btn bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                            <i data-feather="play" class="w-3 h-3"></i> Langsung Kerja
                        </button>
                        <button onclick="needMaterial(${job.id})" class="btn bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                            <i data-feather="package" class="w-3 h-3"></i> Butuh Material
                        </button>
                    </div>`;
            }
        } else if (job.status === 'waiting_material') {
            action = `
                <div class="space-y-2">
                    <div class="flex gap-2">
                        <button onclick="openMaterialForm(${job.id})" class="btn bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                            <i data-feather="clipboard" class="w-3 h-3"></i> Ajukan Material
                        </button>
                        <button onclick="startJob(${job.id})" class="btn bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                            <i data-feather="play" class="w-3 h-3"></i> Mulai
                        </button>
                    </div>
                    <div class="flex gap-2 items-center">
                        <input type="date" id="res-${job.id}" class="border border-slate-200 rounded-lg px-2 py-1.5 text-xs flex-1">
                        <button onclick="reschedule(${job.id})" class="btn bg-slate-500 hover:bg-slate-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap">Reschedule</button>
                    </div>
                </div>`;
        } else if (job.status === 'material_ready') {
            action = `
                <div class="flex gap-2">
                    <button onclick="openMaterialForm(${job.id})" class="btn bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                        <i data-feather="clipboard" class="w-3 h-3"></i> Ajukan Material
                    </button>
                    <button onclick="startJob(${job.id})" class="btn bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                        <i data-feather="play" class="w-3 h-3"></i> Mulai Kerja
                    </button>
                </div>`;
        } else if (job.status === 'on_progress') {
            action = `
                <button onclick="completeJob(${job.id})" class="btn bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1">
                    <i data-feather="check-circle" class="w-3 h-3"></i> Tandai Selesai
                </button>`;
        }

        return `
        <div class="card bg-white rounded-2xl shadow-sm border border-l-4 border-orange-200 overflow-hidden">
            ${job.photo ? `
                <div class="relative h-36 overflow-hidden cursor-pointer" onclick="openImage('/storage/${job.photo}')">
                    <img src="/storage/${job.photo}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                </div>
            ` : `<div class="h-1.5 bg-gradient-to-r from-orange-300 to-amber-200"></div>`}
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-slate-800 text-sm leading-tight flex-1">${job.title ?? '-'}</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold ml-2 whitespace-nowrap ${repairStatusStyle[job.status] ?? 'bg-slate-100 text-slate-500'}">
                        ${repairStatusLabel[job.status] ?? job.status}
                    </span>
                </div>
                <div class="flex flex-col gap-1 mb-3">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <i data-feather="map-pin" class="w-3 h-3"></i>
                        <span>${job.branch ?? '-'}</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <i data-feather="tag" class="w-3 h-3"></i>
                        <span>${job.category ?? '-'}</span>
                    </div>
                </div>
                <div class="border-t border-slate-50 pt-3 space-y-2">
                    ${action}
                    ${job.spk_sent_at ? `
                    <button onclick="openWorkOrder(${job.wo_id}, 'repair')" class="btn w-full border border-indigo-200 text-indigo-600 hover:bg-indigo-50 px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center justify-center gap-1">
                        <i data-feather="file-text" class="w-3 h-3"></i> Lihat SPK — ${job.spk_number ?? ''}
                    </button>` : ''}
                    <button onclick="openDetail(${job.id})" class="btn w-full border border-slate-200 text-slate-600 hover:bg-slate-50 px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center justify-center gap-1">
                        <i data-feather="info" class="w-3 h-3"></i> Lihat Detail
                    </button>
                </div>
            </div>
        </div>`;
    }).join('');

    feather.replace();
}

async function loadRepairJobs() {
    try {
        const res  = await fetch('/api/technician/jobs', { headers: { 'Authorization': 'Bearer ' + token } });
        const data = await res.json();
        if (!res.ok) { alert(data.message || 'Gagal load data'); return; }
        window.repairData = data;
        renderRepair(data);
    } catch (err) { console.error(err); }
}

/* ══════════════════════════════════════
   SECTION 2 — MAINTENANCE TERJADWAL
══════════════════════════════════════ */

function filterScheduled(status) {
    currentScheduledFilter = status;
    document.querySelectorAll('.stab').forEach(b => {
        b.className = 'stab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-slate-500 border border-slate-200';
    });
    document.getElementById('stab-' + status).className = 'stab btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-white';
    renderScheduled(window.scheduledData || []);
}

function renderScheduled(data) {
    const filtered = currentScheduledFilter === 'all' ? data : data.filter(j => j.status === currentScheduledFilter);
    const list     = document.getElementById('scheduledList');
    const empty    = document.getElementById('scheduledEmpty');

    if (!filtered.length) {
        list.innerHTML = '';
        empty.classList.remove('hidden');
        feather.replace();
        return;
    }
    empty.classList.add('hidden');

    list.innerHTML = filtered.map(item => {
        const st     = schedStatusStyle[item.status] ?? 'bg-slate-100 text-slate-500';
        const stLbl  = schedStatusLabel[item.status] ?? item.status;
        const period = PERIOD_MAP[item.period] ?? item.period;

        // Tombol aksi per status
        let action = '';
        if (item.status === 'pending') {
            action = `
                <button onclick="confirmScheduled(${item.id})" class="btn w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center justify-center gap-1">
                    <i data-feather="check" class="w-3 h-3"></i> Konfirmasi Tugas
                </button>`;
        } else if (item.status === 'confirmed') {
            action = `
                <button onclick="startScheduled(${item.id})" class="btn w-full bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center justify-center gap-1">
                    <i data-feather="play" class="w-3 h-3"></i> Mulai Pekerjaan
                </button>`;
        } else if (item.status === 'in_progress') {
            action = `
                <button onclick="openCompleteScheduled(${item.id})" class="btn w-full bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center justify-center gap-1">
                    <i data-feather="check-circle" class="w-3 h-3"></i> Tandai Selesai
                </button>`;
        }

        const subCat = item.sub_category_name
            ? `<span class="text-xs bg-blue-50 text-blue-500 px-2 py-0.5 rounded-full">${item.sub_category_name}</span>`
            : '';

        return `
        <div class="card bg-white rounded-2xl shadow-sm border border-l-4 border-blue-200 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-blue-400 to-indigo-300"></div>
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-800 text-sm leading-tight">${item.title ?? '-'}</h3>
                        ${subCat ? `<div class="mt-1">${subCat}</div>` : ''}
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold ml-2 whitespace-nowrap ${st}">
                        ${stLbl}
                    </span>
                </div>

                <div class="flex flex-col gap-1 mb-3">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <i data-feather="layers" class="w-3 h-3"></i>
                        <span>${item.category_name ?? item.category ?? '-'}</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <i data-feather="refresh-cw" class="w-3 h-3"></i>
                        <span>${period}</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <i data-feather="calendar" class="w-3 h-3"></i>
                        <span>${formatDate(item.scheduled_date)}</span>
                    </div>
                </div>

                ${item.note ? `<p class="text-xs text-slate-500 bg-slate-50 rounded-lg px-3 py-2 mb-3 border-l-2 border-slate-200">${item.note}</p>` : ''}

                <div class="border-t border-slate-50 pt-3 space-y-2">
                    ${action}
                    ${item.spk_sent_at ? `
                    <button onclick="openWorkOrder(${item.wo_id}, 'scheduled')" class="btn w-full border border-indigo-200 text-indigo-600 hover:bg-indigo-50 px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center justify-center gap-1">
                        <i data-feather="file-text" class="w-3 h-3"></i> Lihat SPK — ${item.spk_number ?? ''}
                    </button>` : ''}
                    <button onclick="openScheduledDetail(${item.id})" class="btn w-full border border-slate-200 text-slate-600 hover:bg-slate-50 px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center justify-center gap-1">
                        <i data-feather="info" class="w-3 h-3"></i> Lihat Detail
                    </button>
                </div>
            </div>
        </div>`;
    }).join('');

    feather.replace();
}

async function loadScheduledTasks() {
    try {
        const res  = await fetch('/api/scheduled-maintenances/my-tasks', { headers: { 'Authorization': 'Bearer ' + token } });
        const data = await res.json();
        if (!res.ok) { console.error('Scheduled tasks error:', data); return; }
        window.scheduledData = Array.isArray(data) ? data : (data.data ?? []);
        renderScheduled(window.scheduledData);
    } catch (err) { console.error(err); }
}

/* Konfirmasi tugas scheduled */
async function confirmScheduled(id) {
    const res  = await fetch(`/api/scheduled-maintenances/${id}/confirm`, {
        method: 'PUT',
        headers: { 'Authorization': 'Bearer ' + token }
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message ?? 'Gagal konfirmasi');
    alert('Tugas dikonfirmasi!');
    loadScheduledTasks();
}

/* Mulai pekerjaan scheduled — ubah status ke in_progress via confirm jika belum ada endpoint start */
async function startScheduled(id) {
    // Gunakan endpoint complete dengan status in_progress, atau endpoint khusus start jika ada
    const res  = await fetch(`/api/scheduled-maintenances/${id}/confirm`, {
        method: 'PUT',
        headers: { 'Authorization': 'Bearer ' + token }
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message ?? 'Gagal memulai');
    alert('Pekerjaan dimulai!');
    loadScheduledTasks();
}

/* Modal laporan selesai */
function openCompleteScheduled(id) {
    selectedSchedId = id;
    document.getElementById('completeNote').value  = '';
    document.getElementById('completePhoto').value = '';
    document.getElementById('completeScheduledModal').classList.remove('hidden');
    document.getElementById('completeScheduledModal').classList.add('flex');
}
function closeCompleteScheduled() {
    document.getElementById('completeScheduledModal').classList.add('hidden');
    document.getElementById('completeScheduledModal').classList.remove('flex');
}

async function submitCompleteScheduled() {
    const note  = document.getElementById('completeNote').value.trim();
    const photo = document.getElementById('completePhoto').files[0];

    const form = new FormData();
    form.append('completion_note', note);
    if (photo) form.append('completion_photo', photo);

    const res  = await fetch(`/api/scheduled-maintenances/${selectedSchedId}/complete`, {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token },
        body: form,
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message ?? 'Gagal submit laporan');
    alert('Laporan berhasil dikirim!');
    closeCompleteScheduled();
    loadScheduledTasks();
}

/* Modal detail scheduled */
function openScheduledDetail(id) {
    const item = (window.scheduledData || []).find(j => j.id === id);
    if (!item) return;

    const period = PERIOD_MAP[item.period] ?? item.period;

    document.getElementById('scheduledDetailContent').innerHTML = `
        <div class="flex justify-between items-start">
            <h4 class="font-bold text-slate-800">${item.title}</h4>
            <span class="text-xs px-2.5 py-1 rounded-full font-semibold ${schedStatusStyle[item.status] ?? ''}">${schedStatusLabel[item.status] ?? item.status}</span>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <i data-feather="layers" class="w-4 h-4"></i>
            ${item.category_name ?? item.category ?? '-'}
            ${item.sub_category_name ? `<span class="text-blue-500">/ ${item.sub_category_name}</span>` : ''}
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <i data-feather="refresh-cw" class="w-4 h-4"></i> ${period}
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <i data-feather="calendar" class="w-4 h-4"></i> ${formatDate(item.scheduled_date)}
        </div>
        ${item.note ? `<p class="text-sm text-slate-600 bg-slate-50 rounded-lg p-3 border-l-2 border-slate-200">${item.note}</p>` : ''}
        ${item.worker_confirmed_at ? `<p class="text-xs text-slate-400">Dikonfirmasi: ${formatDate(item.worker_confirmed_at)}</p>` : ''}
        ${item.completed_at ? `<p class="text-xs text-slate-400">Selesai: ${formatDate(item.completed_at)}</p>` : ''}
    `;

    document.getElementById('scheduledDetailModal').classList.remove('hidden');
    document.getElementById('scheduledDetailModal').classList.add('flex');
    feather.replace();
}
function closeScheduledDetail() {
    document.getElementById('scheduledDetailModal').classList.add('hidden');
    document.getElementById('scheduledDetailModal').classList.remove('flex');
}

/* ══════════════════════════════════════
   PERBAIKAN GEDUNG — ACTIONS
══════════════════════════════════════ */

function openDetail(id) {
    const job = (window.repairData || []).find(j => j.id === id);
    if (!job) return;
    document.getElementById('detailContent').innerHTML = `
        <div class="flex justify-between items-start">
            <h4 class="font-bold text-slate-800">${job.title}</h4>
            <span class="text-xs px-2.5 py-1 rounded-full font-semibold ${repairStatusStyle[job.status] ?? ''}">${repairStatusLabel[job.status] ?? job.status}</span>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <i data-feather="map-pin" class="w-4 h-4"></i> ${job.branch}
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <i data-feather="tag" class="w-4 h-4"></i> ${job.category}
        </div>
        <p class="text-sm text-slate-600 bg-slate-50 rounded-lg p-3">${job.description ?? '-'}</p>
        ${job.photo ? `<img src="/storage/${job.photo}" onclick="openImage('/storage/${job.photo}')" class="w-full h-44 object-cover rounded-xl cursor-pointer hover:opacity-90 transition-opacity">` : ''}
    `;
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
    feather.replace();
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

async function needMaterial(id) {
    const res  = await fetch(`/api/technician/inspect/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ needs_material: true, notes: 'Butuh material' })
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    openMaterialForm(id);
}

async function setSchedule(id) {
    const date = document.getElementById(`date-${id}`).value;
    if (!date) return alert('Pilih tanggal dulu!');
    const res  = await fetch(`/api/technician/schedule/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ schedule_date: date })
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    alert('Jadwal berhasil diset');
    loadRepairJobs();
}

async function inspectJob(id, needsMaterial) {
    const res  = await fetch(`/api/technician/inspect/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ needs_material: needsMaterial, notes: 'Hasil pengecekan' })
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    alert('Inspection selesai');
    loadRepairJobs();
}

async function startJob(id) {
    const res  = await fetch(`/api/technician/start/${id}`, { method: 'POST', headers: { 'Authorization': 'Bearer ' + token } });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    alert('Pekerjaan dimulai');
    loadRepairJobs();
}

let selectedRepairId = null;

function completeJob(id) {
    selectedRepairId = id;
    document.getElementById('repairCompleteNote').value   = '';
    document.getElementById('repairCompleteMaterial').value = '';
    document.getElementById('repairCompletePhoto').value  = '';
    document.getElementById('completeRepairModal').classList.remove('hidden');
    document.getElementById('completeRepairModal').classList.add('flex');
}

function closeCompleteRepair() {
    document.getElementById('completeRepairModal').classList.add('hidden');
    document.getElementById('completeRepairModal').classList.remove('flex');
}

async function submitCompleteRepair() {
    const note  = document.getElementById('repairCompleteNote').value.trim();
    const photo = document.getElementById('repairCompletePhoto').files[0];

    if (!note)  return alert('Deskripsi pekerjaan wajib diisi!');
    if (!photo) return alert('Foto sesudah perbaikan wajib diupload!');

    const form = new FormData();
    form.append('completion_note', note);
    form.append('completion_photo', photo);

    const material = document.getElementById('repairCompleteMaterial').value.trim();
    if (material) form.append('material_used', material);

    const res  = await fetch(`/api/technician/complete/${selectedRepairId}`, {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token },
        body: form,
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message ?? 'Gagal submit laporan');
    alert('Laporan berhasil dikirim!');
    closeCompleteRepair();
    loadRepairJobs();
}

async function reschedule(id) {
    const date = document.getElementById(`res-${id}`).value;
    if (!date) return alert('Pilih tanggal dulu!');
    const res  = await fetch(`/api/technician/schedule/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ schedule_date: date })
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    alert('Reschedule berhasil');
    loadRepairJobs();
}

function openMaterialForm(id) {
    selectedId = id;
    document.getElementById('materialModal').classList.remove('hidden');
    document.getElementById('materialModal').classList.add('flex');
    feather.replace();
}
function closeMaterialModal() {
    document.getElementById('materialModal').classList.remove('flex');
    document.getElementById('materialModal').classList.add('hidden');
}
function addMaterialField() {
    const div = document.createElement('div');
    div.className = 'flex gap-2 items-center';
    div.innerHTML = `
        <input type="text" placeholder="Nama barang" class="border border-slate-200 rounded-lg p-2 w-full text-sm name">
        <input type="number" placeholder="Qty" class="border border-slate-200 rounded-lg p-2 w-20 text-sm qty">
        <select class="border border-slate-200 rounded-lg p-2 text-sm unit">
            <option value="pcs">pcs</option>
            <option value="meter">meter</option>
            <option value="kg">kg</option>
        </select>`;
    document.getElementById('materialList').appendChild(div);
}
async function submitMaterial() {
    const names = document.querySelectorAll('.name');
    const qtys  = document.querySelectorAll('.qty');
    const units = document.querySelectorAll('.unit');
    let items = [];
    for (let i = 0; i < names.length; i++) {
        if (names[i].value && qtys[i].value) {
            items.push({ name: names[i].value, qty: qtys[i].value, unit: units[i].value });
        }
    }
    if (!items.length) return alert('Isi minimal 1 item');
    const res  = await fetch(`/api/technician/material/${selectedId}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
        body: JSON.stringify({ items })
    });
    const data = await res.json();
    if (!res.ok) return alert(data.message);
    alert('Material diajukan');
    closeMaterialModal();
    loadRepairJobs();
}

/* ── HELPERS ── */
function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

function openWorkOrder(id, type) {
    if (!id) return alert('Work Order belum tersedia');
    window.open(`/work-order/${type}/${id}`, '_blank');
}
/* ── INIT ── */
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    loadRepairJobs();
    loadScheduledTasks();
});
</script>

</body>
</html>