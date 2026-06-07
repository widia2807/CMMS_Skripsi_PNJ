<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Order</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', serif; font-size: 11pt; color: #111; background: #f5f5f5; }

        @page { size: A4; margin: 2cm 2.5cm; }
        @media print {
            body { background: #fff; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }

        /* ── PRINT BAR ── */
        .print-bar {
            position: fixed; top: 16px; right: 16px; z-index: 100;
            display: flex; gap: 8px;
        }
        .print-bar button {
            padding: 9px 20px; border: none; border-radius: 9px;
            font-size: 13px; font-weight: 700; cursor: pointer; font-family: sans-serif;
        }
        .btn-print  { background: #1e293b; color: #fff; }
        .btn-settings { background: #6366f1; color: #fff; }
        .btn-close  { background: #e2e8f0; color: #334155; }
        .btn-print:hover { background: #334155; }
        .btn-settings:hover { background: #4f46e5; }

        /* ── SETTINGS PANEL ── */
        .settings-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 200;
            align-items: center; justify-content: center;
        }
        .settings-panel {
            background: #fff; border-radius: 16px; padding: 28px;
            width: 100%; max-width: 500px; max-height: 85vh;
            overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .settings-panel h2 { font-size: 16px; font-weight: 700; margin-bottom: 20px; color: #1e293b; }
        .field-group { margin-bottom: 14px; }
        .field-group label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 5px; }
        .field-group input[type="text"],
        .field-group input[type="email"] {
            width: 100%; border: 1px solid #e2e8f0; border-radius: 8px;
            padding: 9px 12px; font-size: 13px; outline: none;
        }
        .field-group input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .upload-box {
            border: 2px dashed #e2e8f0; border-radius: 10px;
            padding: 16px; text-align: center; cursor: pointer;
            background: #fafafa; transition: all 0.2s;
        }
        .upload-box:hover { border-color: #6366f1; background: #f5f3ff; }
        .upload-box input { display: none; }
        .upload-box p { font-size: 12px; color: #94a3b8; margin-top: 4px; }
        .preview-img { max-height: 80px; margin-top: 8px; border-radius: 6px; border: 1px solid #e2e8f0; }
        .btn-save-settings {
            width: 100%; background: #1e293b; color: #fff;
            border: none; border-radius: 10px; padding: 12px;
            font-size: 14px; font-weight: 700; cursor: pointer; margin-top: 8px;
        }
        .btn-save-settings:hover { background: #334155; }
        .btn-cancel-settings {
            width: 100%; background: transparent; color: #94a3b8;
            border: none; padding: 10px; font-size: 13px; cursor: pointer; margin-top: 4px;
        }

        /* ── DOKUMEN ── */
        .page {
            width: 210mm; min-height: 297mm;
            margin: 20px auto; padding: 2cm 2.5cm;
            background: #fff; box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        @media print { .page { margin: 0; box-shadow: none; } }

        /* KOP */
        .kop { display: flex; align-items: center; gap: 16px; padding-bottom: 12px; border-bottom: 3px double #111; }
        .kop img.logo { width: 70px; height: 70px; object-fit: contain; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .instansi { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .sub      { font-size: 9pt; color: #444; margin-top: 2px; }
        .kop-divider { border: none; border-top: 1px solid #111; margin: 4px 0 16px; }

        /* JUDUL */
        .doc-title { text-align: center; margin-bottom: 14px; }
        .doc-title h1 { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .type-badge {
            display: inline-block; font-size: 9pt; font-weight: bold;
            padding: 2px 12px; border-radius: 20px; margin-top: 4px;
        }
        .badge-repair    { background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; }
        .badge-scheduled { background: #e3f2fd; color: #0d47a1; border: 1px solid #90caf9; }

        /* META */
        .wo-meta {
            display: flex; justify-content: space-between; font-size: 10pt;
            margin-bottom: 16px; padding: 8px 12px;
            background: #f8f8f8; border: 1px solid #ddd; border-radius: 4px;
        }

        /* SECTION */
        .section-title {
            font-size: 10pt; font-weight: bold; text-transform: uppercase;
            background: #222; color: #fff; padding: 4px 10px; margin: 14px 0 8px;
        }

        /* INFO TABLE */
        .info-table { width: 100%; border-collapse: collapse; font-size: 10.5pt; }
        .info-table td { padding: 4px 6px; vertical-align: top; }
        .info-table td.label { width: 38%; font-weight: bold; color: #333; }
        .info-table td.sep   { width: 3%; }
        .info-table td.value { width: 59%; }
        .info-table tr:nth-child(even) td { background: #fafafa; }

        /* GRID TABLE */
        .grid-table { width: 100%; border-collapse: collapse; font-size: 10pt; margin-bottom: 8px; }
        .grid-table th, .grid-table td { border: 1px solid #aaa; padding: 5px 8px; }
        .grid-table th { background: #eee; font-weight: bold; text-align: center; }
        .grid-table td.center { text-align: center; }

        /* CHECKLIST */
        .checklist { list-style: none; padding-left: 4px; }
        .checklist li { display: flex; align-items: flex-start; gap: 6px; padding: 2px 0; font-size: 10.5pt; }
        .checklist li::before { content: '☐'; font-size: 12pt; line-height: 1; flex-shrink: 0; }

        /* NOTES */
        .notes-box {
            border: 1px solid #bbb; border-radius: 4px; padding: 8px 10px;
            min-height: 50px; font-size: 10.5pt; color: #333;
            margin-bottom: 8px; background: #fafafa;
        }

        /* KETENTUAN */
        .ketentuan { font-size: 10.5pt; padding-left: 18px; line-height: 1.7; }

        /* TANDA TANGAN */
        .signature-section {
            margin-top: 24px; display: flex; gap: 0;
            border-top: 1px solid #ddd; padding-top: 16px;
        }
        .sig-box { flex: 1; text-align: center; padding: 0 12px; border-right: 1px dashed #ccc; }
        .sig-box:last-child { border-right: none; }
        .sig-box .sig-title { font-size: 9.5pt; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; }
        .sig-box .sig-role  { font-size: 9pt; color: #555; margin-bottom: 12px; }
        .sig-box .sig-img   { height: 60px; object-fit: contain; margin-bottom: 4px; }
        .sig-box .sig-line  { border-top: 1px solid #333; margin: 0 10%; }
        .sig-box .sig-name  { font-size: 10pt; font-weight: bold; margin-top: 4px; }
        .sig-empty-space    { height: 60px; } /* ruang kosong TTD tukang */

        /* FOOTER */
        .doc-footer {
            margin-top: 20px; border-top: 1px solid #ccc; padding-top: 8px;
            display: flex; justify-content: space-between; font-size: 8.5pt; color: #888;
        }

        /* STATUS BADGE */
        .status-badge {
            display: inline-block; font-size: 9pt; font-weight: bold;
            padding: 1px 10px; border-radius: 20px; border: 1px solid #ccc;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- ══ PRINT BAR ══ -->
<div class="print-bar no-print">
    <button class="btn-print" onclick="window.print()">🖨 Cetak SPK</button>
    <button class="btn-close" onclick="window.close()">✕ Tutup</button>
</div>

<!-- ══ DOKUMEN SPK ══ -->
<div class="page" id="woPage">

    <!-- KOP SURAT -->
    <div class="kop">
        <img id="docLogo" class="logo" src="" alt="Logo" style="display:none">
        <div class="kop-text">
            <div class="instansi" id="docName">Nama Instansi</div>
            <div class="sub" id="docAddress">Alamat Instansi</div>
            <div class="sub" id="docContact"></div>
        </div>
    </div>
    <hr class="kop-divider">

    <!-- JUDUL -->
    <div class="doc-title">
        <h1>Surat Perintah Kerja (Work Order)</h1>
        <div class="type-badge" id="typeBadge"></div>
    </div>

    <!-- META -->
    <div class="wo-meta">
        <div><strong>Nomor WO</strong> &nbsp;:&nbsp; <span id="docWoNumber">-</span></div>
        <div><strong>Tanggal Terbit</strong> &nbsp;:&nbsp; <span id="docCreatedAt">-</span></div>
        <div><strong>Status</strong> &nbsp;:&nbsp; <span class="status-badge" id="docStatus">-</span></div>
    </div>

    <!-- A. INFORMASI PEKERJAAN -->
    <div class="section-title">A. Informasi Pekerjaan</div>
    <table class="info-table" id="infoTable">
        <!-- diisi JS -->
    </table>

    <!-- B. PENUGASAN PELAKSANA -->
    <div class="section-title">B. Penugasan Pelaksana</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Teknisi / Tukang</td>
            <td class="sep">:</td>
            <td class="value"><strong id="docWorker">-</strong></td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="sep">:</td>
            <td class="value">Teknisi / Tukang Pemeliharaan</td>
        </tr>
        <tr>
            <td class="label">Ditugaskan Oleh</td>
            <td class="sep">:</td>
            <td class="value" id="docCreatedBy">-</td>
        </tr>
    </table>

    <!-- C. DESKRIPSI & INSTRUKSI -->
    <div class="section-title">C. Deskripsi & Instruksi Pekerjaan</div>
    <div id="docDescription"></div>

    <!-- D. MATERIAL (jika ada) -->
    <div id="materialSection" style="display:none">
        <div class="section-title">D. Kebutuhan Material</div>
        <table class="grid-table">
            <thead>
                <tr>
                    <th style="width:5%">No</th>
                    <th>Nama Material / Suku Cadang</th>
                    <th style="width:15%">Satuan</th>
                    <th style="width:10%">Qty</th>
                </tr>
            </thead>
            <tbody id="materialBody"></tbody>
        </table>
    </div>

    <!-- E. KETENTUAN -->
    <div class="section-title" id="ketentuanTitle">D. Ketentuan Pelaksanaan</div>
    <ol class="ketentuan">
        <li>Surat Perintah Kerja ini berlaku sejak tanggal diterbitkan hingga pekerjaan dinyatakan selesai.</li>
        <li>Pelaksana wajib melaksanakan pekerjaan sesuai standar keselamatan kerja (K3) yang berlaku.</li>
        <li>Setiap perubahan lingkup pekerjaan harus mendapat persetujuan tertulis dari penanggung jawab.</li>
        <li>Apabila ditemukan kerusakan tambahan di luar lingkup WO ini, pelaksana wajib melaporkan sebelum bertindak.</li>
        <li>Pekerjaan dinyatakan selesai setelah diverifikasi dan laporan diunggah ke sistem.</li>
    </ol>

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <!-- Dibuat Oleh (Admin GA) -->
        <div class="sig-box">
            <div class="sig-title">Dibuat Oleh</div>
            <div class="sig-role">Manager / Penanggung Jawab GA</div>
            <!-- TTD Manager GA dari database -->
            <img id="docManagerSig" class="sig-img" src="" alt="TTD" style="display:none">
            <div id="docManagerSigEmpty" class="sig-empty-space"></div>
            <div class="sig-line"></div>
            <div class="sig-name" id="docManagerName">___________________</div>
            <div style="font-size:9pt;color:#555">NIP / NIK : ___________________</div>
        </div>

        <!-- Pelaksana (Tukang) — TTD KOSONG untuk tanda tangan manual -->
        <div class="sig-box">
            <div class="sig-title">Pelaksana</div>
            <div class="sig-role">Teknisi / Tukang</div>
            <div class="sig-empty-space"></div><!-- kosong, tanda tangan manual -->
            <div class="sig-line"></div>
            <div class="sig-name" id="docWorkerSig">___________________</div>
            <div style="font-size:9pt;color:#555">NIP / NIK : ___________________</div>
        </div>

        <!-- Mengetahui -->
        <div class="sig-box">
            <div class="sig-title">Mengetahui</div>
            <div class="sig-role">Pejabat Penanggung Jawab</div>
            <div class="sig-empty-space"></div>
            <div class="sig-line"></div>
            <div class="sig-name">___________________</div>
            <div style="font-size:9pt;color:#555">NIP / NIK : ___________________</div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="doc-footer">
        <span>Dokumen ini diterbitkan otomatis oleh sistem CMMS &mdash; <span id="footerCompany"></span></span>
        <span id="footerCode">Hal. 1 dari 1</span>
    </div>

</div><!-- end .page -->

<script>
function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
}
const token = getCookie('token');
if (!token) window.location.href = '/login';
const params = new URLSearchParams(window.location.search);
const woId   = params.get('id');
const woType = params.get('type');

if (!woId || isNaN(woId)) {
    document.getElementById('woPage').innerHTML = 
        '<p style="color:red;text-align:center;margin-top:40px">ID Work Order tidak valid</p>';
    throw new Error('Invalid WO ID: ' + woId);
}
// ── LOAD DATA ────────────────────────────────────────────────────────
async function loadWO() {
    const res  = await fetch(`/api/work-orders/${woId}`, {
        headers: { Authorization: 'Bearer ' + token }
    });
    if (!res.ok) {
        document.getElementById('woPage').innerHTML = '<p style="color:red;text-align:center;margin-top:40px">Data tidak ditemukan</p>';
        return;
    }
    const { wo, company, materials } = await res.json();

    renderCompany(company);
    renderWO(wo, materials);
}

// ── RENDER PERUSAHAAN ─────────────────────────────────────────────────
function renderCompany(c) {
    if (c.logo_url) {
        const logo = document.getElementById('docLogo');
        logo.src   = c.logo_url;
        logo.style.display = 'block';
    }
    document.getElementById('docName').textContent    = c.name ?? 'Nama Instansi';
    document.getElementById('docAddress').textContent = c.address ?? '';
    document.getElementById('docContact').textContent = [
        c.phone ? 'Telp: ' + c.phone : '',
        c.email ? 'Email: ' + c.email : '',
    ].filter(Boolean).join('  |  ');
    document.getElementById('footerCompany').textContent = c.name ?? '';

    // TTD Manager GA
    if (c.manager_sig_url) {
        const sig = document.getElementById('docManagerSig');
        sig.src   = c.manager_sig_url;
        sig.style.display = 'block';
        document.getElementById('docManagerSigEmpty').style.display = 'none';
    }
    if (c.manager_name) {
        document.getElementById('docManagerName').textContent = c.manager_name;
    }
}

// ── RENDER WORK ORDER ─────────────────────────────────────────────────
function renderWO(wo, materials) {
    const isRepair    = wo.type === 'repair';
    const typeBadge   = document.getElementById('typeBadge');
    typeBadge.textContent = isRepair ? '🔧 Perbaikan Gedung' : '🗓 Maintenance Terjadwal';
    typeBadge.className   = 'type-badge ' + (isRepair ? 'badge-repair' : 'badge-scheduled');

    document.getElementById('docWoNumber').textContent  = wo.wo_number ?? '-';
    document.getElementById('docCreatedAt').textContent = formatDate(wo.created_at);
    document.getElementById('docStatus').textContent    = statusLabel(wo.status);
    document.getElementById('docWorker').textContent    = wo.worker?.name ?? '-';
    document.getElementById('docWorkerSig').textContent = wo.worker?.name ?? '___________________';
    document.getElementById('docCreatedBy').textContent = wo.created_by_user?.name ?? '-';
    document.getElementById('footerCode').textContent   = `WO/${isRepair ? 'REP' : 'SCH'}/${new Date().getFullYear()} — Hal. 1 dari 1`;

    // Informasi Pekerjaan
    const rows = [
        ['Judul Pekerjaan', `<strong>${wo.title}</strong>`],
        ['Kategori', [wo.category?.name, wo.sub_category?.name].filter(Boolean).join(' / ') || '-'],
    ];
    if (isRepair) {
        rows.push(['Lokasi / Gedung', wo.repair_request?.branch?.name ?? '-']);
        rows.push(['Tanggal Dijadwalkan', wo.schedule_date ? formatDate(wo.schedule_date) : '-']);
        if (wo.urgency) rows.push(['Prioritas', urgencyLabel(wo.urgency)]);
    } else {
        rows.push(['Periode Maintenance', periodLabel(wo.period)]);
        rows.push(['Tanggal Pelaksanaan', wo.schedule_date ? formatDate(wo.schedule_date) : '-']);
    }

    document.getElementById('infoTable').innerHTML = rows.map(([label, value]) => `
        <tr>
            <td class="label">${label}</td>
            <td class="sep">:</td>
            <td class="value">${value}</td>
        </tr>
    `).join('');

    // Deskripsi / instruksi
    const descEl = document.getElementById('docDescription');
    if (isRepair) {
        descEl.innerHTML = `
            <p style="font-size:10pt;font-weight:bold;margin-bottom:4px">Deskripsi Kerusakan / Keluhan:</p>
            <div class="notes-box">${wo.description ?? '-'}</div>
            ${wo.note ? `<p style="font-size:10pt;font-weight:bold;margin:8px 0 4px">Instruksi Tambahan:</p><div class="notes-box">${wo.note}</div>` : ''}
            <p style="font-size:10pt;font-weight:bold;margin:8px 0 6px">Checklist Pelaksanaan:</p>
            <ul class="checklist">
                <li>Identifikasi dan dokumentasikan sumber kerusakan</li>
                <li>Lakukan perbaikan sesuai standar teknis yang berlaku</li>
                <li>Gunakan material/suku cadang sesuai spesifikasi</li>
                <li>Uji fungsi setelah perbaikan selesai</li>
                <li>Bersihkan area kerja setelah pekerjaan selesai</li>
                <li>Laporkan hasil perbaikan melalui aplikasi CMMS</li>
            </ul>
        `;
    } else {
        descEl.innerHTML = `
            <div style="border-left:4px solid #1565c0;background:#f3f8ff;padding:8px 12px;font-size:10.5pt;margin-bottom:8px;border-radius:0 4px 4px 0">
                <strong>Instruksi:</strong><br>${wo.note ?? 'Laksanakan maintenance sesuai SOP.'}
            </div>
            <p style="font-size:10pt;font-weight:bold;margin-bottom:6px">Checklist Pelaksanaan:</p>
            <ul class="checklist">
                <li>Periksa kondisi awal peralatan / area sebelum pekerjaan dimulai</li>
                <li>Laksanakan pekerjaan sesuai prosedur standar (SOP)</li>
                <li>Catat temuan selama pelaksanaan</li>
                <li>Pastikan area/peralatan dalam kondisi bersih dan aman setelah selesai</li>
                <li>Ambil foto dokumentasi kondisi sebelum dan sesudah</li>
                <li>Laporkan selesai melalui aplikasi CMMS</li>
            </ul>
        `;
    }

    // Material
    if (materials && materials.length > 0) {
        document.getElementById('materialSection').style.display = 'block';
        document.getElementById('ketentuanTitle').textContent = 'E. Ketentuan Pelaksanaan';
        document.getElementById('materialBody').innerHTML = materials.map((m, i) => `
            <tr>
                <td class="center">${i+1}</td>
                <td>${m.item_name ?? m.name}</td>
                <td class="center">${m.unit ?? '-'}</td>
                <td class="center">${m.qty}</td>
            </tr>
        `).join('');
    }
}


// ── HELPERS ───────────────────────────────────────────────────────────
function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}
function statusLabel(s) {
    const map = { issued: 'Diterbitkan', confirmed: 'Dikonfirmasi', done: 'Selesai', approved: 'Disetujui', on_progress: 'Sedang Dikerjakan' };
    return map[s] ?? s;
}
function urgencyLabel(u) {
    const map = { high: '🔴 Prioritas / Tinggi', medium: '🟡 Segera / Sedang', low: '🟢 Santai / Rendah' };
    return map[u] ?? u;
}
function periodLabel(p) {
    const map = { weekly: 'Mingguan', monthly: 'Bulanan', quarterly: 'Triwulan', yearly: 'Tahunan' };
    return map[p] ?? p;
}

// ── INIT ──────────────────────────────────────────────────────────────
loadWO();
</script>
</body>
</html>