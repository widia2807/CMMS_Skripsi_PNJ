<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RepairRequest as WorkRequest;
use App\Models\Category;
use App\Models\User;

class TechnicianDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $incoming = WorkRequest::where('technician_id', $user->id)
            ->whereIn('status', ['approved', 'scheduled', 'waiting_material', 'on_progress'])
            ->count();

        $completed = WorkRequest::where('technician_id', $user->id)
            ->where('status', 'done')
            ->count();

        return response()->json([
            'incoming_jobs' => $incoming,
            'completed_jobs' => $completed
        ]);
    }

    public function jobs()
{
    $user = auth()->user();

    return WorkRequest::with(['branch', 'categoryRelation'])
        ->where('technician_id', $user->id)
        ->latest()
        ->get()
        ->map(function ($req) {
            return [
                'id' => $req->id,
                'title' => $req->title,
                'description' => $req->description,
                'status' => $req->status,
                'photo' => $req->photo,

                // 🔥 INI YANG KAMU BUTUH
                'branch' => $req->branch->name ?? '-',
                'category' => $req->categoryRelation->name ?? '-',
            ];
        });
}

    // ================= ASSIGN =================
    public function assignTechnician(Request $request, $id)
    {
        $request->validate([
            'technician_id' => 'required|exists:users,id'
        ]);

        $req = WorkRequest::findOrFail($id);

        $req->technician_id = $request->technician_id;
        $req->status = 'approved';
        $req->save();

        return response()->json(['message' => 'Technician assigned']);
    }

    public function inspectJob(Request $request, $id)
{
    $user = auth()->user();

    $req = WorkRequest::where('id', $id)
        ->where('technician_id', $user->id)
        ->first();

    if (!$req) {
        return response()->json([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    if ($req->status !== 'scheduled') {
        return response()->json([
            'message' => 'Job belum dijadwalkan'
        ], 400);
    }

    $request->validate([
        'needs_material' => 'required',
        'notes' => 'nullable|string'
    ]);

    $needsMaterial = filter_var($request->needs_material, FILTER_VALIDATE_BOOLEAN);

    //$req->inspection_notes = $request->notes;

    if ($needsMaterial) {
        $req->status = 'waiting_material';
    } else {
        $req->status = 'on_progress';
    }

    $req->save();

    return response()->json([
        'message' => 'Inspection selesai'
    ]);
}

    public function technicians(Request $request)
{
    try {
        $user = auth()->user(); // ← ini yang kurang

        $query = User::where('role', 'technician')
            ->where('company_id', $user->company_id);

        return response()->json($query->get(['id', 'name']));

    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

   public function schedule(Request $request, $id)
{
    $user = auth()->user();

    $req = WorkRequest::where('id', $id)
        ->where('technician_id', $user->id)
        ->first();

    if (!$req) {
        return response()->json([
            'message' => 'Job tidak ditemukan / bukan milik anda'
        ], 404);
    }

    if (!in_array($req->status, ['approved', 'waiting_material'])) {
        return response()->json(['message' => 'Tidak bisa dijadwalkan'], 400);
    }

    $request->validate(['schedule_date' => 'required|date']);

    $req->schedule_date = $request->schedule_date;
    $req->status = 'scheduled'; // ← fix: was 'material_ready'
    $req->save();

    return response()->json(['message' => 'Jadwal diset']);
}

    // ================= START JOB =================
 public function startJob($id)
{
    $user = auth()->user();

    $req = WorkRequest::where('id', $id)
        ->where('technician_id', $user->id)
        ->first();

    // ← tambah 'material_ready'
    if (!in_array($req->status, ['waiting_material', 'material_ready'])) {
        return response()->json([
            'message' => 'Belum siap dikerjakan'
        ], 400);
    }

    $req->status = 'on_progress';
    $req->save();

    return response()->json(['message' => 'Pekerjaan dimulai']);
}

    // ================= COMPLETE =================
    public function completeJob($id)
    {
        $user = auth()->user();

        $req = WorkRequest::where('id', $id)
            ->where('technician_id', $user->id)
            ->first();

        if ($req->status !== 'on_progress') {
            return response()->json([
                'message' => 'Pekerjaan belum dimulai'
            ], 400);
        }

        $req->status = 'done';
        $req->save();

        return response()->json([
            'message' => 'Pekerjaan selesai'
        ]);
    }

    // ================= VERIFY (PIC) =================
    public function verifyJob($id)
    {
        $req = WorkRequest::findOrFail($id);

        if ($req->status !== 'done') {
            return response()->json([
                'message' => 'Belum selesai'
            ], 400);
        }

        $req->status = 'verified';
        $req->save();

        return response()->json([
            'message' => 'Pekerjaan diverifikasi'
        ]);
    }

    public function requestMaterial(Request $request, $id)
{
    $request->validate([
        'items' => 'required|array'
    ]);

    foreach ($request->items as $item) {
        \App\Models\MaterialRequest::create([
            'repair_request_id' => $id,
            'item_name' => $item['name'],
            'qty' => $item['qty'],
            'unit' => $item['unit'] ?? null,
            'status' => 'pending'
        ]);
    }

    return response()->json(['message' => 'Material diajukan']);
}

}