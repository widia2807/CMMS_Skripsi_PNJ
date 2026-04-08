<?php

namespace App\Http\Controllers\API\PIC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RepairRequest;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $total = RepairRequest::where('user_id', $user->id)->count();

        $progress = RepairRequest::where('user_id', $user->id)
                    ->where('status', 'on_progress')
                    ->count();

        $done = RepairRequest::where('user_id', $user->id)
                ->where('status', 'done')
                ->count();

        return response()->json([
            'total' => $total,
            'progress' => $progress,
            'done' => $done
        ]);
    }
}