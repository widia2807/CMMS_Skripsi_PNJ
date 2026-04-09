<?php

namespace App\Http\Controllers\PIC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RepairRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $total = RepairRequest::where('branch_id', $user->branch_id)->count();

        $progress = RepairRequest::where('branch_id', $user->branch_id)
            ->where('status', 'on_progress')
            ->count();

        $done = RepairRequest::where('branch_id', $user->branch_id)
            ->where('status', 'done')
            ->count();

        return response()->json([
            'total' => $total,
            'progress' => $progress,
            'done' => $done
        ]);
    }

    public function activity()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $activities = RepairRequest::where('branch_id', $user->branch_id)
            ->latest()
            ->take(5)
            ->get(['description']);

        return response()->json($activities);
    }
}