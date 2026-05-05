<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\RepairRequest as RequestModel;

class RequestController extends Controller
{
    public function store(Request $request)
{
    $user = auth()->user();

    // hanya PIC
    if ($user->role !== 'pic') {
        return response()->json([
            'message' => 'Hanya PIC yang bisa mengajukan'
        ], 403);
    }

    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'sub_category' => 'required|string',
        'photo' => 'nullable|image|max:2048'
    ]);

    // tambahan data otomatis
    $data['user_id'] = $user->id;
    $data['branch_id'] = $user->branch_id; 
    $data['status'] = 'pending';

    // upload foto
    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('requests', 'public');
    }

    $req = RequestModel::create($data);

    return response()->json([
        'message' => 'Pengajuan berhasil dibuat',
        'data' => $req
    ]);
}

public function index()
{
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }

    
    if ($user->role === 'pic') {
        $data = RequestModel::with('categoryRelation')
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    } 
    
    else {
        $data = RequestModel::with('categoryRelation')
            ->latest()
            ->get();
    }

    
    return $data->map(function ($req) {
        return [
            'id' => $req->id,
            'title' => $req->title,
            'description' => $req->description,
            'status' => $req->status,
            'photo' => $req->photo,
            'category_id' => $req->category_id,
           
            'category' => $req->categoryRelation->name ?? '-',
        ];
    });
}

public function show($id)
{
    $user = auth()->user();

    $req = RequestModel::with('branch')->find($id);

    if (!$req) {
        return response()->json([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    // kalau PIC → hanya lihat miliknya
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
    // 🔥 TAMBAHAN INI
    'branch' => $req->branch->name ?? '-'
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

    // 🔥 cek semua material untuk request ini
    $remaining = \App\Models\MaterialRequest::where('repair_request_id', $material->repair_request_id)
        ->where('status', 'pending')
        ->count();

    // kalau semua sudah approved
    if ($remaining === 0) {
        $req = RequestModel::find($material->repair_request_id);
        $req->status = 'scheduled'; // atau langsung boleh kerja
        $req->save();
    }

    return response()->json(['message' => 'Material ready']);
}
public function approveAllMaterial($id)
{
    \App\Models\MaterialRequest::where('repair_request_id', $id)
        ->update(['status' => 'approved']);

    $req = RequestModel::find($id);

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

    // 🔥 ADMIN = global (tidak pakai branch)
    $req = RequestModel::find($id);

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

    $req = RequestModel::find($id);

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
        ->pluck('name');

    return response()->json($data);
}


}