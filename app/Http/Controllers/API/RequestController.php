<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        'category' => 'required|in:kelistrikan,sipil',
        'sub_category' => 'required|string',
        'photo' => 'nullable|image|max:2048'
    ]);

    // tambahan data otomatis
    $data['user_id'] = $user->id;
    $data['branch_id'] = $user->branch_id; // 🔥 penting biar gak campur cabang
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
        return RequestModel::where('user_id', $user->id)->get();
    }

    return RequestModel::latest()->get(); // 🔥 aman
}

public function show($id)
{
    $user = auth()->user();

    $req = RequestModel::find($id);

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

    // kalau admin → boleh lihat semua di cabang
    if (in_array($user->role, ['admin_ga','super_admin']) &&
        $req->branch_id !== $user->branch_id) {
        return response()->json([
            'message' => 'Beda cabang'
        ], 403);
    }

    return response()->json($req);
}

public function getSubCategory($category)
{
    // mapping category ke id
    $categoryMap = [
        'kelistrikan' => 1,
        'sipil' => 2
    ];

    $categoryId = $categoryMap[$category] ?? null;

    if (!$categoryId) {
        return response()->json([]);
    }

    $data = \DB::table('sub_categories')
        ->where('category_id', $categoryId)
        ->pluck('name');

    return response()->json($data);
}

public function approve(Request $request, $id)
{
    $user = auth()->user();

    if (!in_array($user->role, ['admin_ga','super_admin'])) {
        return response()->json([
            'message' => 'Tidak punya akses'
        ], 403);
    }

    $request->validate([
        'urgency' => 'required|in:low,medium,high'
    ]);

    $req = RequestModel::where('id', $id)
        ->where('branch_id', $user->branch_id)
        ->firstOrFail();

    if ($req->status !== 'pending') {
        return response()->json([
            'message' => 'Sudah diproses'
        ], 400);
    }

    $req->status = 'approved';
    $req->urgency = $request->urgency;
    $req->approved_at = now();
    $req->reject_reason = null;
    $req->save();

    return response()->json([
        'message' => 'Request approved'
    ]);
}

public function reject(Request $request, $id)
{
    $user = auth()->user();

    if (!in_array($user->role, ['admin_ga','super_admin'])) {
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
}