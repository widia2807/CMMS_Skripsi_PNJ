<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;

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
    ]);

    $data['user_id'] = $user->id;
    $data['status'] = 'pending';

    $req = RequestModel::create($data);

    return response()->json([
        'message' => 'Pengajuan berhasil dibuat',
        'data' => $req
    ]);
}
}