<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
 public function store(Request $request)
{
    $auth = auth()->user(); // 🔥 WAJIB

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:admin,pic,technician',
        'branch_id' => 'nullable'
    ]);

    $newUser = User::create([
        'name' => $request->name,
        'email' => $request->email,

        // 🔥 DEFAULT PASSWORD
        'password' => Hash::make('123456'),

        'mode' => 'company',
        'system_type' => $auth->system_type,
        'company_id' => $auth->company_id,
        'branch_id' => $request->branch_id,

        'role' => $request->role,
        'status' => 'active',

        // 🔥 WAJIB GANTI PASSWORD
        'must_change_password' => true
    ]);

    ActivityLog::create([
        'description' => 'Menambahkan user ' . $newUser->name
    ]);

    return response()->json([
        'message' => 'User berhasil dibuat',
        'user' => $newUser
    ]);
}

public function activate($id)
{
    $user = User::findOrFail($id);

    $user->update([
        'status' => 'active'
    ]);

    return response()->json([
        'message' => 'User diaktifkan'
    ]);
}

public function index()
{
    $auth = auth()->user();

    $users = User::where('company_id', $auth->company_id)
        ->with('branch')
        ->get();

    return response()->json($users);
}
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'branch_id' => $request->branch_id
    ]);

    return response()->json(['message' => 'User updated']);
}

public function toggle($id)
{
    $user = User::findOrFail($id);

    $user->status = $user->status === 'active'
        ? 'inactive'
        : 'active';

    $user->save();

    return response()->json(['message' => 'User updated']);
}

public function destroy($id)
{
    User::findOrFail($id)->delete();

    return response()->json(['message' => 'User deleted']);
}

public function resetPassword($id)
{
    $user = User::findOrFail($id);

    $user->update([
        'password' => Hash::make('123456'),
        'must_change_password' => true
    ]);

    return response()->json([
        'message' => 'Password direset ke 123456'
    ]);
}
}
