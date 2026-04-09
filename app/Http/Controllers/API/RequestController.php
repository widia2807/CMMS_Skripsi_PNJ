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

    $data = RequestModel::where('branch_id', $user->branch_id)
        ->latest()
        ->get();

    return response()->json($data);
}

public function show($id)
{
    $user = auth()->user();

    $req = RequestModel::where('id', $id)
        ->where('branch_id', $user->branch_id)
        ->first();

    if (!$req) {
        return response()->json([
            'message' => 'Data tidak ditemukan'
        ], 404);
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


}