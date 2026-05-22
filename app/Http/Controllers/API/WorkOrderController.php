<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RepairRequest;
use App\Models\ScheduledMaintenance;
use App\Models\WorkOrder;
use App\Models\Company;
use App\Helpers\SpkHelper;
use Carbon\Carbon;

class WorkOrderController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $wos  = WorkOrder::with(['category', 'worker'])
                ->where('company_id', $user->company_id)
                ->latest()
                ->get();
    return response()->json($wos);
}
    // ══════════════════════════════════════════════════════════════
    //  COMPANY SETTINGS  (logo, alamat, TTD manager)
    // ══════════════════════════════════════════════════════════════

    /** GET /api/company-settings */
    public function getSettings()
    {
        $user    = auth()->user();
        $company = Company::findOrFail($user->company_id);

        return response()->json([
            'id'                => $company->id,
            'name'              => $company->name,
            'address'           => $company->address,
            'phone'             => $company->phone,
            'email'             => $company->email,
            'logo_url'          => $company->logo
                                    ? asset('storage/' . $company->logo)
                                    : null,
            'manager_name'      => $company->manager_name,
            'manager_sig_url'   => $company->manager_signature
                                    ? asset('storage/' . $company->manager_signature)
                                    : null,
        ]);
    }

    /** POST /api/company-settings */
    public function saveSettings(Request $request)
    {
        $user    = auth()->user();
        $company = Company::findOrFail($user->company_id);

        $request->validate([
            'name'              => 'sometimes|string|max:255',
            'address'           => 'nullable|string',
            'phone'             => 'nullable|string|max:50',
            'email'             => 'nullable|email',
            'manager_name'      => 'nullable|string|max:255',
            'logo'              => 'nullable|image|max:2048',
            'manager_signature' => 'nullable|image|max:2048',
        ]);

        if ($request->filled('name'))         $company->name         = $request->name;
        if ($request->filled('address'))      $company->address      = $request->address;
        if ($request->filled('phone'))        $company->phone        = $request->phone;
        if ($request->filled('email'))        $company->email        = $request->email;
        if ($request->filled('manager_name')) $company->manager_name = $request->manager_name;

        if ($request->hasFile('logo')) {
            $company->logo = $request->file('logo')->store('company', 'public');
        }
        if ($request->hasFile('manager_signature')) {
            $company->manager_signature = $request->file('manager_signature')
                                                   ->store('signatures', 'public');
        }

        $company->save();

        return response()->json(['message' => 'Pengaturan berhasil disimpan']);
    }

    // ══════════════════════════════════════════════════════════════
    //  KIRIM SPK — PERBAIKAN GEDUNG
    // ══════════════════════════════════════════════════════════════

    /** POST /api/requests/{id}/send-spk */
    public function sendRepairSpk($id)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'super_admin'])) {
        return response()->json(['message' => 'Tidak punya akses'], 403);
    }
        $req  = RepairRequest::with(['category', 'subCategory', 'branch', 'technician'])
                    ->where('company_id', $user->company_id)
                    ->findOrFail($id);

        if (!$req->technician_id) {
            return response()->json(['message' => 'Tukang belum ditentukan'], 422);
        }
        if (!$req->schedule_date) {
            return response()->json(['message' => 'Tukang belum mengatur jadwal'], 422);
        }

        // Generate nomor SPK jika belum ada
        if (!$req->spk_number) {
            $req->spk_number = SpkHelper::generate('repair');
        }
        $req->spk_sent_at = Carbon::now();
        $req->spk_sent_by = $user->id;
        $req->save();

        // Buat / update record di tabel work_orders
        $wo = WorkOrder::updateOrCreate(
            ['repair_request_id' => $req->id],
            [
                'wo_number'        => $req->spk_number,
                'type'             => 'repair',
                'title'            => $req->title,
                'description'      => $req->description,
                'status'           => 'issued',
                'company_id'       => $req->company_id,
                'category_id'      => $req->category_id,
                'sub_category_id'  => $req->sub_category_id,
                'branch_id'        => $req->branch_id,
                'worker_id'        => $req->technician_id,
                'created_by'       => $user->id,
                'schedule_date'    => $req->schedule_date,
                'urgency'          => $req->urgency,
            ]
        );

        // In-app notification ke tukang
        \App\Models\Notification::create([
            'user_id' => $req->technician_id,
            'title'   => 'SPK Diterima: ' . $req->title,
            'body'    => 'Nomor SPK: ' . $req->spk_number . '. Jadwal: ' . $req->schedule_date,
            'type'    => 'spk',
            'data'    => json_encode(['work_order_id' => $wo->id]),
        ]);

        return response()->json([
            'message'     => 'SPK berhasil dikirim',
            'spk_number'  => $req->spk_number,
            'spk_sent_at' => $req->spk_sent_at,
            'wo_id'       => $wo->id,
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    //  KIRIM SPK — MAINTENANCE TERJADWAL
    // ══════════════════════════════════════════════════════════════

    /** POST /api/scheduled-maintenances/{id}/send-spk */
    public function sendScheduledSpk($id)
    {
        $user  = auth()->user();
        $sched = ScheduledMaintenance::with(['category', 'worker'])
                     ->where('company_id', $user->company_id)
                     ->findOrFail($id);

        if (!$sched->worker_id) {
            return response()->json(['message' => 'Tukang belum ditentukan'], 422);
        }

        if (!$sched->spk_number) {
            $sched->spk_number = SpkHelper::generate('scheduled');
        }
        $sched->spk_sent_at = Carbon::now();
        $sched->spk_sent_by = $user->id;
        $sched->save();

        $wo = WorkOrder::updateOrCreate(
            ['scheduled_maintenance_id' => $sched->id],
            [
                'wo_number'                 => $sched->spk_number,
                'type'                      => 'scheduled',
                'title'                     => $sched->title,
                'note'                      => $sched->note,
                'status'                    => 'issued',
                'company_id'                => $sched->company_id,
                'category_id'               => $sched->category_id,
                'sub_category_id'           => $sched->scheduled_sub_category_id ?? null,
                'worker_id'                 => $sched->worker_id,
                'created_by'                => $user->id,
                'schedule_date'             => $sched->scheduled_date,
                'period'                    => $sched->period,
            ]
        );

        \App\Models\Notification::create([
            'user_id' => $sched->worker_id,
            'title'   => 'SPK Maintenance: ' . $sched->title,
            'body'    => 'Nomor SPK: ' . $sched->spk_number . '. Jadwal: ' . $sched->scheduled_date,
            'type'    => 'spk',
            'data'    => json_encode(['work_order_id' => $wo->id]),
        ]);

        return response()->json([
            'message'     => 'SPK berhasil dikirim',
            'spk_number'  => $sched->spk_number,
            'wo_id'       => $wo->id,
        ]);
    }

    // ══════════════════════════════════════════════════════════════
    //  DATA WORK ORDER (untuk blade template)
    // ══════════════════════════════════════════════════════════════

    /** GET /api/work-orders/{id} */
    public function show($id)
    {
        $user = auth()->user();
        $wo   = WorkOrder::with([
                    'repairRequest.branch',
                    'repairRequest.materials',
                    'scheduledMaintenance',
                    'category',
                    'subCategory',
                    'worker',
                    'createdByUser',
                ])
                ->where('company_id', $user->company_id)
                ->findOrFail($id);

        $company = Company::findOrFail($user->company_id);

        return response()->json([
            'wo'        => $wo,
            'company'   => [
                'name'            => $company->name,
                'address'         => $company->address,
                'phone'           => $company->phone,
                'email'           => $company->email,
                'logo_url'        => $company->logo ? asset('storage/' . $company->logo) : null,
                'manager_name'    => $company->manager_name,
                'manager_sig_url' => $company->manager_signature
                                      ? asset('storage/' . $company->manager_signature)
                                      : null,
            ],
            'materials' => $wo->repairRequest?->materials ?? [],
        ]);
    }

    
}