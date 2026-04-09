<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;

class LiteDashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    // 🔥 FILTER WAJIB
    $query = User::where('system_type', 'lite')
                 ->where('branch_id', $user->branch_id);

    return response()->json([
        'total' => $query->count(),
        'pending' => (clone $query)->where('status', 'pending')->count(),
        'active' => (clone $query)->where('status', 'active')->count()
    ]);
}
}