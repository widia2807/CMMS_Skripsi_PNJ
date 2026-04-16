<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
 public function store(Request $request)
{
    $auth = auth()->user(); 
    if (!$auth) {
        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:admin,pic,technician',
        'branch_id' => 'nullable',
        'category_id' => 'nullable'
    ]);

    $newUser = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make('123456'),

    'mode' => 'company',
    'system_type' => $auth->system_type,
    'company_id' => $auth->company_id,

    'branch_id' => $request->role === 'technician' ? null : $request->branch_id,
    'category_id' => $request->role === 'technician' ? $request->category_id : null,

    'role' => $request->role,
    'status' => 'inactive',
    'must_change_password' => true
]);
Mail::raw(
    "Halo {$newUser->name},\n\nAkun kamu telah dibuat.\nEmail: {$newUser->email}\nPassword: 123456\n\nSilakan login dan ganti password kamu.",
    function ($message) use ($newUser) {
        $message->to($newUser->email)
                ->subject('Akun CMMS Kamu Telah Dibuat');
    }
);
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

    if ($user->role === 'super_admin') {
        return response()->json([
            'message' => 'Super admin tidak bisa diubah'
        ], 403);
    }

    $user->update(['status' => 'active']);

    return response()->json([
        'message' => 'User diaktifkan'
    ]);
}

public function index()
{
    $auth = auth()->user();

    if (!$auth) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $users = User::where('company_id', $auth->company_id)
        ->with('branch', 'category')
        ->get();

    return response()->json($users);
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // 🚫 protect super admin
    if ($user->role === 'super_admin') {
        return response()->json([
            'message' => 'Super admin tidak bisa diubah'
        ], 403);
    }

    // ✅ VALIDATION WAJIB DI SINI
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|in:admin,pic,technician',
        'branch_id' => 'nullable',
        'category_id' => 'nullable'
    ]);

    // ✅ UPDATE
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'branch_id' => $request->branch_id,
        'category_id' => 'nullable'
    ]);

    return response()->json([
        'message' => 'User updated'
    ]);
}


public function destroy($id)
{
    // 🔒 HARUS PALING ATAS
    if (auth()->user()->role !== 'super_admin') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $user = User::findOrFail($id);

    // proteksi tambahan (jaga2)
    if ($user->role === 'super_admin') {
        return response()->json([
            'message' => 'Super admin tidak bisa dihapus'
        ], 403);
    }

    $user->delete();

    return response()->json(['message' => 'User deleted']);
}

public function resetPassword($id)
{
    $user = User::findOrFail($id);
    if (!in_array(auth()->user()->role, ['admin', 'super_admin'])) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    $user->update([
        'password' => Hash::make('123456'),
        'must_change_password' => true
    ]);

    return response()->json([
        'message' => 'Password direset ke 123456'
    ]);
}

public function disable($id)
{
    $user = User::findOrFail($id);

    if ($user->role === 'super_admin') {
        return response()->json([
            'message' => 'Super admin tidak bisa diubah'
        ], 403);
    }

    $user->update([
        'status' => 'inactive'
    ]);

    return response()->json([
        'message' => 'User dinonaktifkan'
    ]);
}
}
