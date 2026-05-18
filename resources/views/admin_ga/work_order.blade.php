<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Order — {{ $wo->wo_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #111;
            background: #fff;
        }

        /* ── PAGE SETUP ── */
        @page { size: A4; margin: 2cm 2.5cm; }
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 1.5cm 2cm;
            background: #fff;
        }

        /* ── HEADER / KOP ── */
        .kop {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 12px;
            border-bottom: 3px double #111;
            margin-bottom: 4px;
        }
        .kop img { width: 70px; height: 70px; object-fit: contain; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .instansi  { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .kop-text .alamat    { font-size: 9pt; color: #333; margin-top: 2px; }
        .kop-text .telp      { font-size: 9pt; color: #333; }
        .kop-divider         { border: none; border-top: 1px solid #111; margin: 4px 0 16px; }

        /* ── JUDUL DOKUMEN ── */
        .doc-title {
            text-align: center;
            margin-bottom: 14px;
        }
        .doc-title h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .doc-title .wo-type-badge {
            display: inline-block;
            font-size: 9pt;
            font-weight: bold;
            padding: 2px 12px;
            border-radius: 20px;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }
        .badge-repair     { background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; }
        .badge-scheduled  { background: #e3f2fd; color: #0d47a1; border: 1px solid #90caf9; }

        /* ── NOMOR & TANGGAL ── */
        .wo-meta {
            display: flex;
            justify-content: space-between;
            font-size: 10pt;
            margin-bottom: 16px;
            padding: 8px 12px;
            background: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* ── SECTION TITLES ── */
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #222;
            color: #fff;
            padding: 4px 10px;
            margin: 14px 0 8px;
        }

        /* ── INFO TABLE ── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
            margin-bottom: 6px;
        }
        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .info-table td.label {
            width: 38%;
            font-weight: bold;
            color: #333;
        }
        .info-table td.sep    { width: 3%;  }
        .info-table td.value  { width: 59%; }
        .info-table tr:nth-child(even) td { background: #fafafa; }

        /* ── FULL BORDER TABLE ── */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 8px;
        }
        .grid-table th, .grid-table td {
            border: 1px solid #aaa;
            padding: 5px 8px;
            text-align: left;
            vertical-align: top;
        }
        .grid-table th {
            background: #eee;
            font-weight: bold;
            text-align: center;
        }
        .grid-table td.center { text-align: center; }
        .grid-table td.num    { text-align: right; }

        /* ── STATUS BADGE ── */
        .status-badge {
            display: inline-block;
            font-size: 9pt;
            font-weight: bold;
            padding: 1px 10px;
            border-radius: 20px;
            border: 1px solid #ccc;
        }

        /* ── CHECKLIST ── */
        .checklist { list-style: none; padding-left: 4px; }
        .checklist li { display: flex; align-items: flex-start; gap: 6px; padding: 2px 0; font-size: 10.5pt; }
        .checklist li::before {
            content: '☐';
            font-size: 12pt;
            line-height: 1;
            flex-shrink: 0;
        }

        /* ── NOTES BOX ── */
        .notes-box {
            border: 1px solid #bbb;
            border-radius: 4px;
            padding: 8px 10px;
            min-height: 50px;
            font-size: 10.5pt;
            color: #333;
            margin-bottom: 8px;
            background: #fafafa;
        }

        /* ── INSTRUCTION BOX (maintenance) ── */
        .instruction-box {
            border-left: 4px solid #1565c0;
            background: #f3f8ff;
            padding: 8px 12px;
            font-size: 10.5pt;
            margin-bottom: 8px;
            border-radius: 0 4px 4px 0;
        }

        /* ── COMPLETION BOX ── */
        .completion-box {
            border: 1px dashed #aaa;
            border-radius: 4px;
            padding: 10px 12px;
            font-size: 10.5pt;
            margin-top: 6px;
            background: #fffde7;
        }
        .completion-box .comp-label { font-size: 9pt; color: #666; font-weight: bold; margin-bottom: 4px; }
        .completion-box .comp-value { font-size: 11pt; }

        /* ── PERIOD BADGE ── */
        .period-badge {
            display: inline-block;
            font-size: 9pt;
            padding: 1px 8px;
            background: #e8f4fd;
            color: #1565c0;
            border: 1px solid #90caf9;
            border-radius: 20px;
            font-weight: bold;
        }

        /* ── SIGNATURE ── */
        .signature-section {
            margin-top: 24px;
            display: flex;
            gap: 0;
            border-top: 1px solid #ddd;
            padding-top: 16px;
        }
        .sig-box {
            flex: 1;
            text-align: center;
            padding: 0 12px;
            border-right: 1px dashed #ccc;
        }
        .sig-box:last-child { border-right: none; }
        .sig-box .sig-title  { font-size: 9.5pt; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; }
        .sig-box .sig-role   { font-size: 9pt; color: #555; margin-bottom: 60px; }
        .sig-box .sig-line   { border-top: 1px solid #333; margin: 0 10%; }
        .sig-box .sig-name   { font-size: 10pt; font-weight: bold; margin-top: 4px; }
        .sig-box .sig-nip    { font-size: 9pt; color: #444; }

        /* ── FOOTER ── */
        .doc-footer {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            font-size: 8.5pt;
            color: #888;
        }

        /* ── PRINT BUTTON ── */
        .print-bar {
            position: fixed;
            top: 16px; right: 16px;
            z-index: 100;
            display: flex;
            gap: 8px;
        }
        .print-bar button {
            padding: 8px 18px;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-print  { background: #1a1a1a; color: #fff; }
        .btn-close  { background: #eee; color: #333; }
        .btn-print:hover { background: #333; }
    </style>
</head>

<body>

{{-- ── PRINT BAR (tidak tercetak) ── --}}
<div class="print-bar no-print">
    <button class="btn-print" onclick="window.print()">🖨 Cetak</button>
    <button class="btn-close" onclick="window.history.back()">✕ Tutup</button>
</div>

<div class="page">

    {{-- ══════════════════════════════════════
         KOP SURAT
    ══════════════════════════════════════ --}}
    <div class="kop">
        @if($company->logo)
            <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo">
        @endif
        <div class="kop-text">
            <div class="instansi">{{ $company->name ?? 'Nama Instansi' }}</div>
            <div class="alamat">{{ $company->address ?? 'Alamat Instansi' }}</div>
            <div class="telp">
                Telp: {{ $company->phone ?? '-' }}
                @if($company->email) &nbsp;|&nbsp; Email: {{ $company->email }} @endif
            </div>
        </div>
    </div>
    <hr class="kop-divider">

    {{-- ══════════════════════════════════════
         JUDUL DOKUMEN
    ══════════════════════════════════════ --}}
    <div class="doc-title">
        <h1>Surat Perintah Kerja (Work Order)</h1>
        @if($wo->type === 'scheduled')
            <div class="wo-type-badge badge-scheduled">🗓 Maintenance Terjadwal</div>
        @else
            <div class="wo-type-badge badge-repair">🔧 Perbaikan Gedung</div>
        @endif
    </div>

    {{-- ── NOMOR & TANGGAL ── --}}
    <div class="wo-meta">
        <div><strong>Nomor WO</strong> &nbsp;:&nbsp; {{ $wo->wo_number }}</div>
        <div><strong>Tanggal Terbit</strong> &nbsp;:&nbsp; {{ \Carbon\Carbon::parse($wo->created_at)->isoFormat('D MMMM Y') }}</div>
        <div>
            <strong>Status</strong> &nbsp;:&nbsp;
            <span class="status-badge">
                @switch($wo->status)
                    @case('pending')      Menunggu Konfirmasi @break
                    @case('confirmed')    Dikonfirmasi        @break
                    @case('in_progress')  Sedang Berjalan     @break
                    @case('approved')     Disetujui           @break
                    @case('scheduled')    Terjadwal           @break
                    @case('on_progress')  Sedang Dikerjakan   @break
                    @case('done')         Selesai             @break
                    @default              {{ $wo->status }}
                @endswitch
            </span>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         A. INFORMASI PEKERJAAN
    ══════════════════════════════════════ --}}
    <div class="section-title">A. Informasi Pekerjaan</div>

    <table class="info-table">
        <tr>
            <td class="label">Judul Pekerjaan</td>
            <td class="sep">:</td>
            <td class="value"><strong>{{ $wo->title }}</strong></td>
        </tr>
        <tr>
            <td class="label">Kategori</td>
            <td class="sep">:</td>
            <td class="value">
                {{ $wo->category->name ?? '-' }}
                @if(isset($wo->subCategory) && $wo->subCategory)
                    &nbsp;/&nbsp; <em>{{ $wo->subCategory->name }}</em>
                @endif
            </td>
        </tr>

        @if($wo->type === 'scheduled')
        {{-- ── KHUSUS MAINTENANCE TERJADWAL ── --}}
        <tr>
            <td class="label">Periode Maintenance</td>
            <td class="sep">:</td>
            <td class="value">
                <span class="period-badge">
                    @switch($wo->period)
                        @case('weekly')    Mingguan  @break
                        @case('monthly')   Bulanan   @break
                        @case('quarterly') Triwulan  @break
                        @case('yearly')    Tahunan   @break
                        @default           {{ $wo->period }}
                    @endswitch
                </span>
            </td>
        </tr>
        <tr>
            <td class="label">Tanggal Pelaksanaan</td>
            <td class="sep">:</td>
            <td class="value">{{ \Carbon\Carbon::parse($wo->scheduled_date)->isoFormat('D MMMM Y') }}</td>
        </tr>
        @else
        {{-- ── KHUSUS PERBAIKAN GEDUNG ── --}}
        <tr>
            <td class="label">Lokasi / Gedung</td>
            <td class="sep">:</td>
            <td class="value">{{ $wo->branch->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Ruangan</td>
            <td class="sep">:</td>
            <td class="value">{{ $wo->room ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Pengajuan</td>
            <td class="sep">:</td>
            <td class="value">{{ \Carbon\Carbon::parse($wo->created_at)->isoFormat('D MMMM Y') }}</td>
        </tr>
        @if($wo->schedule_date)
        <tr>
            <td class="label">Tanggal Dijadwalkan</td>
            <td class="sep">:</td>
            <td class="value">{{ \Carbon\Carbon::parse($wo->schedule_date)->isoFormat('D MMMM Y') }}</td>
        </tr>
        @endif
        @endif

        <tr>
            <td class="label">Prioritas</td>
            <td class="sep">:</td>
            <td class="value">
                @if(isset($wo->priority))
                    @switch($wo->priority)
                        @case('high')   <strong style="color:#c62828">🔴 Tinggi</strong>   @break
                        @case('medium') <strong style="color:#f57c00">🟡 Sedang</strong>   @break
                        @case('low')    <strong style="color:#388e3c">🟢 Rendah</strong>   @break
                        @default        {{ $wo->priority }}
                    @endswitch
                @else
                    —
                @endif
            </td>
        </tr>
    </table>

    {{-- ══════════════════════════════════════
         B. PENUGASAN PELAKSANA
    ══════════════════════════════════════ --}}
    <div class="section-title">B. Penugasan Pelaksana</div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Teknisi / Tukang</td>
            <td class="sep">:</td>
            <td class="value"><strong>{{ $wo->worker->name ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="sep">:</td>
            <td class="value">Teknisi / Tukang Pemeliharaan</td>
        </tr>
        <tr>
            <td class="label">Ditugaskan Oleh</td>
            <td class="sep">:</td>
            <td class="value">{{ $wo->assignedBy->name ?? $wo->createdBy->name ?? '-' }}</td>
        </tr>
        @if($wo->type === 'scheduled' && $wo->worker_confirmed_at)
        <tr>
            <td class="label">Dikonfirmasi Tukang</td>
            <td class="sep">:</td>
            <td class="value">{{ \Carbon\Carbon::parse($wo->worker_confirmed_at)->isoFormat('D MMMM Y, HH:mm') }} WIB</td>
        </tr>
        @endif
    </table>

    {{-- ══════════════════════════════════════
         C. DESKRIPSI / INSTRUKSI PEKERJAAN
    ══════════════════════════════════════ --}}
    <div class="section-title">C. Deskripsi & Instruksi Pekerjaan</div>

    @if($wo->type === 'scheduled')
        {{-- Maintenance: tampilkan instruksi dengan styling biru --}}
        <div class="instruction-box">
            <strong>Instruksi Khusus:</strong><br>
            {{ $wo->note ?? 'Laksanakan maintenance sesuai SOP yang berlaku dan checklist terlampir.' }}
        </div>

        {{-- Checklist standar maintenance --}}
        <p style="font-size:10pt; font-weight:bold; margin-bottom:6px;">Checklist Pelaksanaan:</p>
        <ul class="checklist">
            <li>Periksa kondisi awal peralatan / area sebelum pekerjaan dimulai</li>
            <li>Laksanakan pekerjaan sesuai prosedur standar (SOP)</li>
            <li>Catat temuan selama pelaksanaan (kerusakan, kebocoran, anomali, dll)</li>
            <li>Pastikan area/peralatan dalam kondisi bersih dan aman setelah selesai</li>
            <li>Ambil foto dokumentasi kondisi sebelum dan sesudah</li>
            <li>Laporkan selesai melalui aplikasi CMMS</li>
        </ul>
    @else
        {{-- Perbaikan: tampilkan deskripsi kerusakan --}}
        <p style="font-size:10pt; font-weight:bold; margin-bottom:4px;">Deskripsi Kerusakan / Keluhan:</p>
        <div class="notes-box">{{ $wo->description ?? '-' }}</div>

        @if($wo->note)
        <p style="font-size:10pt; font-weight:bold; margin: 8px 0 4px;">Instruksi Tambahan:</p>
        <div class="notes-box">{{ $wo->note }}</div>
        @endif

        {{-- Checklist standar perbaikan --}}
        <p style="font-size:10pt; font-weight:bold; margin-bottom:6px; margin-top:8px;">Checklist Pelaksanaan:</p>
        <ul class="checklist">
            <li>Identifikasi dan dokumentasikan sumber kerusakan</li>
            <li>Lakukan perbaikan sesuai standar teknis yang berlaku</li>
            <li>Gunakan material/suku cadang sesuai spesifikasi</li>
            <li>Uji fungsi setelah perbaikan selesai</li>
            <li>Bersihkan area kerja setelah pekerjaan selesai</li>
            <li>Laporkan hasil perbaikan melalui aplikasi CMMS</li>
        </ul>
    @endif

    {{-- ══════════════════════════════════════
         D. MATERIAL (hanya jika ada)
    ══════════════════════════════════════ --}}
    @if(isset($materials) && count($materials) > 0)
    <div class="section-title">D. Kebutuhan Material</div>

    <table class="grid-table">
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th>Nama Material / Suku Cadang</th>
                <th style="width:15%">Satuan</th>
                <th style="width:12%">Qty</th>
                <th style="width:20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $i => $mat)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>{{ $mat->name }}</td>
                <td class="center">{{ $mat->unit }}</td>
                <td class="center">{{ $mat->qty }}</td>
                <td>{{ $mat->note ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- ══════════════════════════════════════
         E. KETENTUAN PELAKSANAAN
    ══════════════════════════════════════ --}}
    <div class="section-title">{{ isset($materials) && count($materials) > 0 ? 'E' : 'D' }}. Ketentuan Pelaksanaan</div>

    <ol style="font-size:10.5pt; padding-left:18px; line-height:1.7;">
        <li>Surat Perintah Kerja ini berlaku sejak tanggal diterbitkan hingga pekerjaan dinyatakan selesai.</li>
        <li>Pelaksana wajib melaksanakan pekerjaan sesuai standar keselamatan kerja (K3) yang berlaku.</li>
        <li>Setiap perubahan lingkup pekerjaan harus mendapat persetujuan tertulis dari penanggung jawab.</li>
        <li>Apabila ditemukan kerusakan tambahan di luar lingkup WO ini, pelaksana wajib melaporkan sebelum bertindak.</li>
        <li>Pekerjaan dinyatakan selesai setelah diverifikasi oleh pengawas dan laporan diunggah ke sistem.</li>
        <li>Keterlambatan penyelesaian tanpa alasan yang sah akan menjadi catatan evaluasi kinerja.</li>
    </ol>

    {{-- ══════════════════════════════════════
         F. LAPORAN PENYELESAIAN (jika sudah done)
    ══════════════════════════════════════ --}}
    @if(in_array($wo->status, ['done', 'verified']) && $wo->completed_at)
    <div class="section-title" style="background:#1b5e20;">
        {{ isset($materials) && count($materials) > 0 ? 'F' : 'E' }}. Laporan Penyelesaian
    </div>

    <div class="completion-box">
        <div class="comp-label">Tanggal Selesai</div>
        <div class="comp-value">{{ \Carbon\Carbon::parse($wo->completed_at)->isoFormat('D MMMM Y, HH:mm') }} WIB</div>

        @if($wo->completion_note)
        <div class="comp-label" style="margin-top:8px;">Catatan Penyelesaian</div>
        <div class="comp-value">{{ $wo->completion_note }}</div>
        @endif

        @if($wo->completion_photo)
        <div class="comp-label" style="margin-top:8px;">Foto Dokumentasi</div>
        <img src="{{ asset('storage/' . $wo->completion_photo) }}"
             style="max-width:280px; max-height:200px; border-radius:6px; border:1px solid #ccc; margin-top:4px;">
        @endif
    </div>
    @endif

    {{-- ══════════════════════════════════════
         TANDA TANGAN
    ══════════════════════════════════════ --}}
    <div class="signature-section">
        <div class="sig-box">
            <div class="sig-title">Dibuat Oleh</div>
            <div class="sig-role">Admin / Koordinator</div>
            <div class="sig-line"></div>
            <div class="sig-name">{{ $wo->createdBy->name ?? '___________________' }}</div>
            <div class="sig-nip">NIP / NIK : ___________________</div>
        </div>

        <div class="sig-box">
            <div class="sig-title">Pelaksana</div>
            <div class="sig-role">Teknisi / Tukang</div>
            <div class="sig-line"></div>
            <div class="sig-name">{{ $wo->worker->name ?? '___________________' }}</div>
            <div class="sig-nip">NIP / NIK : ___________________</div>
        </div>

        <div class="sig-box">
            <div class="sig-title">Mengetahui</div>
            <div class="sig-role">Pejabat Penanggung Jawab</div>
            <div class="sig-line"></div>
            <div class="sig-name">___________________</div>
            <div class="sig-nip">NIP / NIK : ___________________</div>
        </div>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="doc-footer">
        <span>Dokumen ini diterbitkan otomatis oleh sistem CMMS &mdash; {{ $company->name ?? '' }}</span>
        <span>WO/{{ $wo->type === 'scheduled' ? 'SCH' : 'REP' }}/{{ \Carbon\Carbon::now()->format('Y') }} &mdash; Hal. 1 dari 1</span>
    </div>

</div>{{-- end .page --}}

</body>
</html>