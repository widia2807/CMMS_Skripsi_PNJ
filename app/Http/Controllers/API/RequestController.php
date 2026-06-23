<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Helpers\SpkHelper;
use Carbon\Carbon;
use App\Models\RepairRequest as RequestModel;

class RequestController extends Controller
{
   public function store(Request $request)
{
    $user = auth()->user();

    if ($user->role !== 'pic') {
        return response()->json(['message' => 'Hanya PIC yang bisa mengajukan'], 403);
    }

    $data = $request->validate([
        'title'           => 'required|string|max:255',
        'description'     => 'required|string',
        'category_id'     => 'required|exists:categories,id',
        'sub_category_id' => 'required|exists:sub_categories,id',
        'photo'           => 'nullable|image|max:2048',
    ]);

    $data['user_id']    = $user->id;
    $data['branch_id']  = $user->branch_id;
    $data['company_id'] = $user->company_id;
    $data['status']     = 'pending';

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('requests', 'public');
    }

    $req = RequestModel::create($data);

    return response()->json([
        'message' => 'Pengajuan berhasil dibuat',
        'data'    => $req
    ]);
}

public function index()
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    if ($user->role === 'pic') {
        $data = RequestModel::with(['categoryRelation', 'technician', 'branch', 'subCategory', 'materials'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    } else {
        $data = RequestModel::with(['categoryRelation', 'technician', 'branch', 'subCategory', 'materials'])
            ->where('company_id', $user->company_id)
            ->latest()
            ->get();
    }

    return $data->map(function ($req) {
        return [
            'id'              => $req->id,
            'title'           => $req->title,
            'description'     => $req->description,
            'status'          => $req->status,
            'photo'           => $req->photo,
            'category_id'     => $req->category_id,
            'category'        => $req->categoryRelation->name ?? '-',
            'sub_category_id' => $req->sub_category_id,
            'sub_category'    => $req->subCategory->name ?? '-',
            'branch_id'       => $req->branch_id,
            'branch'          => $req->branch->name ?? '-',
            'urgency'         => $req->urgency,
            'technician_id'   => $req->technician_id,
            'technician_name' => $req->technician->name ?? '-',
            'schedule_date'   => $req->schedule_date,
            'completion_note' => $req->completion_note,
            'completed_at'    => $req->completed_at,
            'material_used'   => $req->material_used,
            'materials'       => $req->materials->map(fn($m) => [
                'item_name' => $m->item_name,
                'qty'       => $m->qty,
                'unit'      => $m->unit,
                'status'    => $m->status,
            ]),
            'spk_sent_at'     => $req->spk_sent_at,
            'spk_number'      => $req->spk_number,
            'created_at'      => $req->created_at,
            'wo_id'           => $req->workOrder?->id,
        ];
    });
}

public function show($id)
{
    $user = auth()->user();

    $req = RequestModel::with('branch')
    ->where('company_id', $user->company_id)
    ->find($id);

    if (!$req) {
        return response()->json([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }
    if ($user->role === 'pic' && $req->user_id !== $user->id) {
        return response()->json([
            'message' => 'Tidak punya akses'
        ], 403);
    }

   if ($user->role === 'admin') {
        $branchCompanyId = $req->branch->company_id ?? null;
        
        if ($branchCompanyId !== $user->company_id) {
            return response()->json(['message' => 'Beda company'], 403);
        }
    }

    return response()->json([
    'id' => $req->id,
    'title' => $req->title,
    'description' => $req->description,
    'category' => $req->categoryRelation->name ?? '-',
    'status' => $req->status,
    'urgency' => $req->urgency,
    'photo' => $req->photo,
    'category_id' => $req->category_id,
    'branch' => $req->branch->name ?? '-'
]);
}

public function sendSpk($id)
{
    $request = MaintenanceRequest::with([
        'category', 'subCategory', 'branch',
        'worker', 'createdBy', 'spkSentBy'
    ])->findOrFail($id);

    if (!$request->technician_id) {
        return response()->json(['message' => 'Tukang belum ditentukan'], 422);
    }
    if (!$request->schedule_date) {
        return response()->json(['message' => 'Tukang belum mengatur jadwal'], 422);
    }

    if (!$request->spk_number) {
        $request->spk_number = SpkHelper::generate('repair');
    }

    $request->spk_sent_at = Carbon::now();
    $request->spk_sent_by = auth()->id();
    $request->save();

    return response()->json([
        'message'    => 'SPK berhasil dikirim',
        'spk_number' => $request->spk_number,
        'spk_sent_at'=> $request->spk_sent_at,
    ]);
}

public function workOrder($id)
{
    $wo = MaintenanceRequest::with([
        'category', 'subCategory', 'branch',
        'worker', 'createdBy', 'spkSentBy', 'materials'
    ])->findOrFail($id);

    $wo->type = 'repair'; 

    $company = \App\Models\Company::first();

    return response()->json([
        'wo'       => $wo,
        'company'  => $company,
        'materials'=> $wo->materials ?? [],
    ]);
}
public function materialRequests(Request $request)
{
    try {
        $query = \App\Models\MaterialRequest::with('repairRequest')
            ->where('status', 'pending');

        if ($request->request_id) {
            $query->where('repair_request_id', $request->request_id);
        }

        return response()->json($query->get());
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error material',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function approveMaterial($id)
{
    $material = \App\Models\MaterialRequest::findOrFail($id);
    $material->status = 'approved';
    $material->save();
    $remaining = \App\Models\MaterialRequest::where('repair_request_id', $material->repair_request_id)
        ->where('status', 'pending')
        ->count();
    if ($remaining === 0) {
        $req = RequestModel::find($material->repair_request_id);
        $req->status = 'scheduled'; 
        $req->save();
    }

    return response()->json(['message' => 'Material ready']);
}
public function approveAllMaterial($id)
{
     $user = auth()->user();
    \App\Models\MaterialRequest::where('repair_request_id', $id)
        ->update(['status' => 'approved']);

    $req = RequestModel::where('company_id', $user->company_id)
    ->findOrFail($id);

    if ($req) {
        $req->status = 'material_ready';
        $req->save();
    }

    return response()->json(['message' => 'Material ready']);
}
public function approve(Request $request, $id)
{
    $user = auth()->user();

    if ($user->role !== 'admin') {
        return response()->json(['message' => 'Tidak punya akses'], 403);
    }

    $request->validate([
        'urgency' => 'required|in:low,medium,high',
        'technician_id' => 'nullable|exists:users,id'
    ]);
    $req = RequestModel::where('company_id', $user->company_id)
    ->findOrFail($id);
    if (!$req) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    if ($req->status !== 'pending') {
        return response()->json(['message' => 'Sudah diproses'], 400);
    }
    $req->status = 'approved';
    $req->urgency = $request->urgency;
    $req->technician_id = $request->technician_id ?? null;
    $req->save();

    return response()->json(['message' => 'Berhasil approve']);
}

public function assignTechnician(Request $request, $id)
{
    $user = auth()->user();

    if ($user->role !== 'admin') {
        return response()->json(['message' => 'Tidak punya akses'], 403);
    }

    $request->validate([
        'technician_id' => 'required|exists:users,id'
    ]);

    $req = RequestModel::where('company_id', $user->company_id)
    ->findOrFail($id);

    if (!$req) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    $req->technician_id = $request->technician_id;
    $req->save();

    return response()->json(['message' => 'Tukang berhasil ditentukan']);
}

public function reject(Request $request, $id)
{
    $user = auth()->user();

    if (!in_array($user->role, ['admin'])) {
        return response()->json([
            'message' => 'Tidak punya akses'
        ], 403);
    }

    $request->validate([
        'reason' => 'required|string'
    ]);

    $req = RequestModel::findOrFail($id);

    $req->status = 'rejected';
    $req->reject_reason = $request->reason;
    $req->save();

    return response()->json([
        'message' => 'Request rejected'
    ]);
}

public function assign(Request $request, $id)
{
    $user = auth()->user();

    if (!in_array($user->role, ['admin_ga','super_admin'])) {
        return response()->json([
            'message' => 'Tidak punya akses'
        ], 403);
    }

    $request->validate([
        'technician' => 'required|string'
    ]);

    $req = RequestModel::where('id', $id)
        ->where('branch_id', $user->branch_id)
        ->firstOrFail();

    $req->technician = $request->technician;
    $req->status = 'assigned';
    $req->save();

    return response()->json([
        'message' => 'Tukang berhasil ditentukan'
    ]);
}

public function getSubCategory($categoryId)
{
    $data = SubCategory::where('category_id', $categoryId)
        ->select('id', 'name')  
        ->get();                
    return response()->json($data);
}


}