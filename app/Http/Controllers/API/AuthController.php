<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Company;
use App\Models\Academic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // 🔥 REGISTER
    public function register(Request $request)
{
    // 1. VALIDASI
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'system_type' => 'required|in:full,lite',
        'company_name' => 'required'
    ]);

    // 2. BUAT COMPANY
    $company = Company::create([
        'name' => $request->company_name
    ]);

    // 3. TENTUKAN ROLE 🔥
    $role = $request->system_type == 'full' ? 'super_admin' : 'admin';

    // 4. BUAT USER 🔥 (INI YANG KAMU TANYA)
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),

        'mode' => 'company',
        'system_type' => $request->system_type,
        'company_id' => $company->id,

        'role' => $role,
        'status' => 'active'
    ]);

    // 5. RESPONSE
    return response()->json([
        'message' => 'Register berhasil',
        'user' => $user
    ]);
}
    // 🔐 LOGIN
    public function login(Request $request)
{
    // 1. VALIDASI
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // 2. CEK USER
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Email atau password salah'
        ], 401);
    }

    // 3. CEK STATUS USER
    if ($user->status !== 'active') {
        return response()->json([
            'message' => 'User tidak aktif'
        ], 403);
    }

    // 4. CEK CABANG (kalau ada branch_id)
    if ($user->branch && $user->branch->status === 'inactive') {
        return response()->json([
            'message' => 'Cabang tidak aktif'
        ], 403);
    }

    // 5. BUAT TOKEN 🔥
    $token = $user->createToken('auth_token')->plainTextToken;

    // 6. RESPONSE
    return response()->json([
        'message' => 'Login berhasil',
        'user' => $user,
        'token' => $token,
        'system_type' => $user->system_type,
        'role' => $user->role
    ]);
}
}