<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Branch;

class DashboardController extends Controller
{
  

public function index()
{
    $user = User::first(); // sementara

    $totalCabang = Branch::where('company_id', $user->company_id)->count();

    $totalUser = User::where('company_id', $user->company_id)->count();

    $pendingUser = User::where('company_id', $user->company_id)
                        ->where('status', 'pending')
                        ->count();

    $activeUser = User::where('company_id', $user->company_id)
                        ->where('status', 'active')
                        ->count();

      return response()->json([
        'total_cabang' => Branch::where('company_id', $user->company_id)->count(),

        'total_user' => User::where('company_id', $user->company_id)->count(),

        'pending_user' => User::where('company_id', $user->company_id)
                                ->where('status', 'pending')
                                ->count(),

        'active_user' => User::where('company_id', $user->company_id)
                                ->where('status', 'active')
                                ->count(),
    ]);
}

 public function activity()
    {
        return ActivityLog::latest()->take(5)->get();
    }
}
