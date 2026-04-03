<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\ActivityLog;
use App\Models\User;

class BranchController extends Controller
{
    public function store(Request $request)
{
    $user = auth()->user();
     
    $request->validate([
        'name' => 'required'
    ]);

    $branch = Branch::create([
        'company_id' => $user->company_id,
        'name' => $request->name,
        'type' => $request->type,
        'status' => 'active'
    ]);

     ActivityLog::create([
        'description' => 'Berhasil menambahkan cabang ' . $branch->name
    ]);

    return response()->json([
        'message' => 'Cabang berhasil dibuat',
        'branch' => $branch
    ]);
}
public function active()
{
    $user = auth()->user();

    $branches = Branch::where('company_id', $user->company_id)
        ->where('status', 'active')
        ->get();

    return response()->json($branches);
}

public function index()
{
    $user = auth()->user();

    return response()->json(
        Branch::where('company_id', $user->company_id)->get()
    );
}

public function toggle($id)
{
    $branch = Branch::findOrFail($id);

    $branch->status = $branch->status === 'active'
        ? 'inactive'
        : 'active';

    $branch->save();

    // 🔥 SYNC USER
    User::where('branch_id', $branch->id)
        ->update([
            'status' => $branch->status === 'active'
                ? 'active'
                : 'inactive'
        ]);

    return response()->json(['message' => 'updated']);
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'type' => 'required|in:branch,ho'
    ]);

    $branch = Branch::findOrFail($id);

    $branch->update([
        'name' => $request->name,
        'type' => $request->type
    ]);

    return response()->json(['message' => 'updated']);
}

public function destroy($id)
{
    Branch::findOrFail($id)->delete();

    return response()->json(['message' => 'deleted']);
}
}
